@props(['pertanyaan', 'jawabanBenar', 'lessonId'])

<div
    x-data="voiceQuiz(
        {{ \Illuminate\\Support\\Js::from($pertanyaan) }},
        {{ \Illuminate\\Support\\Js::from($jawabanBenar) }},
        {{ $lessonId }}
    )"
    class="voice-quiz"
    aria-live="polite"
>
    <style>
        .voice-quiz .pulse {
            width: 2rem;
            height: 2rem;
            background: hsla(200,70%,60%,0.6);
            border-radius: 50%;
            animation: pulse 1.2s infinite;
            margin: .5rem auto;
        }
        @keyframes pulse {
            0%   { transform: scale(1); opacity: .7; }
            50%  { transform: scale(1.3); opacity: 1; }
            100% { transform: scale(1); opacity: .7; }
        }
        .voice-quiz .msg {
            font-size: 1.125rem;
            text-align: center;
            margin-top: .75rem;
        }
        .voice-quiz .big-button {
            min-width: 180px;
            padding: .75rem 1.5rem;
            margin-top: 1rem;
            font-size: 1rem;
            background: hsla(140,55%,45%,1);
            color: #fff;
            border: none;
            border-radius: .5rem;
            cursor: pointer;
        }
        .voice-quiz .permission-modal {
            background: #fff;
            border: 2px solid hsl(210,70%,50%);
            border-radius: .75rem;
            padding: 1.5rem;
            max-width: 320px;
            margin: 1rem auto;
            text-align: center;
            box-shadow: 0 4px 12px rgba(0,0,0,.1);
        }
    </style>

    <!-- Permission request UI -->
    <template x-if="!permissionGranted">
        <div class="permission-modal" role="dialog" aria-modal="true" aria-labelledby="perm-title">
            <h2 id="perm-title">Izin Mikrofon</h2>
            <p>Untuk kuis suara, aplikasi membutuhkan izin mikrofon. Tekan tombol berikut untuk memberi izin.</p>
            <button class="big-button" @click="requestPermission()" aria-label="Minta izin mikrofon">Berikan Izin</button>
        </div>
    </template>

    <!-- Pulse while listening -->
    <div x-show="listening" class="pulse" aria-label="Mikrofon aktif, silakan berbicara"></div>

    <p class="msg" x-text="message"></p>

    <button
        class="big-button"
        x-show="finished"
        @click="$dispatch('next-lesson')"
        aria-label="Lanjut ke pelajaran berikutnya setelah kuis selesai"
    >Lanjut →</button>
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

                this.recognizer.onstart = () => { this.listening = true; };
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
                    this.message = 'Hebat! Kamu benar!';
                    await this.speak(this.message);
                    await fetch(`/api/quiz/answer`, {
                        method: 'POST',
                        headers: {'Content-Type':'application/json','X-Requested-With':'XMLHttpRequest'},
                        body: JSON.stringify({lesson_id: this.lessonId, answer: userAnswer})
                    });
                    this.finished = true;
                } else {
                    this.attempts++;
                    this.message = 'Hampir! Coba lagi ya!';
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
</div>
