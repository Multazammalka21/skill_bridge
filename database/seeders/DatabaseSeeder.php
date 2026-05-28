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
        // ─── Create admin user ─────────────────────────────────────
        User::create([
            'name' => 'Admin Pinteria',
            'email' => 'admin@pinteria.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        // ─── Create parent user ───────────────────────────────────
        $parent = User::create([
            'name' => 'Orang Tua Demo',
            'email' => 'parent@skillbridge.test',
            'password' => bcrypt('password'),
            'role' => 'parent',
        ]);

        // ─── Create children ─────────────────────────────────────
        // Aira: lahir 2019 (umur 7 tahun di 2026), tunanetra (Audio World)
        $childAudio = Child::create([
            'user_id' => $parent->id,
            'nama_panggilan' => 'Aira',
            'tanggal_lahir' => '2019-03-15',
            'jenis_disabilitas' => 'tunanetra',
        ]);

        // Bima: lahir 2017 (umur 9 tahun di 2026), tunarungu (Visual World)
        $childVisual = Child::create([
            'user_id' => $parent->id,
            'nama_panggilan' => 'Bima',
            'tanggal_lahir' => '2017-07-22',
            'jenis_disabilitas' => 'tunarungu',
        ]);

        // ─── Audio World Lessons ─────────────────────────────────
        $audioLessons = [];
        $audioTopics = [
            // Age 5-7
            ['Mengenal Angka 1-5', 'Belajar angka satu sampai lima melalui cerita.', '5-7'],
            ['Mengenal Warna', 'Belajar warna melalui deskripsi suara.', '5-7'],
            ['Hewan Peliharaan', 'Mengenal suara dan nama hewan peliharaan.', '5-7'],
            // Age 8-10
            ['Buah-buahan', 'Belajar nama buah melalui deskripsi.', '8-10'],
            ['Anggota Tubuh', 'Mengenal anggota tubuh melalui narasi.', '8-10'],
        ];

        foreach ($audioTopics as $i => $topic) {
            $audioLessons[] = Lesson::create([
                'judul' => $topic[0],
                'deskripsi' => $topic[1],
                'tipe_dunia' => 'audio',
                'kategori_usia' => $topic[2],
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
            // Age 5-7
            ['Bentuk Geometri', 'Belajar bentuk dasar: lingkaran, segitiga, kotak.', '5-7'],
            ['Warna Pelangi', 'Mengenal tujuh warna pelangi.', '5-7'],
            ['Huruf A-E', 'Belajar huruf awal alfabet dengan gambar.', '5-7'],
            // Age 8-10
            ['Angka 1-10', 'Mengenal angka dengan ilustrasi visual.', '8-10'],
            ['Ekspresi Wajah', 'Mengenal emosi melalui ekspresi wajah.', '8-10'],
        ];

        foreach ($visualTopics as $i => $topic) {
            $visualLessons[] = Lesson::create([
                'judul' => $topic[0],
                'deskripsi' => $topic[1],
                'tipe_dunia' => 'visual',
                'kategori_usia' => $topic[2],
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
                'pilihan' => ['Angka', 'Warna', 'Hewan', 'Buah', 'Tubuh'],
                'tipe' => 'voice',
            ]);
        }

        foreach ($visualLessons as $lesson) {
            QuizQuestion::create([
                'lesson_id' => $lesson->id,
                'pertanyaan' => 'Pilih gambar yang sesuai dengan ' . $lesson->judul,
                'jawaban_benar' => 'Pilihan A',
                'pilihan' => [
                    'Pilihan A',
                    'Pilihan B',
                    'Pilihan C',
                    'Pilihan D',
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
                        'jawaban_anak' => 'Angka',
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
                        'jawaban_anak' => 'Pilihan A',
                        'benar' => rand(0, 1),
                        'skor' => rand(50, 100),
                        'percobaan' => rand(1, 2),
                    ]);
                }
            }
        }
    }
}
