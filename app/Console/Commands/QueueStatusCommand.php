<?php

namespace App\Console\Commands;

use App\Models\ImageGeneration;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * php artisan ai:queue-status
 *
 * Menampilkan ringkasan status antrian gambar AI secara real-time.
 */
class QueueStatusCommand extends Command
{
    protected $signature = 'ai:queue-status
                            {--watch : Refresh otomatis setiap 3 detik}';

    protected $description = 'Tampilkan status antrian generate gambar AI (jobs & image_generations)';

    public function handle(): void
    {
        do {
            // Bersihkan layar jika mode watch
            if ($this->option('watch')) {
                $this->output->write("\033[2J\033[H"); // clear terminal
            }

            $this->info('╔══════════════════════════════════════════════════════╗');
            $this->info('║        Pinteria AI — Status Antrian Gambar           ║');
            $this->info('╚══════════════════════════════════════════════════════╝');
            $this->newLine();

            // ── 1. Tabel jobs (antrian Laravel) ─────────────────────────────
            $pendingJobs = DB::table('jobs')
                ->where('queue', 'images')
                ->count();

            $allJobs = DB::table('jobs')->count();

            $this->line('📦 <comment>Tabel: jobs</comment> (antrian Laravel database)');
            $this->table(
                ['Queue', 'Jumlah Job Menunggu'],
                [
                    ['images', $pendingJobs],
                    ['(semua queue)', $allJobs],
                ]
            );

            // ── 2. Tabel failed_jobs ─────────────────────────────────────────
            $failedCount = DB::table('failed_jobs')
                ->where('queue', 'images')
                ->count();

            $this->line('💀 <comment>Tabel: failed_jobs</comment> (job yang gagal setelah semua retry)');
            $this->table(
                ['Queue', 'Jumlah Job Gagal'],
                [['images', $failedCount]]
            );

            // ── 3. Tabel image_generations ───────────────────────────────────
            $stats = ImageGeneration::selectRaw('status, COUNT(*) as total')
                ->groupBy('status')
                ->pluck('total', 'status')
                ->toArray();

            $rows = [
                ['⏳ pending',    $stats['pending']    ?? 0, 'Job belum diambil worker'],
                ['⚙️  processing', $stats['processing'] ?? 0, 'Sedang polling Replicate'],
                ['✅ succeeded',  $stats['succeeded']  ?? 0, 'Gambar siap'],
                ['❌ failed',     $stats['failed']     ?? 0, 'Gagal setelah retry'],
            ];

            $this->line('🖼️  <comment>Tabel: image_generations</comment> (tracking per permintaan)');
            $this->table(['Status', 'Jumlah', 'Keterangan'], $rows);

            // ── 4. Recent entries ────────────────────────────────────────────
            $recent = ImageGeneration::latest()->limit(5)->get(['id', 'status', 'prompt', 'updated_at']);

            if ($recent->isNotEmpty()) {
                $this->line('🕐 <comment>5 Permintaan Terbaru:</comment>');
                $this->table(
                    ['ID', 'Status', 'Prompt', 'Update'],
                    $recent->map(fn ($r) => [
                        $r->id,
                        $r->status,
                        mb_strimwidth($r->prompt, 0, 40, '...'),
                        $r->updated_at,
                    ])->toArray()
                );
            }

            // ── 5. Petunjuk jalankan worker ──────────────────────────────────
            $this->newLine();
            $this->line('💡 <info>Jalankan worker di terminal terpisah:</info>');
            $this->line('   <comment>php artisan queue:work --queue=images,default --timeout=120 --tries=2</comment>');
            $this->line('   <comment>php artisan queue:failed</comment>   ← lihat detail job gagal');
            $this->line('   <comment>php artisan queue:retry all</comment> ← ulangi semua job gagal');

            if ($this->option('watch')) {
                $this->newLine();
                $this->line('<fg=gray>Refresh setiap 3 detik... Tekan Ctrl+C untuk berhenti.</>');
                sleep(3);
            }
        } while ($this->option('watch'));
    }
}
