<?php

namespace App\Services;

use HalilCosdu\Replicate\Facades\Replicate;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * AIService — Lapisan integrasi AI untuk Pinteria
 *
 * ┌─────────────────────────────────────────────────────────┐
 * │  Groq (OpenAI-compatible endpoint)                      │
 * │   → Llama 3.3  : chat/tanya-jawab edukatif adaptif      │
 * │   → Whisper V3 : transkripsi audio soal/jawaban          │
 * │   Driver       : Laravel Http:: (bawaan, tanpa package) │
 * ├─────────────────────────────────────────────────────────┤
 * │  Replicate                                              │
 * │   → SDXL       : generasi gambar ilustrasi konten       │
 * │   Driver       : halilcosdu/laravel-replicate (Facade)  │
 * └─────────────────────────────────────────────────────────┘
 */
class AIService
{
    /** HTTP client pre-configured untuk Groq */
    private PendingRequest $groq;

    public function __construct()
    {
        $this->groq = Http::baseUrl(config('ai.groq.base_url'))
            ->withToken(config('ai.groq.api_key'))
            ->timeout(config('ai.groq.timeout', 30))
            ->acceptJson();
    }

    // =========================================================================
    // 1. LLAMA 3.3 — Chat / Tanya-Jawab Edukatif  (via Groq + Http::)
    // =========================================================================

