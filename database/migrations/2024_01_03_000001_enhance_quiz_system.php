<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Enhance quiz_questions table
        Schema::table('quiz_questions', function (Blueprint $table) {
            $table->string('gambar')->nullable()->after('pilihan');
            $table->string('audio_url')->nullable()->after('gambar');
            $table->string('animasi_url')->nullable()->after('audio_url');
            $table->string('efek_suara_url')->nullable()->after('animasi_url');
            $table->integer('poin')->default(10)->after('tipe');
        });

        // 2. Enhance quiz_results table
        Schema::table('quiz_results', function (Blueprint $table) {
            $table->integer('bintang')->default(0)->after('skor');
            $table->integer('waktu_detik')->default(0)->after('bintang');
        });

        // 3. Create badges table
        Schema::create('badges', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('deskripsi');
            $table->string('ikon'); // Emoji or image URL
            $table->string('syarat_tipe'); // 'quiz_count', 'perfect_score', 'streak'
            $table->integer('syarat_nilai');
            $table->timestamps();
        });

        // 4. Create child_badges table (pivot)
        Schema::create('child_badges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('child_id')->constrained('children')->onDelete('cascade');
            $table->foreignId('badge_id')->constrained('badges')->onDelete('cascade');
            $table->timestamp('earned_at')->useCurrent();
            $table->timestamps();

            $table->unique(['child_id', 'badge_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('child_badges');
        Schema::dropIfExists('badges');

        Schema::table('quiz_results', function (Blueprint $table) {
            $table->dropColumn(['bintang', 'waktu_detik']);
        });

        Schema::table('quiz_questions', function (Blueprint $table) {
            $table->dropColumn(['gambar', 'audio_url', 'animasi_url', 'efek_suara_url', 'poin']);
        });
    }
};
