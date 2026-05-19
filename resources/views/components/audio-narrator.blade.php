{{-- Audio World: Narrator Component --}}
{{-- All buttons ≥ 80×80px, all elements have aria-label --}}
@props(['teks', 'efekSuara' => null, 'lessonId' => null])

<div
    x-data="audioNarrator({{ \Illuminate\Support\Js::from($teks) }}, {{ \Illuminate\Support\Js::from($efekSuara) }}, {{ \Illuminate\Support\Js::from($lessonId) }})"
    class="audio-narrator surface-card animate-slide-up"
    role="region"
    aria-label="Narrator audio untuk pelajaran"
    aria-live="polite"
>
    <style>
        .audio-narrator {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1.5rem;
            padding: 2rem;
            width: 100%;
        }
        .audio-narrator .status-text {
            font-size: 1.5rem;
            color: var(--text);
            text-align: center;
            min-height: 2rem;
            font-weight: 700;
        }
        .audio-narrator .btn-group {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 1.5rem;
            width: 100%;
        }
    </style>

    <p class="status-text" x-text="statusMessage" aria-live="assertive" aria-label="Status narasi saat ini"></p>

    <div class="btn-group">
        <button
            class="btn" style="background: transparent; border: 2px solid rgba(155, 114, 247, 0.5);"
            @click="playNarration()"
            aria-label="Dengarkan kembali narasi: {{ Str::limit($teks, 40) }}"
            id="btn-play-narration"
        >▶ Dengarkan Lagi</button>

        <button
            class="btn"
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
