<?php

namespace Database\Seeders;

use App\Models\Child;
use App\Models\Lesson;
use App\Models\LessonCompletion;
use App\Models\QuizQuestion;
use App\Models\QuizResult;
use App\Models\StudySession;
use App\Models\User;
use App\Models\Badge;
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

        // ─── Seed Default Badges ─────────────────────────────────
        $badges = [
            [
                'nama' => 'Petualang Pertama',
                'deskripsi' => 'Menyelesaikan kuis pertamamu!',
                'ikon' => '🚀',
                'syarat_tipe' => 'quiz_count',
                'syarat_nilai' => 1,
            ],
            [
                'nama' => 'Pencari Nada',
                'deskripsi' => 'Menyelesaikan 5 kuis pelajaran',
                'ikon' => '🎵',
                'syarat_tipe' => 'quiz_count',
                'syarat_nilai' => 5,
            ],
            [
                'nama' => 'Bintang Sempurna',
                'deskripsi' => 'Meraih skor 100 sempurna pada kuis!',
                'ikon' => '🌟',
                'syarat_tipe' => 'perfect_score',
                'syarat_nilai' => 1,
            ],
            [
                'nama' => 'Konsisten Belajar',
                'deskripsi' => 'Belajar selama 3 hari beruntun!',
                'ikon' => '🔥',
                'syarat_tipe' => 'streak',
                'syarat_nilai' => 3,
            ],
        ];

        foreach ($badges as $badge) {
            Badge::create($badge);
        }

        // ─── Audio World Lessons ─────────────────────────────────
        $audioLessons = [];
        $audioTopics = [
            // Age 5-7
            ['Mengenal Angka 1-5', 'Belajar angka satu sampai lima melalui cerita petualangan kelinci cerdik di hutan.', '5-7'],
            ['Mengenal Warna Suara', 'Mengenali warna primer merah, kuning, biru melalui suara kicauan burung.', '5-7'],
            ['Suara Hewan Hutan', 'Mengenal suara gajah, monyet, dan harimau di tengah rimba.', '5-7'],
            // Age 8-10
            ['Misteri Buah Manis', 'Mendengar teka-teki buah apel, mangga, dan melon yang menyehatkan.', '8-10'],
            ['Anggota Tubuh Kita', 'Cerita tentang fungsi mata, telinga, tangan, dan kaki yang ajaib.', '8-10'],
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
            ['Bentuk Geometri', 'Menemukan bentuk lingkaran matahari, kotak rumah, dan segitiga atap.', '5-7'],
            ['Pelangi Warna-Warni', 'Melihat keindahan pelangi dan mencocokkan setiap warnanya.', '5-7'],
            ['Petualangan Huruf A-E', 'Membaca dan mencocokkan huruf awal buah apel, bebek, ceri, dan domba.', '5-7'],
            // Age 8-10
            ['Berhitung 1-10', 'Menghitung jumlah apel dan wortel di kebun pak tani secara visual.', '8-10'],
            ['Ekspresi Emosi', 'Belajar mengenali ekspresi senang, sedih, marah, dan terkejut.', '8-10'],
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
                'pertanyaan' => 'Hewan apa yang sering mengeluarkan suara petualangan di pelajaran ' . $lesson->judul . '?',
                'jawaban_benar' => 'Gajah',
                'pilihan' => ['Gajah', 'Kucing', 'Singa', 'Burung'],
                'tipe' => 'voice',
                'poin' => 15,
            ]);
        }

        $visualQuestionsData = [
            'Bentuk Geometri' => [
                'pertanyaan' => 'Lihat gambar bentuk-bentuk lucu ini. Bentuk apakah matahari yang tersenyum?',
                'jawaban_benar' => 'Lingkaran',
                'pilihan' => ['Lingkaran', 'Kotak', 'Segitiga', 'Bintang'],
                'gambar' => '/images/quiz/geometri.png'
            ],
            'Pelangi Warna-Warni' => [
                'pertanyaan' => 'Lihat pelangi indah di langit. Manakah warna yang paling atas?',
                'jawaban_benar' => 'Merah',
                'pilihan' => ['Merah', 'Hijau', 'Kuning', 'Biru'],
                'gambar' => '/images/quiz/pelangi.png'
            ],
            'Petualangan Huruf A-E' => [
                'pertanyaan' => 'Lihat huruf merah besar A di samping. Gambar buah apakah itu?',
                'jawaban_benar' => 'Apel',
                'pilihan' => ['Apel', 'Bebek', 'Ceri', 'Domba'],
                'gambar' => '/images/quiz/huruf_apel.png'
            ],
            'Berhitung 1-10' => [
                'pertanyaan' => 'Bantu Pak Tani menghitung buah apel di keranjang. Ada berapa jumlahnya?',
                'jawaban_benar' => '3',
                'pilihan' => ['3', '5', '7', '9'],
                'gambar' => '/images/quiz/berhitung_tiga.png'
            ],
            'Ekspresi Emosi' => [
                'pertanyaan' => 'Ada empat ekspresi wajah anak. Wajah yang manakah yang menggambarkan perasaan "Senang"?',
                'jawaban_benar' => 'Senang',
                'pilihan' => ['Senang', 'Sedih', 'Marah', 'Terkejut'],
                'gambar' => '/images/quiz/ekspresi_emosi.png'
            ]
        ];

        foreach ($visualLessons as $lesson) {
            $data = $visualQuestionsData[$lesson->judul] ?? [
                'pertanyaan' => 'Manakah gambar yang mewakili ' . $lesson->judul . '?',
                'jawaban_benar' => 'Pilihan A',
                'pilihan' => ['Pilihan A', 'Pilihan B', 'Pilihan C', 'Pilihan D'],
                'gambar' => null
            ];

            QuizQuestion::create([
                'lesson_id' => $lesson->id,
                'pertanyaan' => $data['pertanyaan'],
                'jawaban_benar' => $data['jawaban_benar'],
                'pilihan' => $data['pilihan'],
                'gambar' => $data['gambar'],
                'tipe' => 'image',
                'poin' => 20,
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
                    $skor = rand(40, 100);
                    $bintang = $skor >= 100 ? 3 : ($skor >= 70 ? 2 : ($skor >= 40 ? 1 : 0));
                    QuizResult::create([
                        'child_id' => $childAudio->id,
                        'lesson_id' => $q->lesson_id,
                        'quiz_question_id' => $q->id,
                        'jawaban_anak' => $skor >= 70 ? $q->jawaban_benar : 'Salah',
                        'benar' => $skor >= 70 ? 1 : 0,
                        'skor' => $skor,
                        'bintang' => $bintang,
                        'waktu_detik' => rand(10, 45),
                        'percobaan' => rand(1, 3),
                        'created_at' => $date,
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
                    $skor = rand(50, 100);
                    $bintang = $skor >= 100 ? 3 : ($skor >= 70 ? 2 : ($skor >= 40 ? 1 : 0));
                    QuizResult::create([
                        'child_id' => $childVisual->id,
                        'lesson_id' => $q->lesson_id,
                        'quiz_question_id' => $q->id,
                        'jawaban_anak' => $skor >= 70 ? $q->jawaban_benar : 'Salah',
                        'benar' => $skor >= 70 ? 1 : 0,
                        'skor' => $skor,
                        'bintang' => $bintang,
                        'waktu_detik' => rand(15, 60),
                        'percobaan' => rand(1, 2),
                        'created_at' => $date,
                    ]);
                }
            }
        }

        // Award default starter badges
        $childAudio->badges()->attach(Badge::where('syarat_tipe', 'quiz_count')->first()->id, ['earned_at' => Carbon::now()]);
        $childVisual->badges()->attach(Badge::where('syarat_tipe', 'quiz_count')->first()->id, ['earned_at' => Carbon::now()]);
    }
}
