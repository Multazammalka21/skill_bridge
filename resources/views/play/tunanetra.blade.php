@extends('layouts.audio-world')

@section('content')
    <div x-data="audioWorldApp()">
        <!-- Contoh penggunaan komponen -->
        <template x-if="currentState === 'narrator'">
            <x-audio-narrator 
                teks="Halo Aira, selamat datang di dunia audio. Hari ini kita akan belajar mengenal hewan peliharaan." 
                efekSuara="/audio/efek/magic-chime.mp3" 
                lessonId="1" 
                @next-lesson="currentState = 'quiz'"
            />
        </template>
        
        <template x-if="currentState === 'quiz'">
            <x-voice-quiz 
                pertanyaan="Hewan apa yang sering mengeong?" 
                jawabanBenar="Kucing" 
                lessonId="1" 
                @next-lesson="currentState = 'progress'"
            />
        </template>
        
        <template x-if="currentState === 'progress'">
            <x-audio-progress current="1" total="5" />
        </template>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('audioWorldApp', () => ({
                currentState: 'narrator', // narrator, quiz, progress
            }))
        })
    </script>
@endsection
