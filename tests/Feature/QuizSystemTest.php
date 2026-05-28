<?php

namespace Tests\Feature;

use App\Models\Child;
use App\Models\Lesson;
use App\Models\QuizQuestion;
use App\Models\QuizResult;
use App\Models\User;
use App\Models\Badge;
use App\Services\GamificationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuizSystemTest extends TestCase
{
    use RefreshDatabase;

    public function test_gamification_star_calculation()
    {
        $this->assertEquals(3, GamificationService::calculateStars(100));
        $this->assertEquals(2, GamificationService::calculateStars(80));
        $this->assertEquals(1, GamificationService::calculateStars(50));
        $this->assertEquals(0, GamificationService::calculateStars(20));
    }

    public function test_parent_can_submit_quiz_result_and_award_badge()
    {
        // 1. Create Parent and Child
        $parent = User::create([
            'name' => 'Bapak Test',
            'email' => 'parent@test.com',
            'password' => bcrypt('password'),
            'role' => 'parent',
        ]);

        $child = Child::create([
            'user_id' => $parent->id,
            'nama_panggilan' => 'Aira',
            'tanggal_lahir' => '2019-03-15',
            'jenis_disabilitas' => 'tunanetra',
        ]);

        // 2. Create Lesson and Question
        $lesson = Lesson::create([
            'judul' => 'Test Lesson',
            'deskripsi' => 'Test Description',
            'tipe_dunia' => 'audio',
            'kategori_usia' => '5-7',
            'urutan' => 1,
            'aktif' => true,
        ]);

        $question = QuizQuestion::create([
            'lesson_id' => $lesson->id,
            'pertanyaan' => 'Siapa nama kelinci?',
            'jawaban_benar' => 'Aira',
            'pilihan' => ['Aira', 'Bima'],
            'tipe' => 'voice',
        ]);

        // 3. Create Badge for quiz completion
        $badge = Badge::create([
            'nama' => 'Pionir Belajar',
            'deskripsi' => 'Selesaikan kuis pertamamu!',
            'ikon' => '🚀',
            'syarat_tipe' => 'quiz_count',
            'syarat_nilai' => 1,
        ]);

        // 4. Act: Submit quiz result under parent's session
        $this->actingAs($parent);

        $response = $this->postJson(route('play.quiz.submit'), [
            'child_id' => $child->id,
            'lesson_id' => $lesson->id,
            'quiz_question_id' => $question->id,
            'jawaban_anak' => 'Aira',
            'benar' => 1,
            'skor' => 100,
            'percobaan' => 1,
            'waktu_detik' => 12,
        ]);

        // 5. Assert: Check response code, database entry, stars count, and badge awarded!
        $response->assertStatus(201);
        $response->assertJsonPath('bintang', 3);
        $response->assertJsonCount(1, 'new_badges');
        $response->assertJsonPath('new_badges.0.nama', 'Pionir Belajar');

        $this->assertDatabaseHas('quiz_results', [
            'child_id' => $child->id,
            'skor' => 100,
            'bintang' => 3,
            'benar' => 1,
        ]);

        $this->assertTrue($child->badges()->where('badge_id', $badge->id)->exists());
    }
}