    /**
     * Kirim pesan ke Llama 3.3 dan terima balasan.
     *
     * @param  string       $userMessage  Pesan/pertanyaan dari pengguna
     * @param  array        $history      Riwayat [['role'=>..., 'content'=>...]]
     * @param  string|null  $childContext Konteks profil anak (opsional)
     * @return array{success: bool, reply: string, usage: array}
     */
    public function chat(string $userMessage, array $history = [], ?string $childContext = null): array
    {
        $systemPrompt = config('ai.system_prompt');

        if ($childContext) {
            $systemPrompt .= "\n\nKonteks anak: {$childContext}";
        }

        $messages = array_merge(
            [['role' => 'system', 'content' => $systemPrompt]],
            $history,
            [['role' => 'user', 'content' => $userMessage]]
        );

        try {
            $response = $this->groq->post('/chat/completions', [
                'model'       => config('ai.groq.chat_model'),
                'messages'    => $messages,
                'temperature' => 0.7,
                'max_tokens'  => 1024,
            ]);

            if ($response->failed()) {
                Log::error('Groq chat error', [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);
                return ['success' => false, 'reply' => 'Maaf, asisten AI sedang tidak tersedia.', 'usage' => []];
            }

            $data = $response->json();

            return [
                'success' => true,
                'reply'   => $data['choices'][0]['message']['content'] ?? '',
                'usage'   => $data['usage'] ?? [],
            ];
        } catch (\Throwable $e) {
            Log::error('Groq chat exception', ['message' => $e->getMessage()]);
            return ['success' => false, 'reply' => 'Terjadi kesalahan saat menghubungi AI.', 'usage' => []];
        }
    }

    /**
     * Buat petunjuk soal quiz tanpa membocorkan jawaban.
     *
     * @return array{success: bool, hint: string}
     */
    public function generateQuizHint(string $questionText, string $lessonTitle): array
    {
        $prompt = "Berikan petunjuk singkat (maksimal 2 kalimat) untuk membantu anak menjawab soal berikut tanpa memberikan jawaban langsung.\n\nJudul Pelajaran: {$lessonTitle}\nSoal: {$questionText}";

        $result = $this->chat($prompt);

        return [
            'success' => $result['success'],
            'hint'    => $result['reply'],
        ];
    }

    // =========================================================================
    // 2. WHISPER V3 — Transkripsi Audio  (via Groq + Http::)
    // =========================================================================

    /**
     * Transkripsi file audio menggunakan Whisper V3.
     *
     * @param  string  $audioPath  Path absolut ke file audio (mp3/wav/m4a/webm)
     * @param  string  $language   Kode bahasa ISO-639-1 (default: 'id')
     * @return array{success: bool, transcript: string, language: string}
     */
    public function transcribeAudio(string $audioPath, string $language = 'id'): array
    {
        if (!file_exists($audioPath)) {
            Log::warning('Whisper: file audio tidak ditemukan', ['path' => $audioPath]);
            return ['success' => false, 'transcript' => '', 'language' => $language];
        }

        try {
            // Whisper menggunakan multipart form, bukan JSON — Http:: tetap dipakai
            $response = Http::baseUrl(config('ai.groq.base_url'))
                ->withToken(config('ai.groq.api_key'))
                ->timeout(config('ai.groq.timeout', 30))
                ->attach('file', fopen($audioPath, 'r'), basename($audioPath))
                ->post('/audio/transcriptions', [
                    'model'           => config('ai.groq.whisper_model'),
                    'language'        => $language,
                    'response_format' => 'json',
                ]);

            if ($response->failed()) {
                Log::error('Whisper error', [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);
                return ['success' => false, 'transcript' => '', 'language' => $language];
            }

            $data = $response->json();

            return [
                'success'    => true,
                'transcript' => $data['text'] ?? '',
                'language'   => $data['language'] ?? $language,
            ];
        } catch (\Throwable $e) {
            Log::error('Whisper exception', ['message' => $e->getMessage()]);
            return ['success' => false, 'transcript' => '', 'language' => $language];
        }
    }

    // =========================================================================
    // 3. GOOGLE CLOUD TTS — Text-to-Speech untuk Mode Tunanetra
    // =========================================================================

    /**
     * Ubah teks menjadi file audio MP3 menggunakan Google Cloud Text-to-Speech.
     *
     * Mengembalikan konten binary MP3. Jika API key belum diisi, mengembalikan null
     * sehingga frontend bisa fallback ke Web Speech API bawaan browser.
     *
     * @param  string  $text     Teks yang akan diubah menjadi suara
     * @return string|null       Konten binary MP3, atau null jika gagal / key kosong
     */
    public function textToSpeech(string $text): ?string
    {
        $apiKey = config('ai.google_tts.api_key');

        // Jika API key belum dikonfigurasi, langsung return null (fallback ke browser TTS)
        if (empty($apiKey)) {
            Log::info('Google TTS: API key belum dikonfigurasi, gunakan browser TTS sebagai fallback.');
            return null;
        }

        try {
            $response = Http::timeout(config('ai.google_tts.timeout', 15))
                ->post(config('ai.google_tts.endpoint') . '?key=' . $apiKey, [
                    'input'       => ['text' => $text],
                    'voice'       => [
                        'languageCode' => config('ai.google_tts.language_code', 'id-ID'),
                        'name'         => config('ai.google_tts.voice', 'id-ID-Wavenet-A'),
                    ],
                    'audioConfig' => [
                        'audioEncoding' => 'MP3',
                        'speakingRate'  => (float) config('ai.google_tts.speaking_rate', 0.9),
                        'pitch'         => 0.0,
                    ],
                ]);

            if ($response->failed()) {
                Log::error('Google TTS error', [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);
                return null;
            }

            $audioContent = $response->json('audioContent');

            if (empty($audioContent)) {
                Log::warning('Google TTS: audioContent kosong dalam response.');
                return null;
            }

            // Decode base64 → binary MP3
            return base64_decode($audioContent);
        } catch (\Throwable $e) {
            Log::error('Google TTS exception', ['message' => $e->getMessage()]);
            return null;
        }
    }

    // =========================================================================
    // 4. LLAMA 3.3 — Evaluasi Jawaban Quiz (Mode Tunanetra)
    // =========================================================================

    /**
     * Nilai jawaban lisan anak menggunakan Llama 3.3 secara semantik.
     *
     * Tidak hanya mencocokkan string persis — Llama memahami variasi ejaan,
     * sinonim, dan cara pengucapan yang berbeda namun bermakna sama.
     *
     * @param  string  $childAnswer    Transkripsi jawaban anak dari Whisper
     * @param  string  $correctAnswer  Jawaban benar yang tersimpan di database
     * @param  string  $question       Pertanyaan kuis (konteks tambahan)
     * @return array{success: bool, is_correct: bool, feedback: string}
     */
    public function evaluateQuizAnswer(string $childAnswer, string $correctAnswer, string $question): array
    {
        $prompt = <<<PROMPT
Kamu adalah penilai jawaban kuis untuk anak-anak. Tugasmu sederhana: nilai apakah jawaban anak BENAR atau SALAH.

Pertanyaan: {$question}
Jawaban Benar: {$correctAnswer}
Jawaban Anak: {$childAnswer}

Aturan penilaian:
- Anggap BENAR jika maknanya sama meskipun ejaannya sedikit berbeda atau ada kata tambahan
- Anggap BENAR jika anak menyebut angka yang sama dalam bentuk berbeda ("dua belas" = "12")
- Anggap SALAH jika maknanya jelas berbeda

Berikan respons dalam format JSON PERSIS seperti ini (tanpa teks lain):
{"benar": true, "pesan": "Teks pujian atau koreksi singkat dalam Bahasa Indonesia, maksimal 15 kata, ramah untuk anak"}
PROMPT;

        try {
            $response = $this->groq->post('/chat/completions', [
                'model'       => config('ai.groq.chat_model'),
                'messages'    => [
                    ['role' => 'system', 'content' => 'Kamu adalah penilai jawaban kuis anak. Selalu balas dengan JSON valid.'],
                    ['role' => 'user',   'content' => $prompt],
                ],
                'temperature'    => 0.1,
                'max_tokens'     => 80,
                'response_format' => ['type' => 'json_object'],
            ]);

            if ($response->failed()) {
                Log::error('Llama evaluate error', ['status' => $response->status()]);
                return $this->fallbackEvaluate($childAnswer, $correctAnswer);
            }

            $content = $response->json('choices.0.message.content', '{}');
            $parsed  = json_decode($content, true);

            if (!isset($parsed['benar'])) {
                return $this->fallbackEvaluate($childAnswer, $correctAnswer);
            }

            return [
                'success'    => true,
                'is_correct' => (bool) $parsed['benar'],
                'feedback'   => $parsed['pesan'] ?? ($parsed['benar'] ? 'Hebat, jawabanmu benar!' : "Belum tepat. Jawaban yang benar adalah {$correctAnswer}."),
            ];
        } catch (\Throwable $e) {
            Log::error('Llama evaluate exception', ['message' => $e->getMessage()]);
            return $this->fallbackEvaluate($childAnswer, $correctAnswer);
        }
    }

    /**
     * Fallback evaluasi sederhana (pencocokan string) jika Llama gagal.
     */
    private function fallbackEvaluate(string $childAnswer, string $correctAnswer): array
    {
        $normalize = fn(string $s) => strtolower(trim(preg_replace('/[^a-z0-9\s]/i', '', $s)));
        $isCorrect = $normalize($childAnswer) === $normalize($correctAnswer);

        return [
            'success'    => true,
            'is_correct' => $isCorrect,
            'feedback'   => $isCorrect
                ? 'Hebat, jawabanmu benar!'
                : "Belum tepat. Jawaban yang benar adalah {$correctAnswer}.",
        ];
    }

    // =========================================================================
    // 5. STABLE DIFFUSION XL — Generasi Gambar  (via Replicate Facade)
    // =========================================================================

    /**
     * Mulai prediksi SDXL secara asynchronous menggunakan Replicate Facade.
     *
     * Replicate::createPrediction() mengembalikan prediction_id yang bisa
     * digunakan untuk polling status via checkImageStatus().
     *
     * @return array{success: bool, prediction_id: string|null, status_url: string|null}
     */
    public function startImagePrediction(string $prompt, string $negativePrompt = 'violence, adult content, scary'): array
    {
        $fullPrompt = "children educational illustration, colorful, friendly, cartoon style: {$prompt}";

        // Ambil hanya versi hash dari string "owner/model:version"
        $modelVersion = last(explode(':', config('ai.replicate.sdxl_model')));

        try {
            $response = Replicate::createPrediction([
                'version' => $modelVersion,
                'input'   => [
                    'prompt'              => $fullPrompt,
                    'negative_prompt'     => $negativePrompt,
                    'width'               => 768,
                    'height'              => 768,
                    'num_outputs'         => 1,
                    'guidance_scale'      => 7.5,
                    'num_inference_steps' => 30,
                ],
            ]);

            if ($response->failed()) {
                Log::error('Replicate createPrediction error', [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);
                return ['success' => false, 'prediction_id' => null, 'status_url' => null];
            }

            $data = $response->json();

            return [
                'success'       => true,
                'prediction_id' => $data['id'] ?? null,
                'status_url'    => $data['urls']['get'] ?? null,
            ];
        } catch (\Throwable $e) {
            Log::error('Replicate startImagePrediction exception', ['message' => $e->getMessage()]);
            return ['success' => false, 'prediction_id' => null, 'status_url' => null];
        }
    }

    /**
     * Cek status prediksi gambar SDXL menggunakan Replicate Facade.
     *
     * Status yang mungkin: starting | processing | succeeded | failed | canceled
     *
     * @return array{success: bool, status: string, image_url: string|null}
     */
    public function checkImageStatus(string $predictionId): array
    {
        try {
            $response = Replicate::getPrediction($predictionId);

            if ($response->failed()) {
                Log::error('Replicate getPrediction error', [
                    'prediction_id' => $predictionId,
                    'status'        => $response->status(),
                ]);
                return ['success' => false, 'status' => 'unknown', 'image_url' => null];
            }

            $data      = $response->json();
            $status    = $data['status'] ?? 'unknown';
            $imageUrl  = ($status === 'succeeded') ? ($data['output'][0] ?? null) : null;

            return [
                'success'   => true,
                'status'    => $status,
                'image_url' => $imageUrl,
            ];
        } catch (\Throwable $e) {
            Log::error('Replicate checkImageStatus exception', ['message' => $e->getMessage()]);
            return ['success' => false, 'status' => 'error', 'image_url' => null];
        }
    }
}
