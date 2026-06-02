<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Child;
use App\Models\Lesson;
use App\Models\LessonCompletion;
use App\Models\MediaAsset;
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
            'email' => 'admin@pinteria.test',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        // ─── Create parent user ───────────────────────────────────
        $parent = User::create([
            'name' => 'Orang Tua Demo',
            'email' => 'parent@pinteria.test',
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

        // ─── Seed Kategori Pembelajaran ──────────────────────────
        $kategoris = [
            ['nama' => 'Literasi',             'deskripsi' => 'Belajar membaca, menulis, dan mengenal huruf alfabet.',           'ikon' => '📖', 'warna' => '#3b82f6', 'urutan' => 1],
            ['nama' => 'Numerasi',             'deskripsi' => 'Belajar angka, berhitung, dan konsep matematika dasar.',          'ikon' => '🔢', 'warna' => '#10b981', 'urutan' => 2],
            ['nama' => 'Pengenalan Lingkungan','deskripsi' => 'Mengenal hewan, tumbuhan, dan alam sekitar.',                    'ikon' => '🌿', 'warna' => '#22c55e', 'urutan' => 3],
            ['nama' => 'Seni & Kreativitas',   'deskripsi' => 'Mengekspresikan diri melalui warna, bentuk, dan musik.',         'ikon' => '🎨', 'warna' => '#f59e0b', 'urutan' => 4],
            ['nama' => 'Sosial & Emosi',       'deskripsi' => 'Belajar mengenali emosi, berteman, dan berinteraksi sosial.',    'ikon' => '💛', 'warna' => '#ec4899', 'urutan' => 5],
            ['nama' => 'Kesehatan & Tubuh',    'deskripsi' => 'Mengenal anggota tubuh, kebersihan diri, dan pola hidup sehat.', 'ikon' => '🏃', 'warna' => '#8b5cf6', 'urutan' => 6],
        ];

        $categoryMap = [];
        foreach ($kategoris as $kat) {
            $categoryMap[$kat['nama']] = Category::create($kat);
        }

        // ─── Seed Sample Media Assets ────────────────────────────
        // (Demo entries — URL merujuk ke placeholder publik agar terlihat di library)
        $adminUser = User::where('role', 'admin')->first();
        $sampleMedia = [
            [
                'nama'         => 'Suara Gajah Hutan',
                'tipe'         => 'audio',
                'path'         => 'media/audio/suara_gajah.mp3',
                'url'          => '/storage/media/audio/suara_gajah.mp3',
                'ukuran_bytes' => 204800,
                'mime_type'    => 'audio/mpeg',
                'keterangan'   => 'Efek suara gajah untuk pelajaran Suara Hewan Hutan',
            ],
            [
                'nama'         => 'Suara Burung Kicau',
                'tipe'         => 'audio',
                'path'         => 'media/audio/suara_burung.mp3',
                'url'          => '/storage/media/audio/suara_burung.mp3',
                'ukuran_bytes' => 153600,
                'mime_type'    => 'audio/mpeg',
                'keterangan'   => 'Kicauan burung untuk pelajaran Mengenal Warna Suara',
            ],
            [
                'nama'         => 'Musik Tema Pinteria',
                'tipe'         => 'audio',
                'path'         => 'media/audio/tema_pinteria.mp3',
                'url'          => '/storage/media/audio/tema_pinteria.mp3',
                'ukuran_bytes' => 512000,
                'mime_type'    => 'audio/mpeg',
                'keterangan'   => 'Musik latar untuk layar pembuka Pinteria',
            ],
            [
                'nama'         => 'Gambar Bentuk Geometri',
                'tipe'         => 'image',
                'path'         => 'media/image/geometri.png',
                'url'          => '/storage/media/image/geometri.png',
                'ukuran_bytes' => 87040,
                'mime_type'    => 'image/png',
                'keterangan'   => 'Ilustrasi bentuk-bentuk geometri untuk kuis visual',
            ],
            [
                'nama'         => 'Gambar Pelangi Warna-Warni',
                'tipe'         => 'image',
                'path'         => 'media/image/pelangi.png',
                'url'          => '/storage/media/image/pelangi.png',
                'ukuran_bytes' => 112640,
                'mime_type'    => 'image/png',
                'keterangan'   => 'Ilustrasi pelangi untuk pelajaran Pelangi Warna-Warni',
            ],
            [
                'nama'         => 'Gambar Ekspresi Emosi Anak',
                'tipe'         => 'image',
                'path'         => 'media/image/ekspresi_emosi.png',
                'url'          => '/storage/media/image/ekspresi_emosi.png',
                'ukuran_bytes' => 98304,
                'mime_type'    => 'image/png',
                'keterangan'   => 'Empat ekspresi wajah anak untuk pelajaran Ekspresi Emosi',
            ],
            [
                'nama'         => 'GIF Bintang Berkedip',
                'tipe'         => 'gif',
                'path'         => 'media/gif/bintang.gif',
                'url'          => '/storage/media/gif/bintang.gif',
                'ukuran_bytes' => 245760,
                'mime_type'    => 'image/gif',
                'keterangan'   => 'Animasi bintang berkedip untuk efek reward kuis',
            ],
            [
                'nama'         => 'Animasi Konfeti Lottie',
                'tipe'         => 'lottie',
                'path'         => 'media/lottie/konfeti.json',
                'url'          => '/storage/media/lottie/konfeti.json',
                'ukuran_bytes' => 32768,
                'mime_type'    => 'application/json',
                'keterangan'   => 'Animasi konfeti Lottie JSON untuk layar kemenangan',
            ],
        ];

        foreach ($sampleMedia as $media) {
            MediaAsset::create(array_merge($media, ['uploaded_by' => $adminUser->id]));
        }

        // ─── Audio World Lessons ─────────────────────────────────
        $audioLessons = [];
        // [judul, deskripsi, usia, category_key, audio_story_url]
        $audioTopics = [
            ['Dongeng Singa dan Tikus', 'Dengarkan cerita tentang seekor Tikus kecil yang menyelamatkan Raja Hutan, si Singa yang perkasa.', '5-7', 'Literasi', null],
        ];

        foreach ($audioTopics as $i => $topic) {
            $audioLessons[] = Lesson::create([
                'category_id'     => $categoryMap[$topic[3]]->id,
                'judul'           => $topic[0],
                'deskripsi'       => $topic[1],
                'tipe_dunia'      => 'audio',
                'kategori_usia'   => $topic[2],
                'urutan'          => $i + 1,
                'teks_narasi'     => $topic[1],
                'teks_keterangan' => $topic[1],
                'audio_story_url' => $topic[4],
                'durasi_menit'    => rand(3, 8),
                'aktif'           => true,
            ]);
        }

        // ─── Visual World Lessons ────────────────────────────────
        $visualLessons = [];
        // [judul, deskripsi, usia, category_key, gambar]
        $visualTopics = [
            // Age 5-7
            ['Bentuk Geometri',       'Menemukan bentuk lingkaran matahari, kotak rumah, dan segitiga atap.',          '5-7',  'Seni & Kreativitas', '/images/quiz/geometri.png'],
            ['Pelangi Warna-Warni',   'Melihat keindahan pelangi dan mencocokkan setiap warnanya.',                    '5-7',  'Seni & Kreativitas', '/images/quiz/pelangi.png'],
            ['Petualangan Huruf A-E', 'Membaca dan mencocokkan huruf awal buah apel, bebek, ceri, dan domba.',         '5-7',  'Literasi',           '/images/quiz/huruf_apel.png'],
            // Age 8-10
            ['Berhitung 1-10',        'Menghitung jumlah apel dan wortel di kebun pak tani secara visual.',            '8-10', 'Numerasi',           '/images/quiz/berhitung_tiga.png'],
            ['Ekspresi Emosi',        'Belajar mengenali ekspresi senang, sedih, marah, dan terkejut.',                '8-10', 'Sosial & Emosi',     '/images/quiz/ekspresi_emosi.png'],
        ];

        foreach ($visualTopics as $i => $topic) {
            $visualLessons[] = Lesson::create([
                'category_id'     => $categoryMap[$topic[3]]->id,
                'judul'           => $topic[0],
                'deskripsi'       => $topic[1],
                'tipe_dunia'      => 'visual',
                'kategori_usia'   => $topic[2],
                'urutan'          => $i + 1,
                'teks_keterangan' => $topic[1],
                'gambar'          => $topic[4],
                'durasi_menit'    => rand(3, 8),
                'aktif'           => true,
            ]);
        }

        // ─── Quiz Questions ──────────────────────────────────────
        // Hanya "Dongeng Singa dan Tikus" yang memiliki soal kuis audio.
        // Semua soal berfokus pada pemahaman isi cerita yang didengarkan.
        foreach ($audioLessons as $lesson) {
            if ($lesson->judul === 'Dongeng Singa dan Tikus') {
                QuizQuestion::create([
                    'lesson_id'     => $lesson->id,
                    'pertanyaan'    => 'Hewan apakah yang tertidur?',
                    'jawaban_benar' => 'Singa',
                    'pilihan'       => ['Singa', 'Tikus', 'Kelinci', 'Burung'],
                    'tipe'          => 'voice',
                    'audio_url'     => '/audio/Singa dan Tikus.mp3',
                    'poin'          => 100,
                ]);
            }
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
