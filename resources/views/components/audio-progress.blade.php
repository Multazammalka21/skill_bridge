{{-- Audio World: Progress Component --}}
{{-- All elements have aria-label --}}
@props(['current', 'total'])

<div
    x-data="audioProgress({{ $current }}, {{ $total }})"
    class="audio-progress"
    role="region"
    aria-label="Progress belajar audio: {{ $current }} dari {{ $total }} cerita selesai"
    aria-live="polite"
>
    <style>
        .audio-progress {
            padding: 1rem;
            text-align: center;
        }
        .audio-progress .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0,0,0,0);
            white-space: nowrap;
            border: 0;
        }
    </style>

    <p class="sr-only" aria-label="Informasi progress belajar" x-text="progressText"></p>

    <script>
        function audioProgress(current, total) {
            return {
                progressText: `Kamu sudah selesaikan ${current} dari ${total} cerita hari ini. Bagus sekali!`,
                async init() {
                    const msg = this.progressText;
                    const utter = new SpeechSynthesisUtterance(msg);
                    utter.lang = 'id-ID';
                    utter.rate = 0.85;
                    utter.pitch = 1.2;
                    speechSynthesis.speak(utter);
                }
            };
        }
    </script>
</div>
