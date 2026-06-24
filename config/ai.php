<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Groq Cloud — Llama 3.3 & Whisper V3
    |--------------------------------------------------------------------------
    | Digunakan untuk: chat AI adaptif (Llama 3.3) dan transkripsi suara
    | (Whisper V3). Semua request dikirim ke OpenAI-compatible endpoint milik Groq.
    */
    'groq' => [
        'api_key'       => env('GROQ_API_KEY'),
        'base_url'      => env('GROQ_BASE_URL', 'https://api.groq.com/openai/v1'),
        'chat_model'    => env('GROQ_CHAT_MODEL', 'llama-3.3-70b-versatile'),
        'whisper_model' => env('GROQ_WHISPER_MODEL', 'whisper-large-v3'),
        'timeout'       => 30,
    ],

    /*
    |--------------------------------------------------------------------------
    | Replicate — Stable Diffusion XL
    |--------------------------------------------------------------------------
    | Digunakan untuk: generasi gambar ilustrasi edukatif untuk konten lesson
    | dan modul anak. Model SDXL menghasilkan gambar berkualitas tinggi.
    */
    'replicate' => [
        'api_token'  => env('REPLICATE_API_TOKEN'),
        'sdxl_model' => env('REPLICATE_SDXL_MODEL', 'stability-ai/sdxl:39ed52f2319f9b9dab9a097cebd84ea3b48a2d6c7db2f7f61c928f5c0ad3869'),
        'base_url'   => 'https://api.replicate.com/v1',
        'timeout'    => 60,
    ],

    /*
    |--------------------------------------------------------------------------
    | Google Cloud TTS — Text-to-Speech untuk Mode Tunanetra
    |--------------------------------------------------------------------------
    | Mengubah teks feedback AI menjadi file MP3 yang diputar langsung
    | di browser anak. Suara id-ID-Wavenet-A paling alami untuk Bahasa Indonesia.
    |
    | Endpoint: POST https://texttospeech.googleapis.com/v1/text:synthesize
    | Docs: https://cloud.google.com/text-to-speech/docs/reference/rest
    */
    'google_tts' => [
        'api_key'       => env('GOOGLE_TTS_API_KEY'),
        'endpoint'      => 'https://texttospeech.googleapis.com/v1/text:synthesize',
        'voice'         => env('GOOGLE_TTS_VOICE', 'id-ID-Wavenet-A'),
        'language_code' => env('GOOGLE_TTS_LANGUAGE_CODE', 'id-ID'),
        'speaking_rate' => (float) env('GOOGLE_TTS_SPEAKING_RATE', 0.9),
        'timeout'       => 15,
    ],

    /*
    |--------------------------------------------------------------------------
    | Pengaturan AI Global
    |--------------------------------------------------------------------------
    */
    'system_prompt' => 'Kamu adalah Pinta, asisten AI edukatif yang ramah dan sabar untuk Pinteria, platform belajar anak dengan disabilitas. Berikan penjelasan yang sederhana, positif, dan memotivasi. Gunakan Bahasa Indonesia yang baik dan mudah dipahami.',

];
