<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\GenerateImageJob;
use App\Models\ImageGeneration;
use App\Services\AIService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * AIController — Endpoint API untuk fitur kecerdasan buatan Pinteria
 *
 * Routes (semua protected by auth:api middleware):
 *  POST /ai/chat                       → Tanya-jawab dengan Llama 3.3
 *  POST /ai/quiz-hint                  → Minta petunjuk soal dari AI
 *  POST /ai/transcribe                 → Transkripsi audio (Whisper V3)
 *  POST /ai/tts                        → Text-to-Speech (Google Cloud TTS)
 *  POST /ai/evaluate-answer            → Transkripsi + Penilaian AI (Tunanetra pipeline)
 *  POST /ai/generate-image             → Generate gambar, langsung dispatch ke queue
 *  GET  /ai/image-status/{id}          → Cek status job generate gambar (by DB id)
 */
class AIController extends Controller
{
    public function __construct(private readonly AIService $ai) {}

    // ─────────────────────────────────────────────────────────────────────────
    // POST /ai/chat
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Tanya-jawab edukatif dengan Llama 3.3.
     *
     * Body JSON:
     * {
     *   "message": "Apa itu fotosintesis?",
     *   "history": [                          // opsional
     *     {"role": "user", "content": "..."},
     *     {"role": "assistant", "content": "..."}
     *   ],
     *   "child_context": "Nama: Budi, usia 7"  // opsional
     * }
     */
    public function chat(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'message'           => 'required|string|max:2000',
            'history'           => 'array',
            'history.*.role'    => 'in:user,assistant',
            'history.*.content' => 'string',
            'child_context'     => 'nullable|string|max:500',
        ]);

        $result = $this->ai->chat(
            $validated['message'],
            $validated['history'] ?? [],
            $validated['child_context'] ?? null
        );

        if (!$result['success']) {
            return response()->json(['message' => $result['reply']], 503);
        }

        return response()->json([
            'reply' => $result['reply'],
            'usage' => $result['usage'],
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // POST /ai/quiz-hint
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Minta petunjuk soal quiz tanpa membocorkan jawaban.
     *
     * Body JSON:
     * {
     *   "question": "Berapa hasil 3 × 4?",
     *   "lesson_title": "Perkalian Dasar"
     * }
     */
    public function quizHint(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'question'     => 'required|string|max:1000',
            'lesson_title' => 'required|string|max:255',
        ]);

        $result = $this->ai->generateQuizHint($validated['question'], $validated['lesson_title']);

        if (!$result['success']) {
            return response()->json(['message' => 'Gagal mengambil petunjuk.'], 503);
        }

        return response()->json(['hint' => $result['hint']]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // POST /ai/transcribe
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Transkripsi file audio menggunakan Whisper V3.
     *
     * Form Data (multipart):
     * - audio: file (mp3 / wav / m4a / webm / ogg, max 25MB)
     * - language: string (opsional, default: "id")
     */
    public function transcribe(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'audio'    => 'required|file|mimes:mp3,wav,m4a,webm,ogg|max:25600',
            'language' => 'nullable|string|size:2',
        ]);

        $path = $validated['audio']->store('temp/audio', 'local');
        $fullPath = Storage::disk('local')->path($path);

        try {
            $result = $this->ai->transcribeAudio($fullPath, $validated['language'] ?? 'id');
        } finally {
            Storage::disk('local')->delete($path);
        }

        if (!$result['success']) {
            return response()->json(['message' => 'Gagal melakukan transkripsi audio.'], 503);
        }

        return response()->json([
            'transcript' => $result['transcript'],
            'language'   => $result['language'],
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // POST /ai/tts
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Konversi teks ke audio MP3 menggunakan Google Cloud TTS.
     *
     * Jika GOOGLE_TTS_API_KEY belum diisi, mengembalikan 204 No Content
     * sehingga frontend tahu harus fallback ke browser SpeechSynthesis.
     *
     * Body JSON:
     * { "text": "Teks yang ingin disuarakan" }
     *
     * Response sukses: file MP3 binary (Content-Type: audio/mpeg)
     * Response fallback: 204 No Content (key belum dikonfigurasi)
     */
    public function tts(Request $request)
    {
        $validated = $request->validate([
            'text' => 'required|string|max:1000',
        ]);

        $mp3Binary = $this->ai->textToSpeech($validated['text']);

        // Kembalikan 204 jika TTS key belum ada → frontend fallback ke browser TTS
        if ($mp3Binary === null) {
            return response()->noContent();
        }

        return response($mp3Binary, 200, [
            'Content-Type'        => 'audio/mpeg',
            'Content-Disposition' => 'inline; filename="tts.mp3"',
            'Cache-Control'       => 'no-store',
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // POST /ai/evaluate-answer  (pipeline Tunanetra: Whisper → Llama)
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Pipeline lengkap Mode Tunanetra dalam satu endpoint:
     *  1. Terima file audio jawaban anak
     *  2. Transkripsi via Groq Whisper V3
     *  3. Evaluasi semantik via Groq Llama 3.3
     *  4. Kembalikan hasil evaluasi (benar/salah + pesan feedback)
     *
     * Form Data (multipart):
     * - audio          : file (mp3/wav/m4a/webm/ogg, max 25MB)
     * - correct_answer : string — jawaban benar dari database
     * - question       : string — pertanyaan kuis (untuk konteks Llama)
     * - language       : string (opsional, default: "id")
     *
     * Response JSON:
     * {
     *   "transcript" : "jawaban anak hasil whisper",
     *   "is_correct" : true/false,
     *   "feedback"   : "Teks pesan untuk diucapkan TTS ke anak"
     * }
     */
    public function evaluateAnswer(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'audio'          => 'required|file|mimes:mp3,wav,m4a,webm,ogg|max:25600',
            'correct_answer' => 'required|string|max:500',
            'question'       => 'required|string|max:1000',
            'language'       => 'nullable|string|size:2',
        ]);

        // Step 1 — Simpan audio sementara
        $path     = $validated['audio']->store('temp/audio', 'local');
        $fullPath = Storage::disk('local')->path($path);

        try {
            // Step 2 — Transkripsi via Whisper
            $transcription = $this->ai->transcribeAudio($fullPath, $validated['language'] ?? 'id');
        } finally {
            Storage::disk('local')->delete($path);
        }

        if (!$transcription['success'] || empty($transcription['transcript'])) {
            return response()->json([
                'message' => 'Gagal mentranskripsi audio. Coba bicara lebih jelas.',
            ], 422);
        }

        // Step 3 — Evaluasi semantik via Llama 3.3
        $evaluation = $this->ai->evaluateQuizAnswer(
            $transcription['transcript'],
            $validated['correct_answer'],
            $validated['question']
        );

        return response()->json([
            'transcript' => $transcription['transcript'],
            'is_correct' => $evaluation['is_correct'],
            'feedback'   => $evaluation['feedback'],
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Antri permintaan generate gambar SDXL ke background queue.
     *
     * Response langsung (202 Accepted) dengan `generation_id` untuk polling.
     * Gunakan GET /ai/image-status/{generation_id} untuk cek hasilnya.
     *
     * Body JSON:
     * {
     *   "prompt": "kucing belajar membaca buku di perpustakaan",
     *   "negative_prompt": "..."   // opsional
     * }
     */
    public function generateImage(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'prompt'          => 'required|string|max:500',
            'negative_prompt' => 'nullable|string|max:300',
        ]);

        // Buat record tracking di database
        /** @var \App\Models\User $user */
        $user = $request->user();

        $generation = ImageGeneration::create([
            'user_id'         => $user->id,
            'prompt'          => $validated['prompt'],
            'negative_prompt' => $validated['negative_prompt'] ?? null,
            'status'          => ImageGeneration::STATUS_PENDING,
        ]);

        // Dispatch job ke background queue (database driver)
        GenerateImageJob::dispatch($generation)
            ->onQueue('images');

        return response()->json([
            'generation_id' => $generation->id,
            'status'        => $generation->status,
            'message'       => 'Permintaan gambar diterima dan sedang diproses di background. Gunakan generation_id untuk cek status.',
            'poll_url'      => url("/api/ai/image-status/{$generation->id}"),
        ], 202);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // GET /ai/image-status/{generationId}
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Cek status permintaan generate gambar.
     *
     * Status yang mungkin:
     *  - pending     → Job belum diambil worker
     *  - processing  → Worker sedang polling Replicate
     *  - succeeded   → Gambar siap, lihat image_url
     *  - failed      → Gagal, lihat error_message
     */
    public function imageStatus(Request $request, string $generationId): JsonResponse
    {
        $generation = ImageGeneration::where('id', $generationId)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$generation) {
            return response()->json(['message' => 'Data tidak ditemukan.'], 404);
        }

        return response()->json([
            'generation_id' => $generation->id,
            'status'        => $generation->status,
            'image_url'     => $generation->image_url,
            'error_message' => $generation->error_message,
            'created_at'    => $generation->created_at,
            'updated_at'    => $generation->updated_at,
        ]);
    }
}
