{{-- Audio World: Voice Quiz Component --}}
{{-- All buttons ≥ 80×80px, all elements have aria-label --}}
@props(['pertanyaan', 'jawabanBenar', 'lessonId'])

<div
    x-data="voiceQuiz(
        {{ \Illuminate\Support\Js::from($pertanyaan) }},
        {{ \Illuminate\Support\Js::from($jawabanBenar) }},
        {{ $lessonId }}
    )"
    class="voice-quiz"
    role="region"
    aria-label="Kuis suara untuk pelajaran"
    aria-live="polite"
>
    <style>
        .voice-quiz {
            max-width: 100%;
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1rem;
        }
        .voice-quiz .pulse {
            width: 3rem;
            height: 3rem;
            background: hsla(200,70%,60%,0.6);
            border-radius: 50%;
            animation: vq-pulse 1.2s infinite;
            margin: .5rem auto;
        }
        @keyframes vq-pulse {
            0%   { transform: scale(1); opacity: .7; }
            50%  { transform: scale(1.3); opacity: 1; }
            100% { transform: scale(1); opacity: .7; }
        }
        .voice-quiz .msg {
            font-size: 1.125rem;
            text-align: center;
            margin-top: .75rem;
            min-height: 1.5rem;
        }
        .voice-quiz .big-button {
            /* ENFORCED: minimum 80×80px for nav buttons */
            min-width: 80px;
            min-height: 80px;
            padding: 1rem 1.5rem;
            margin-top: 1rem;
            font-size: 1rem;
            font-weight: 600;
            background: linear-gradient(135deg, hsl(140,55%,45%), hsl(140,55%,35%));
            color: #fff;
            border: none;
            border-radius: .75rem;
            cursor: pointer;
            transition: transform .15s ease, box-shadow .2s ease;
            text-align: center;
            font-family: inherit;
        }
        .voice-quiz .big-button:hover,
        .voice-quiz .big-button:focus-visible {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0,0,0,.2);
            outline: 3px solid hsl(140, 55%, 60%);
            outline-offset: 2px;
        }
        .voice-quiz .big-button.secondary {
            background: linear-gradient(135deg, hsl(210,70%,55%), hsl(210,70%,40%));
        }
        .voice-quiz .big-button.secondary:focus-visible {
            outline-color: hsl(210, 70%, 70%);
        }
        .voice-quiz .permission-modal {
            background: #fff;
            border: 2px solid hsl(210,70%,50%);
            border-radius: .75rem;
            padding: 1.5rem;
            max-width: 360px;
            margin: 1rem auto;
            text-align: center;
            box-shadow: 0 4px 12px rgba(0,0,0,.1);
        }
        .voice-quiz .permission-modal h2 {
            font-size: 1.25rem;
            margin-bottom: 0.5rem;
        }
        .voice-quiz .permission-modal p {
            margin-bottom: 1rem;
            color: #555;
        }
    </style>

    <!-- Permission request UI -->
    <template x-if="!permissionGranted">
        <div class="permission-modal" role="dialog" aria-modal="true" aria-labelledby="perm-title" aria-describedby="perm-desc">
            <h2 id="perm-title">Izin Mikrofon</h2>
            <p id="perm-desc">Untuk kuis suara, aplikasi membutuhkan izin mikrofon. Tekan tombol berikut untuk memberi izin.</p>
            <button
                class="big-button"
                @click="requestPermission()"
                aria-label="Berikan izin akses mikrofon untuk kuis suara"
                id="btn-mic-permission"
            >🎤 Berikan Izin</button>
        </div>
    </template>

    <!-- Pulse while listening -->
    <div
        x-show="listening"
        class="pulse"
        role="status"
        aria-label="Mikrofon aktif, silakan berbicara sekarang"
    ></div>

    <p class="msg"
        x-text="message"
        aria-live="assertive"
        aria-label="Pesan status kuis suara"
    ></p>

    <div style="display:flex; flex-wrap:wrap; justify-content:center; gap:0.75rem;">
        <button
            class="big-button secondary"
            x-show="!listening && permissionGranted && !finished"
            @click="startRecognition()"
            aria-label="Ulangi mendengarkan jawaban suara"
            id="btn-retry-voice"
        >🎤 Bicara Lagi</button>

        <button
            class="big-button"
            x-show="finished"
            @click="$dispatch('next-lesson')"
            aria-label="Lanjut ke pelajaran berikutnya setelah kuis selesai"
            id="btn-next-after-quiz"
        >Lanjut →</button>
    </div>
