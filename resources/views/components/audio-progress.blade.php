<!-- resources/views/components/audio-progress.blade.php -->
@props(['current', 'total'])

<div
    x-data="audioProgress({{ $current }}, {{ $total }})"
    class="audio-progress"
    aria-live="polite"
>
    <script>
        function audioProgress(current, total) {
            return {
                async init() {
                    const msg = `Kamu sudah selesaikan ${current} dari ${total} cerita hari ini. Bagus sekali!`;
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
