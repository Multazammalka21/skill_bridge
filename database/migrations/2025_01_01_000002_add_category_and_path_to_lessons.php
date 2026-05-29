<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('lessons', function (Blueprint $table) {
            // Kategori pembelajaran (FK ke categories)
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete()->after('id');

            // Prerequisite lesson untuk learning path (self-referential)
            $table->unsignedBigInteger('prerequisite_lesson_id')->nullable()->after('urutan');
            $table->foreign('prerequisite_lesson_id')->references('id')->on('lessons')->nullOnDelete();

            // Tipe konten utama lesson
            $table->string('konten_tipe')->nullable()->after('teks_keterangan');
            // Values: audio_story | gambar_interaktif | animasi_lottie | teks | campuran

            // Audio storytelling URL
            $table->string('audio_story_url')->nullable()->after('konten_tipe');
        });
    }

    public function down(): void
    {
        Schema::table('lessons', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropForeign(['prerequisite_lesson_id']);
            $table->dropColumn(['category_id', 'prerequisite_lesson_id', 'konten_tipe', 'audio_story_url']);
        });
    }
};
