@props(['teks', 'efekSuara' => null])

<div
    x-data="audioNarrator('{{ $teks }}', @json($efekSuara))"
    class="audio-narrator"
    aria-live="polite"
>
    <style>
        .audio-narrator .big-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 200px;
            padding: 1rem 2rem;
            margin: .5rem;
            font-size: 1.125rem;
            font-weight: 600;
            color: #fff;
            background: linear-gradient(135deg, hsl(210, 70%, 55%), hsl(210, 70%, 40%));
            border: none;
            border-radius: .75rem;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(0,0,0,.15);
            transition: transform .1s ease, box-shadow .2s ease;
        }
        .audio-narrator .big-button:hover,
        .audio-narrator .big-button:focus {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0,0,0,.2);
        }
    </style>

    <button
        class="big-button"
        @click="playNarration()"
        aria-label="Dengarkan kembali narasi {{ Str::limit($teks, 30) }}."
    >▶ Dengarkan Lagi</button>

    <button
        class="big-button"
        @click="$dispatch('next-lesson')"
        aria-label="Lanjut ke pelajaran berikutnya."
    >Lanjut →</button>
</div>

<script>
    function audioNarrator(text, efekSuara) {
        return {
            text,
            efekSuara,
            async playNarration() {
                const speak = async (msg) => {
                    return new Promise((resolve) => {
                        const utter = new SpeechSynthesisUtterance(msg);
                        utter.lang = 'id-ID';
                        utter.rate = 0.85;
                        utter.pitch = 1.2;
                        utter.onend = resolve;
                        speechSynthesis.speak(utter);
                    });
                };

                if (this.efekSuara) {
                    const audio = new Audio(this.efekSuara);
                    await new Promise(r => {
                        audio.addEventListener('ended', r);
                        audio.play();
                    });
                }
                await speak(this.text);
            },
            init() {
                this.playNarration();
            }
        };
    }
</script>
</div>
