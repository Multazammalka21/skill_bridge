{{-- Audio World: Narrator Component --}}
{{-- All buttons ≥ 80×80px, all elements have aria-label --}}
@props(['teks', 'efekSuara' => null, 'lessonId' => null])

<div
    x-data="audioNarrator({{ \Illuminate\Support\Js::from($teks) }}, {{ \Illuminate\Support\Js::from($efekSuara) }}, {{ \Illuminate\Support\Js::from($lessonId) }})"
    class="audio-narrator"
    role="region"
    aria-label="Narrator audio untuk pelajaran"
    aria-live="polite"
>
    <style>
        .audio-narrator {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1rem;
            padding: 1.5rem;
        }
        .audio-narrator .big-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            /* ENFORCED: minimum 80×80px for nav buttons */
            min-width: 80px;
            min-height: 80px;
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
            transition: transform .15s ease, box-shadow .2s ease, background .2s ease;
            text-align: center;
            line-height: 1.3;
        }
        .audio-narrator .big-button:hover,
        .audio-narrator .big-button:focus-visible {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0,0,0,.2);
            outline: 3px solid hsl(210, 70%, 70%);
            outline-offset: 2px;
        }
        .audio-narrator .big-button:active {
            transform: translateY(0);
        }
        .audio-narrator .status-text {
            font-size: 1rem;
            color: #444;
            text-align: center;
            min-height: 1.5rem;
        }
    </style>

    <p class="status-text" x-text="statusMessage" aria-live="assertive" aria-label="Status narasi saat ini"></p>

    <div style="display:flex; flex-wrap:wrap; justify-content:center; gap:0.75rem;">
        <button
            class="big-button"
            @click="playNarration()"
            aria-label="Dengarkan kembali narasi: {{ Str::limit($teks, 40) }}"
            id="btn-play-narration"
        >▶ Dengarkan Lagi</button>

        <button
            class="big-button"
            @click="$dispatch('next-lesson')"
            aria-label="Lanjut ke pelajaran berikutnya"
            id="btn-next-lesson"
        >Lanjut →</button>
    </div>
</div>

<script>
    function audioNarrator(text, efekSuara, lessonId) {
        return {
            text,
            efekSuara,
            lessonId,
            statusMessage: '',
            audioElement: null,

            async playNarration() {
                this.statusMessage = 'Memainkan narasi...';

                const speak = async (msg) => {
                    return new Promise((resolve) => {
                        const utter = new SpeechSynthesisUtterance(msg);
                        utter.lang = 'id-ID';
                        utter.rate = 0.85;
                        utter.pitch = 1.2;
                        utter.onend = resolve;
                        utter.onerror = resolve;
                        speechSynthesis.speak(utter);
                    });
                };

                // Lazy load audio effect only when needed
                if (this.efekSuara && !this.audioElement) {
                    this.audioElement = new Audio(this.efekSuara);
                    this.audioElement.preload = 'none';
                }

                if (this.audioElement) {
                    try {
                        await new Promise((resolve, reject) => {
                            this.audioElement.addEventListener('ended', resolve, { once: true });
                            this.audioElement.addEventListener('error', resolve, { once: true });
                            this.audioElement.play().catch(resolve);
                        });
                    } catch (e) { /* ignore audio errors */ }
                }

                await speak(this.text);
                this.statusMessage = 'Narasi selesai. Tekan tombol untuk mendengar lagi atau lanjut.';
            },

            init() {
                this.playNarration();
            }
        };
    }
</script>
