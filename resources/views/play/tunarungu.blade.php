@extends('layouts.visual-world')

@section('content')
    <div x-data="visualWorldApp()">
        
        <!-- Komponen Progress Bar -->
        <x-visual-progress :completed="1" :total="5" />

        <!-- Contoh penggunaan komponen Visual Lesson -->
        <template x-if="currentState === 'lesson'">
            <div style="margin-top: 2rem;">
                <x-visual-lesson 
                    judul="Mengenal Hewan Peliharaan" 
                    gambar="/images/placeholder.png" 
                    teksKeterangan="Ini adalah anjing. Anjing sangat setia." 
                    @next-lesson="currentState = 'quiz'"
                />
            </div>
        </template>
        
        <!-- Contoh penggunaan komponen Image Quiz -->
        <template x-if="currentState === 'quiz'">
            <div style="margin-top: 2rem;">
                @php
                    $pilihan = [
                        ['gambar' => '/images/placeholder.png', 'label' => 'Kucing', 'benar' => false],
                        ['gambar' => '/images/placeholder.png', 'label' => 'Anjing', 'benar' => true],
                        ['gambar' => '/images/placeholder.png', 'label' => 'Burung', 'benar' => false],
                        ['gambar' => '/images/placeholder.png', 'label' => 'Ikan', 'benar' => false],
                    ];
                @endphp
                <x-image-quiz 
                    pertanyaan="Manakah gambar Anjing?" 
                    :pilihan="$pilihan" 
                    @quiz-selesai="currentState = 'done'"
                />
            </div>
        </template>

        <template x-if="currentState === 'done'">
            <div style="text-align: center; margin-top: 4rem;">
                <h1 style="color: var(--visual-success); font-size: 3rem;">Selesai! 🎉</h1>
                <button 
                    @click="window.location.href='/dashboard'"
                    style="background: var(--visual-primary); color: white; border: none; padding: 1rem 2rem; border-radius: 12px; font-size: 1.25rem; font-weight: bold; cursor: pointer; margin-top: 2rem; min-width: 80px; min-height: 80px;"
                >
                    Kembali
                </button>
            </div>
        </template>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('visualWorldApp', () => ({
                currentState: 'lesson', // lesson, quiz, done
            }))
        })
    </script>
@endsection
