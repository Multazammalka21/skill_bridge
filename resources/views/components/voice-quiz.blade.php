{{-- Audio World: Voice Quiz Component --}}
{{-- All buttons ≥ 80×80px, all elements have aria-label --}}
@props(['pertanyaan', 'jawabanBenar', 'lessonId'])

<div
    x-data="voiceQuiz(
        {{ \Illuminate\Support\Js::from($pertanyaan) }},
        {{ \Illuminate\Support\Js::from($jawabanBenar) }},
        {{ $lessonId }}
    )"
    class="voice-quiz surface-card animate-slide-up"
    role="region"
    aria-label="Kuis suara untuk pelajaran"
    aria-live="polite"
>
    <style>
        .voice-quiz {
            width: 100%;
            padding: 2rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1.5rem;
        }
        .mic-container {
            position: relative;
            width: 100px;
            height: 100px;
            margin: 1rem auto;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            background: var(--surface);
            border-radius: 50%;
            z-index: 1;
        }
        .voice-quiz .msg {
            font-size: 1.5rem;
            text-align: center;
            margin-top: 1rem;
            min-height: 2rem;
            color: var(--text);
            font-weight: bold;
        }
        .voice-quiz .permission-modal {
            background: var(--surface);
            border: 2px solid rgba(155, 114, 247, 0.5);
            border-radius: 24px;
            padding: 2rem;
            max-width: 400px;
            margin: 1rem auto;
            text-align: center;
            box-shadow: 0 8px 32px rgba(0,0,0,.3);
        }
        .voice-quiz .permission-modal h2 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }
        .voice-quiz .permission-modal p {
            margin-bottom: 1.5rem;
            color: var(--text-muted);
            font-size: 1.25rem;
        }
    </style>

    <!-- Permission request UI -->
    <template x-if="!permissionGranted">
        <div class="permission-modal" role="dialog" aria-modal="true" aria-labelledby="perm-title" aria-describedby="perm-desc">
            <h2 id="perm-title">Izin Mikrofon</h2>
            <p id="perm-desc">Untuk kuis suara, aplikasi membutuhkan izin mikrofon. Tekan tombol berikut untuk memberi izin.</p>
            <button
                class="btn"
                @click="requestPermission()"
                aria-label="Berikan izin akses mikrofon untuk kuis suara"
                id="btn-mic-permission"
            >🎤 Berikan Izin</button>
        </div>
    </template>

    <!-- Pulse while listening -->
    <div x-show="listening" class="mic-container" role="status" aria-label="Mikrofon aktif, silakan berbicara sekarang">
        🎤
        <div class="mic-ring"></div>
    </div>

    <p class="msg"
        x-text="message"
        aria-live="assertive"
        aria-label="Pesan status kuis suara"
    ></p>

    <div style="display:flex; flex-wrap:wrap; justify-content:center; gap:0.75rem;">
        <button
            class="btn" style="background: transparent; border: 2px solid rgba(155, 114, 247, 0.5);"
            x-show="!listening && permissionGranted && !finished"
            @click="startRecognition()"
            aria-label="Ulangi mendengarkan jawaban suara"
            id="btn-retry-voice"
        >🎤 Bicara Lagi</button>

        <button
            class="btn"
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
