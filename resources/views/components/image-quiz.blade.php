@props(['pertanyaan', 'pilihan'])

<div x-data="imageQuiz()" class="image-quiz" aria-live="polite">
    <style>
        .image-quiz {
            max-width: 100%;
            margin: 2rem auto;
        }
        .image-quiz .question {
            font-size: 1.5rem;
            font-weight: 600;
            text-align: center;
            margin-bottom: 1.5rem;
        }
        .image-quiz .grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(140px, 1fr));
            gap: 1rem;
        }
        .image-quiz .card {
            position: relative;
            border: 2px solid transparent;
            border-radius: .5rem;
            overflow: hidden;
            cursor: pointer;
            transition: transform .2s ease;
        }
        .image-quiz .card img {
            width: 100%;
            height: 140px;
            object-fit: cover;
        }
        .image-quiz .card .label {
            padding: .5rem;
            text-align: center;
            font-weight: 500;
        }
        .image-quiz .card.benar {
            border-color: #28a745; /* green */
        }
        .image-quiz .card.salah {
            border-color: #dc3545; /* red */
        }
        .shake {
            animation: shake 0.5s;
        }
        @keyframes shake {
            0% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            50% { transform: translateX(5px); }
            75% { transform: translateX(-5px); }
            100% { transform: translateX(0); }
        }
    </style>

    <div class="question" aria-label="Pertanyaan kuis">{{ $pertanyaan }}</div>

    <div class="grid" aria-label="Pilihan gambar kuis">
        @foreach($pilihan as $index => $item)
            <div
                class="card"
                :class="dipilih === {{ $index }} ? (status === 'benar' ? 'benar' : 'salah') : ''"
                @click="pilih({{ $index }}, {{ $item['benar'] ? 'true' : 'false' }})"
                x-ref="card{{ $index }}"
                aria-label="Pilihan {{ $index + 1 }}: {{ $item['label'] }}, {{ $item['benar'] ? 'Jawaban benar' : 'Jawaban salah' }}"
            >
                <img src="{{ $item['gambar'] }}" alt="{{ $item['label'] }}" />
                <div class="label">{{ $item['label'] }}</div>
            </div>
        @endforeach
    </div>

    <!-- Lottie mascot placeholder; will be injected via script when needed -->
    <div x-ref="lottieMascot" style="position:absolute; top:0; left:0; width:150px; height:150px; pointer-events:none;"></div>

    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.2/dist/confetti.browser.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lottie-web/5.12.2/lottie.min.js"></script>
    <script>
        function imageQuiz() {
            return {
                dipilih: null,
                status: null,
                async pilih(index, benar) {
                    this.dipilih = index;
                    this.status = benar ? 'benar' : 'salah';
                    const cardEl = this.$refs['card' + index];
                    if (benar) {
                        // green border already applied via class binding
                        // confetti
                        confetti({
                            particleCount: 80,
                            spread: 70,
                            origin: { y: 0.6 }
                        });
                        // mascot Lottie joget (happy)
                        this.playMascot('https://assets9.lottiefiles.com/packages/lf20_jcikwtzn.json');
                        setTimeout(() => {
                            this.$dispatch('quiz-selesai');
                        }, 1500);
                    } else {
                        // add shake animation
                        cardEl.classList.add('shake');
                        setTimeout(() => cardEl.classList.remove('shake'), 1000);
                        // mascot Lottie geleng kepala (sad)
                        this.playMascot('https://assets9.lottiefiles.com/packages/lf20_ka0r6zkc.json');
                    }
                },
                playMascot(url) {
                    const container = this.$refs.lottieMascot;
                    container.innerHTML = '';
                    lottie.loadAnimation({
                        container,
                        renderer: 'svg',
                        loop: false,
                        autoplay: true,
                        path: url
                    });
                }
            };
        }
    </script>
</div>