</div>

<script>
    function normalizeAnswer(text) {
        const angkaMap = {
            '1':'satu','2':'dua','3':'tiga','4':'empat','5':'lima',
            '6':'enam','7':'tujuh','8':'delapan','9':'sembilan','10':'sepuluh'
        };
        let clean = text.toLowerCase().trim().replace(/[^a-z0-9]/g, '');
        return angkaMap[clean] || clean;
    }

    function voiceQuiz(question, correctAnswer, lessonId) {
        return {
            question,
            correctAnswer: normalizeAnswer(correctAnswer),
            lessonId,
            attempts: 0,
            maxAttempts: 3,
            listening: false,
            permissionGranted: false,
            finished: false,
            message: '',
            recognizer: null,

            async init() {
                await this.speak(this.question);
                await this.wait(1500);
                this.checkPermissionAndStart();
            },

            async speak(msg) {
                return new Promise(resolve => {
                    const utter = new SpeechSynthesisUtterance(msg);
                    utter.lang = 'id-ID';
                    utter.rate = 0.85;
                    utter.pitch = 1.2;
                    utter.onend = resolve;
                    utter.onerror = resolve;
                    speechSynthesis.speak(utter);
                });
            },

            async wait(ms) {
                return new Promise(r => setTimeout(r, ms));
            },

            async requestPermission() {
                try {
                    const stream = await navigator.mediaDevices.getUserMedia({audio:true});
                    stream.getTracks().forEach(t=>t.stop());
                    this.permissionGranted = true;
                    this.startRecognition();
                } catch (e) {
                    this.message = 'Izin mikrofon ditolak. Tidak dapat melanjutkan kuis.';
                }
            },

            async checkPermissionAndStart() {
                if (!('mediaDevices' in navigator) || !(window.SpeechRecognition || window.webkitSpeechRecognition)) {
                    this.message = 'Peramban tidak mendukung API suara.';
                    return;
                }
                try {
                    const result = await navigator.permissions.query({name:'microphone'});
                    this.permissionGranted = result.state === 'granted';
                } catch (_) {
                    this.permissionGranted = false;
                }
                if (this.permissionGranted) {
                    this.startRecognition();
                }
            },

            async startRecognition() {
                const SpeechRec = window.SpeechRecognition || window.webkitSpeechRecognition;
                this.recognizer = new SpeechRec();
                this.recognizer.lang = 'id-ID';
                this.recognizer.interimResults = false;
                this.recognizer.maxAlternatives = 1;

                this.recognizer.onstart = () => { this.listening = true; this.message = 'Mendengarkan... Silakan bicara.'; };
                this.recognizer.onend   = () => { this.listening = false; };
                this.recognizer.onerror = (e) => {
                    this.message = 'Terjadi kesalahan pengenalan suara.';
                    this.retryOrFinish();
                };
                this.recognizer.onresult = (e) => {
                    const transcript = e.results[0][0].transcript;
                    const normalized = normalizeAnswer(transcript);
                    this.evaluateAnswer(normalized);
                };
                this.recognizer.start();
            },

            async evaluateAnswer(userAnswer) {
                if (userAnswer === this.correctAnswer) {
                    this.message = 'Hebat! Kamu benar! 🎉';
                    await this.speak(this.message);
                    this.finished = true;
                } else {
                    this.attempts++;
                    this.message = 'Hampir! Coba lagi ya! (Percobaan ' + this.attempts + '/' + this.maxAttempts + ')';
                    await this.speak(this.message);
                    if (this.attempts < this.maxAttempts) {
                        await this.wait(1000);
                        this.startRecognition();
                    } else {
                        this.finished = true;
                        await this.speak('Waktumu habis, mari lanjut ke pelajaran berikutnya.');
                    }
                }
            },

            retryOrFinish() {
                if (this.attempts < this.maxAttempts) {
                    this.startRecognition();
                } else {
                    this.finished = true;
                }
            }
        };
    }
</script>
