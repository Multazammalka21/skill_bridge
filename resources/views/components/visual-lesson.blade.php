@props(['judul', 'gambar', 'animasiLottie' => null, 'teksKeterangan'])

<div x-data="visualLesson()" class="visual-lesson" aria-live="polite">
    <style>
        .visual-lesson {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            gap: 1rem;
            padding: 1rem;
        }
        .visual-lesson .title {
            font-size: 2rem;
            font-weight: 800;
            text-align: center;
        }
        .visual-lesson .media {
            width: 100%;
            max-height: 60vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .visual-lesson .placeholder {
            width: 100%;
            height: 60vh;
            border: 2px dashed #aaa;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: #666;
            text-align: center;
        }
        .visual-lesson .caption {
            font-size: 1.75rem;
            font-weight: 600;
            color: #222;
            text-align: center;
            max-width: 90%;
        }
    </style>

    <h2 class="title" aria-label="Judul pelajaran {{ $judul }}">{{ $judul }}</h2>

    <div class="media" aria-label="Media pelajaran">
        @if($animasiLottie)
            <div class="placeholder">[ANIMASI: {{ $animasiLottie }}]</div>
        @else
            <img src="{{ $gambar }}" alt="Gambar pelajaran" style="max-width:100%;max-height:100%;object-fit:contain;" />
        @endif
    </div>

    <p class="caption" aria-label="Deskripsi pelajaran">{{ $teksKeterangan }}</p>

    <script>
        function visualLesson() {
            return {
                // No runtime logic needed for placeholder
            };
        }
    </script>
</div>
