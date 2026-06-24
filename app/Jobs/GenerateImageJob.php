<?php

namespace App\Jobs;

use App\Models\ImageGeneration;
use App\Services\AIService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

/**
 * GenerateImageJob
 *
 * Menjalankan proses SDXL image generation via Replicate di background.
 * Job ini dimasukkan ke antrian database dan dikerjakan oleh queue worker.
 *
 * Alur:
 *  1. Controller membuat record ImageGeneration (status: pending) lalu dispatch job ini
 *  2. Job berjalan di background:
 *     a. Panggil Replicate API → dapat prediction_id (status: processing)
 *     b. Poll hingga selesai (max 10x, interval 5 detik)
 *     c. Simpan image_url → status: succeeded
 *     d. Jika gagal → status: failed
 */
class GenerateImageJob implements ShouldQueue
{
    use Queueable, InteractsWithQueue, SerializesModels;

    /**
     * Jumlah percobaan ulang jika job gagal.
     */
    public int $tries = 2;

    /**
     * Timeout maksimal job dalam detik (Replicate bisa memakan 60-90 detik).
     */
    public int $timeout = 120;

    /**
     * Waktu jeda sebelum retry (detik).
     */
    public int $backoff = 10;

    public function __construct(
        private readonly ImageGeneration $imageGeneration
    ) {}

    public function handle(AIService $ai): void
    {
        $record = $this->imageGeneration;

        try {
            // ── Langkah 1: Mulai prediksi di Replicate via Facade ─────────────
            $record->update(['status' => ImageGeneration::STATUS_PROCESSING]);

            $asyncResult = $ai->startImagePrediction(
                $record->prompt,
                $record->negative_prompt ?? 'violence, adult content, scary'
            );

            if (!$asyncResult['success'] || !$asyncResult['prediction_id']) {
                $record->update([
                    'status'        => ImageGeneration::STATUS_FAILED,
                    'error_message' => 'Gagal membuat prediksi di Replicate.',
                ]);
                return;
            }

            $predictionId = $asyncResult['prediction_id'];
            $record->update(['prediction_id' => $predictionId]);

            // ── Langkah 2: Poll status hingga selesai ─────────────────────────
            $maxAttempts = 12;   // 12 × 6 detik = 72 detik maks
            $intervalSec = 6;

            for ($attempt = 0; $attempt < $maxAttempts; $attempt++) {
                sleep($intervalSec);

                $statusResult = $ai->checkImageStatus($predictionId);

                if (!$statusResult['success']) {
                    continue; // coba lagi di iterasi berikutnya
                }

                $replicateStatus = $statusResult['status'];

                if ($replicateStatus === 'succeeded') {
                    $record->update([
                        'status'    => ImageGeneration::STATUS_SUCCEEDED,
                        'image_url' => $statusResult['image_url'],
                    ]);

                    Log::info('GenerateImageJob: berhasil', [
                        'id'        => $record->id,
                        'image_url' => $statusResult['image_url'],
                    ]);
                    return;
                }

                if (in_array($replicateStatus, ['failed', 'canceled'])) {
                    $record->update([
                        'status'        => ImageGeneration::STATUS_FAILED,
                        'error_message' => "Replicate status: {$replicateStatus}",
                    ]);
                    return;
                }

                // Status masih starting/processing → lanjut polling
            }

            // Jika loop habis tanpa hasil
            $record->update([
                'status'        => ImageGeneration::STATUS_FAILED,
                'error_message' => 'Timeout: Replicate tidak merespons dalam batas waktu.',
            ]);
        } catch (\Throwable $e) {
            Log::error('GenerateImageJob exception', [
                'id'      => $record->id,
                'message' => $e->getMessage(),
            ]);

            $record->update([
                'status'        => ImageGeneration::STATUS_FAILED,
                'error_message' => $e->getMessage(),
            ]);

            // Lempar ulang agar Laravel queue menangani retry
            throw $e;
        }
    }

    /**
     * Dipanggil jika job gagal setelah semua percobaan habis.
     */
    public function failed(\Throwable $exception): void
    {
        $this->imageGeneration->update([
            'status'        => ImageGeneration::STATUS_FAILED,
            'error_message' => 'Job gagal setelah ' . $this->tries . ' percobaan: ' . $exception->getMessage(),
        ]);
    }
}
