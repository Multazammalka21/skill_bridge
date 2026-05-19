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
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->enum('tipe_dunia', ['audio', 'visual']);
            $table->integer('urutan')->default(0);
            $table->string('gambar')->nullable();
            $table->string('animasi_lottie')->nullable();
            $table->string('efek_suara')->nullable();
            $table->text('teks_narasi')->nullable();
            $table->text('teks_keterangan')->nullable();
            $table->integer('durasi_menit')->default(5);
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lessons');
    }
};
