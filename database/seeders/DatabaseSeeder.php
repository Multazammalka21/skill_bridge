<?php

namespace Database\Seeders;

use App\Models\Child;
use App\Models\Lesson;
use App\Models\LessonCompletion;
use App\Models\QuizQuestion;
use App\Models\QuizResult;
use App\Models\StudySession;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with demo data.
     */
    public function run(): void
    {
        // ─── Create parent user ───────────────────────────────────
        $parent = User::create([
            'name' => 'Orang Tua Demo',
            'email' => 'parent@skillbridge.test',
            'password' => bcrypt('password'),
            'role' => 'parent',
        ]);

        // ─── Create children ─────────────────────────────────────
        $childAudio = Child::create([
            'user_id' => $parent->id,
            'nama_panggilan' => 'Aira',
            'tanggal_lahir' => '2018-03-15',
            'jenis_disabilitas' => 'tunanetra',
        ]);

        $childVisual = Child::create([
            'user_id' => $parent->id,
            'nama_panggilan' => 'Bima',
            'tanggal_lahir' => '2019-07-22',
            'jenis_disabilitas' => 'tunarungu',
        ]);

        // ─── Audio World Lessons ─────────────────────────────────
        $audioLessons = [];
        $audioTopics = [
            ['Mengenal Angka 1-5', 'Belajar angka satu sampai lima melalui cerita.'],
            ['Mengenal Warna', 'Belajar warna melalui deskripsi suara.'],
            ['Hewan Peliharaan', 'Mengenal suara dan nama hewan peliharaan.'],
            ['Buah-buahan', 'Belajar nama buah melalui deskripsi.'],
            ['Anggota Tubuh', 'Mengenal anggota tubuh melalui narasi.'],
        ];

        foreach ($audioTopics as $i => $topic) {
            $audioLessons[] = Lesson::create([
                'judul' => $topic[0],
                'deskripsi' => $topic[1],
                'tipe_dunia' => 'audio',
                'urutan' => $i + 1,
                'teks_narasi' => $topic[1],
                'teks_keterangan' => $topic[1],
                'durasi_menit' => rand(3, 8),
                'aktif' => true,
            ]);
        }

        // ─── Visual World Lessons ────────────────────────────────
        $visualLessons = [];
        $visualTopics = [
            ['Bentuk Geometri', 'Belajar bentuk dasar: lingkaran, segitiga, kotak.'],
            ['Warna Pelangi', 'Mengenal tujuh warna pelangi.'],
            ['Huruf A-E', 'Belajar huruf awal alfabet dengan gambar.'],
            ['Angka 1-10', 'Mengenal angka dengan ilustrasi visual.'],
            ['Ekspresi Wajah', 'Mengenal emosi melalui ekspresi wajah.'],
        ];

        foreach ($visualTopics as $i => $topic) {
            $visualLessons[] = Lesson::create([
                'judul' => $topic[0],
                'deskripsi' => $topic[1],
                'tipe_dunia' => 'visual',
                'urutan' => $i + 1,
                'teks_keterangan' => $topic[1],
                'durasi_menit' => rand(3, 8),
                'aktif' => true,
            ]);
        }

        // ─── Quiz Questions ──────────────────────────────────────
        foreach ($audioLessons as $lesson) {
            QuizQuestion::create([
                'lesson_id' => $lesson->id,
                'pertanyaan' => 'Sebutkan apa yang kamu pelajari di pelajaran ' . $lesson->judul . '?',
                'jawaban_benar' => strtolower(explode(' ', $lesson->judul)[1] ?? 'jawaban'),
                'tipe' => 'voice',
            ]);
        }

        foreach ($visualLessons as $lesson) {
            QuizQuestion::create([
                'lesson_id' => $lesson->id,
                'pertanyaan' => 'Pilih gambar yang sesuai dengan ' . $lesson->judul,
                'jawaban_benar' => 'benar',
                'pilihan' => [
                    ['label' => 'Pilihan A', 'gambar' => '/images/placeholder.png', 'benar' => true],
                    ['label' => 'Pilihan B', 'gambar' => '/images/placeholder.png', 'benar' => false],
                    ['label' => 'Pilihan C', 'gambar' => '/images/placeholder.png', 'benar' => false],
                    ['label' => 'Pilihan D', 'gambar' => '/images/placeholder.png', 'benar' => false],
                ],
                'tipe' => 'image',
            ]);
        }

        // ─── Sample Progress Data (last 30 days) ────────────────
        $allAudioQuestions = QuizQuestion::whereHas('lesson', fn($q) => $q->where('tipe_dunia', 'audio'))->get();
        $allVisualQuestions = QuizQuestion::whereHas('lesson', fn($q) => $q->where('tipe_dunia', 'visual'))->get();

        for ($day = 29; $day >= 0; $day--) {
            $date = Carbon::now()->subDays($day);

            // Audio child (Aira) — random daily progress
            if (rand(0, 3) > 0) {
                $lesson = $audioLessons[array_rand($audioLessons)];
                LessonCompletion::firstOrCreate(
                    ['child_id' => $childAudio->id, 'lesson_id' => $lesson->id],
                    ['completed_at' => $date]
                );

                StudySession::create([
                    'child_id' => $childAudio->id,
                    'lesson_id' => $lesson->id,
                    'started_at' => $date->copy()->setHour(rand(8, 16)),
                    'ended_at' => $date->copy()->setHour(rand(8, 16))->addMinutes(rand(5, 25)),
                    'durasi_detik' => rand(300, 1500),
                ]);

                if ($allAudioQuestions->isNotEmpty()) {
                    $q = $allAudioQuestions->random();
                    QuizResult::create([
                        'child_id' => $childAudio->id,
                        'lesson_id' => $q->lesson_id,
                        'quiz_question_id' => $q->id,
                        'jawaban_anak' => 'jawaban',
                        'benar' => rand(0, 1),
                        'skor' => rand(40, 100),
                        'percobaan' => rand(1, 3),
                    ]);
                }
            }

            // Visual child (Bima)
            if (rand(0, 3) > 0) {
                $lesson = $visualLessons[array_rand($visualLessons)];
                LessonCompletion::firstOrCreate(
                    ['child_id' => $childVisual->id, 'lesson_id' => $lesson->id],
                    ['completed_at' => $date]
                );

                StudySession::create([
                    'child_id' => $childVisual->id,
                    'lesson_id' => $lesson->id,
                    'started_at' => $date->copy()->setHour(rand(8, 16)),
                    'ended_at' => $date->copy()->setHour(rand(8, 16))->addMinutes(rand(5, 20)),
                    'durasi_detik' => rand(300, 1200),
                ]);

                if ($allVisualQuestions->isNotEmpty()) {
                    $q = $allVisualQuestions->random();
                    QuizResult::create([
                        'child_id' => $childVisual->id,
                        'lesson_id' => $q->lesson_id,
                        'quiz_question_id' => $q->id,
                        'jawaban_anak' => 'benar',
                        'benar' => rand(0, 1),
                        'skor' => rand(50, 100),
                        'percobaan' => rand(1, 2),
                    ]);
                }
            }
        }
    }
}
