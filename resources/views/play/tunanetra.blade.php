@extends('layouts.audio-world')

@section('content')
    <div x-data="audioWorldApp()" class="w-full">
        <!-- 1. Introduction Screen -->
        <template x-if="currentState === 'welcome'">
            <div class="surface-card animate-slide-up" style="display: flex; flex-direction: column; align-items: center; gap: 2rem; padding: 3rem;">
                <h1 style="font-size: 3rem; color: #fffffe; font-family: 'Fredoka', sans-serif;">🦉 Dunia Audio Pinteria</h1>
                <p style="font-size: 1.5rem; color: #d8c4ff; text-align: center; max-width: 600px; font-weight: bold;">
                    Halo Petualang Cilik! Bersiaplah mendengar cerita seru dan menjawab pertanyaan menggunakan suaramu.
                </p>
                <button 
                    class="btn btn-next animate-pulse" 
                    @click="startAdventure()"
                    aria-label="Tekan tombol besar ini untuk memulai petualangan belajarmu!"
                    style="font-size: 2.2rem; min-height: 100px; padding: 0 50px;"
                >
                    🚀 MULAI PETUALANGAN!
                </button>
            </div>
        </template>

        <!-- 1b. Next Lesson Prompt Screen -->
        <template x-if="currentState === 'next_lesson_prompt'">
            <div class="surface-card animate-slide-up" style="display: flex; flex-direction: column; align-items: center; gap: 2rem; padding: 3rem;">
                <h1 style="font-size: 2.5rem; color: #fffffe; font-family: 'Fredoka', sans-serif;">📖 Cerita Selanjutnya</h1>
                <p style="font-size: 1.5rem; color: #d8c4ff; text-align: center; max-width: 600px; font-weight: bold;" x-text="'Mulai pelajaran: ' + lessons[lessonIndex].judul"></p>
                <button 
                    class="btn btn-next animate-pulse" 
                    @click="currentState = 'narrator'; loadLesson();"
                    aria-label="Tekan tombol besar ini untuk memulai cerita berikutnya!"
                    style="font-size: 2.2rem; min-height: 100px; padding: 0 50px;"
                >
                    🚀 MULAI CERITA!
                </button>
            </div>
        </template>

        <!-- 2. Narration Stage -->
        <template x-if="currentState === 'narrator'">
            <div class="surface-card animate-slide-up" style="display: flex; flex-direction: column; align-items: center; gap: 2rem; padding: 2rem;">
                <h2 style="font-size: 2rem; font-family: 'Nunito', sans-serif; color: #fffffe;" x-text="currentLesson.judul"></h2>
                
                <div class="story-box" x-text="currentLesson.deskripsi" style="background: rgba(155, 114, 247, 0.1); border: 2px solid rgba(155, 114, 247, 0.3); border-radius: 18px; padding: 2rem; font-size: 1.6rem; color: #fffffe; line-height: 1.6;"></div>

                <p class="status-text" style="font-size: 1.4rem; color: #b48fff; font-weight: bold;" x-text="narrationStatus"></p>

                <div style="display: flex; gap: 1.5rem; flex-wrap: wrap; justify-content: center;">
                    <button
                        class="btn btn-repeat"
                        @click="playNarration()"
                        aria-label="Dengar cerita sekali lagi"
                        style="min-height: 80px;"
                    >
                        ▶️ Dengar Lagi
                    </button>
                    <button
                        class="btn btn-next"
                        @click="startQuiz()"
                        aria-label="Lanjut ke kuis cerita ini"
                        style="min-height: 80px;"
                    >
                        Mulai Kuis 📝
                    </button>
                </div>
            </div>
        </template>

        <!-- 3. Quiz Stage -->
        <template x-if="currentState === 'quiz'">
            <div class="surface-card animate-slide-up" style="display: flex; flex-direction: column; align-items: center; gap: 2rem; padding: 2rem;">
                <h2 style="font-size: 1.8rem; font-family: 'Nunito', sans-serif; color: #d8c4ff;">Pertanyaan Kuis</h2>

                <!-- Question counter -->
                <div style="font-size: 1.1rem; color: #b48fff; font-weight: 600;" x-text="'Soal ' + (questionIndex + 1) + ' dari ' + questionsList.length"></div>

                <div class="story-box" x-text="currentQuestion.pertanyaan" style="background: rgba(155, 114, 247, 0.08); border: 1px solid rgba(155, 114, 247, 0.2); font-size: 1.8rem; color: #fffffe; padding: 2rem; border-radius: 18px;"></div>

                <!-- Replay story audio button (shown when question has audio_url) -->
                <template x-if="currentQuestion.audio_url">
                    <button
                        class="btn btn-repeat"
                        @click="replayQuestionAudio()"
                        aria-label="Dengarkan cerita lagi untuk membantu menjawab"
                        style="min-height: 64px; font-size: 1.3rem; background: rgba(255,214,0,0.12); border-color: #ffd600; color: #ffd600;"
                    >
                        🎧 Dengar Cerita Lagi
                    </button>
                </template>

                <!-- Recording indicator -->
                <div x-show="listening" style="display:flex;align-items:center;justify-content:center;gap:15px;margin:10px 0;padding:15px 30px;background:rgba(220,38,38,0.15);border-radius:50px;border:1px solid rgba(220,38,38,0.4);">
                    <div style="background:#ef4444;width:40px;height:40px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:20px;color:white;animation:pulse 1.5s infinite;">🎙️</div>
                    <span style="font-size:1.4rem;color:#fca5a5;font-weight:bold;">Merekam... lepas SPASI jika sudah!</span>
                </div>
                <!-- Processing indicator -->
                <div x-show="processing" style="display:flex;align-items:center;justify-content:center;gap:15px;margin:10px 0;padding:15px 30px;background:rgba(245,158,11,0.12);border-radius:50px;border:1px solid rgba(245,158,11,0.3);">
                    <div style="width:36px;height:36px;border:4px solid rgba(251,191,36,0.3);border-top-color:#fbbf24;border-radius:50%;animation:spin 0.8s linear infinite;"></div>
                    <span style="font-size:1.3rem;color:#fcd34d;font-weight:bold;">Memproses jawaban...</span>
                </div>

                <p class="status-text" style="font-size:1.5rem;color:#ffeb3b;font-weight:bold;text-align:center;min-height:2.5rem;" x-text="quizStatus"></p>

                <!-- Fallback Multiple Choice -->
                <div x-show="!quizFinished" style="width:100%;max-width:500px;margin-top:1rem;">
                    <p style="font-size:1.1rem;color:#b48fff;margin-bottom:0.8rem;text-align:center;font-weight:bold;">💡 Pilihan Jawaban (Pendamping dapat mengklik jika mikrofon bermasalah):</p>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;width:100%;">
                        <template x-for="(opt, idx) in currentQuestion.pilihan" :key="idx">
                            <button class="btn" style="min-height:60px;font-size:1.2rem;background:rgba(155,114,247,0.15);border:2px solid rgba(155,114,247,0.3);color:#fffffe;border-radius:15px;padding:10px;box-shadow:none;" @click="selectOptionFallback(opt)">
                                <span x-text="opt"></span>
                            </button>
                        </template>
                    </div>
                </div>

                <div style="display:flex;gap:1.5rem;flex-wrap:wrap;justify-content:center;">
                    <!-- Push-to-talk button -->
                    <button id="ptt-btn"
                        class="btn btn-repeat"
                        x-show="!listening && !processing && !quizFinished"
                        @mousedown="startRecording()"
                        @mouseup="stopRecording()"
                        @touchstart.prevent="startRecording()"
                        @touchend.prevent="stopRecording()"
                        aria-label="Tahan tombol ini atau tekan SPASI untuk menjawab dengan suara"
                        style="min-height:80px;background:rgba(239,68,68,0.15);border-color:#ef4444;color:#fca5a5;"
                    >🎙️ Tahan untuk Berbicara</button>
                    <button
                        class="btn btn-next"
                        x-show="quizFinished"
                        @click="nextSlide()"
                        aria-label="Lanjut"
                        style="min-height:80px;"
                    >Lanjut ➡️</button>
                </div>
            </div>
        </template>

        <!-- 4. Victory / Results Screen -->
        <template x-if="currentState === 'victory'">
            <div class="surface-card animate-slide-up" style="display: flex; flex-direction: column; align-items: center; gap: 2rem; padding: 3rem; text-align: center;">
                <h1 style="color: #ffd600; font-size: 3.5rem; font-family: 'Fredoka', sans-serif;">🎉 Petualangan Selesai! 🎉</h1>
                
                <div style="font-size: 5rem; letter-spacing: 10px; margin: 1rem 0;" x-text="getStarsEmoji()"></div>
                
                <div style="background: rgba(255, 255, 255, 0.05); padding: 2rem; border-radius: 20px; border: 1px solid rgba(155, 114, 247, 0.2); width: 100%; max-width: 500px;">
                    <h3 style="font-size: 2rem; color: #fffffe; margin-bottom: 1rem;">Skor Kamu</h3>
                    <h2 style="font-size: 4rem; color: #ffd600; margin: 0;" x-text="totalScore + '%'"></h2>
                    <p style="font-size: 1.4rem; color: #b48fff; margin-top: 1rem;" x-text="'Benar ' + totalCorrect + ' dari ' + totalQuestions + ' soal'"></p>
                </div>

                <!-- Newly Earned Badges -->
                <div x-show="earnedBadges.length > 0" style="width: 100%; max-width: 500px; margin-top: 1rem;">
                    <h3 style="font-size: 1.8rem; color: #ffd600; margin-bottom: 1rem;">🏆 Lencana Baru Diraih!</h3>
                    <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
                        <template x-for="badge in earnedBadges" :key="badge.id">
                            <div style="background: rgba(155,114,247,0.15); border: 2px solid #9b72f7; padding: 15px; border-radius: 16px; min-width: 120px; text-align: center;">
                                <div style="font-size: 3rem; margin-bottom: 5px;" x-text="badge.ikon"></div>
                                <div style="font-weight: bold; color: #fffffe; font-size: 1.2rem;" x-text="badge.nama"></div>
                            </div>
                        </template>
                    </div>
                </div>

                <div style="display: flex; gap: 1.5rem; width: 100%; justify-content: center; margin-top: 2rem;">
                    <button 
                        @click="openParentUnlockModal($event, '/dashboard')"
                        class="btn btn-repeat"
                        style="min-height: 80px;"
                        aria-label="Kembali ke halaman utama orang tua"
                    >
                        🏠 Dashboard
                    </button>
                    <button 
                        @click="location.reload()"
                        class="btn btn-next"
                        style="min-height: 80px;"
                        aria-label="Mainkan petualangan sekali lagi dari awal"
                    >
                        🔄 Main Lagi
                    </button>
                </div>
            </div>
        </template>
    </div>

    <style>
        @keyframes spin { to { transform: rotate(360deg); } }
    </style>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('audioWorldApp', () => ({
                currentState: 'welcome',
                childId: @json($child->id),
                lessons: @json($lessons),
                lessonIndex: 0,
                questionIndex: 0,
                currentLesson: null,
                currentQuestion: null,
                questionsList: [],
                narrationStatus: '',
                quizStatus: '',
                listening: false,
                processing: false,
                quizFinished: false,
                activeAudio: null,
                mediaRecorder: null,
                audioChunks: [],
                totalCorrect: 0,
                totalQuestions: 0,
                totalScore: 0,
                earnedBadges: [],
                correctCountForCurrentLesson: 0,
                attemptsForCurrentQuestion: 0,
                timerStart: null,
                csrf: document.querySelector('meta[name="csrf-token"]')?.content ?? '',

                init() {
                    this.speakTTS("Halo {{ $child->nama_panggilan ?? 'Petualang' }}, selamat datang di dunia audio Pinteria. Tekan tombol besar di tengah layar untuk memulai!");
                    document.addEventListener('keydown', (e) => {
                        if (e.code === 'Space' && this.currentState === 'quiz' && !this.listening && !this.processing && !this.quizFinished) {
                            e.preventDefault();
                            this.startRecording();
                        }
                    });
                    document.addEventListener('keyup', (e) => {
                        if (e.code === 'Space' && this.listening) {
                            e.preventDefault();
                            this.stopRecording();
                        }
                    });
                },

                /* ── TTS: Server-side (Google Cloud) → fallback Browser ── */
                async speakTTS(text) {
                    return new Promise(async (resolve) => {
                        this.stopActiveAudio();
                        try {
                            const res = await fetch('{{ route("play.ai.tts") }}', {
                                method: 'POST',
                                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': this.csrf },
                                body: JSON.stringify({ text }),
                            });
                            if (res.status === 200) {
                                const blob = await res.blob();
                                const url  = URL.createObjectURL(blob);
                                this.activeAudio = new Audio(url);
                                this.activeAudio.onended = () => { URL.revokeObjectURL(url); resolve(); };
                                this.activeAudio.onerror = resolve;
                                await this.activeAudio.play().catch(resolve);
                                return;
                            }
                        } catch(e) { /* fallback */ }
                        const u = new SpeechSynthesisUtterance(text);
                        u.lang = 'id-ID'; u.rate = 0.85; u.pitch = 1.2;
                        u.onend = resolve; u.onerror = resolve;
                        speechSynthesis.speak(u);
                    });
                },

                stopActiveAudio() {
                    speechSynthesis.cancel();
                    if (this.activeAudio) {
                        try { this.activeAudio.pause(); this.activeAudio.currentTime = 0; } catch(e) {}
                        this.activeAudio = null;
                    }
                },

                /* ── MediaRecorder Push-to-Talk ── */
                async startRecording() {
                    if (this.listening || this.processing) return;
                    try {
                        const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
                        this.audioChunks = [];
                        const mime = MediaRecorder.isTypeSupported('audio/webm') ? 'audio/webm' : 'audio/ogg';
                        this.mediaRecorder = new MediaRecorder(stream, { mimeType: mime });
                        this.mediaRecorder.ondataavailable = (e) => { if (e.data.size > 0) this.audioChunks.push(e.data); };
                        this.mediaRecorder.onstop = async () => { stream.getTracks().forEach(t => t.stop()); await this.processAudioAnswer(); };
                        this.mediaRecorder.start();
                        this.listening = true;
                        this.quizStatus = '🎙️ Mendengarkan... lepas SPASI jika sudah selesai!';
                    } catch(err) {
                        this.quizStatus = '⚠️ Akses mikrofon ditolak. Pendamping dapat memilih jawaban di bawah.';
                        await this.speakTTS('Akses mikrofon ditolak. Pendamping dapat membantu memilih jawaban di bawah.');
                    }
                },

                stopRecording() {
                    this.listening = false;
                    if (this.mediaRecorder && this.mediaRecorder.state === 'recording') this.mediaRecorder.stop();
                },

                /* ── Pipeline: Audio → Whisper → Llama → TTS feedback ── */
                async processAudioAnswer() {
                    if (!this.audioChunks.length) return;
                    this.processing = true;
                    this.quizStatus = '⏳ Memproses jawabanmu...';
                    const mime = this.audioChunks[0].type || 'audio/webm';
                    const ext  = mime.includes('ogg') ? 'ogg' : 'webm';
                    const fd   = new FormData();
                    fd.append('audio',          new Blob(this.audioChunks, { type: mime }), 'answer.' + ext);
                    fd.append('correct_answer', this.currentQuestion.jawaban_benar);
                    fd.append('question',       this.currentQuestion.pertanyaan);
                    try {
                        const res = await fetch('{{ route("play.ai.evaluate") }}', {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': this.csrf },
                            body: fd,
                        });
                        this.processing = false;
                        if (res.status === 422) {
                            this.attemptsForCurrentQuestion++;
                            if (this.attemptsForCurrentQuestion < 3) {
                                this.quizStatus = 'Suara kurang jelas. Tekan SPASI untuk coba lagi!';
                                await this.speakTTS('Suara kurang terdengar. Coba bicara lebih jelas dan lebih keras.');
                            } else {
                                await this.speakTTS('Waktu menjawab habis. Mari lanjut ke pertanyaan berikutnya.');
                                this.submitQuizAnswer('Tidak terdeteksi', false);
                            }
                            return;
                        }
                        if (!res.ok) throw new Error(res.status);
                        const data = await res.json();
                        this.quizStatus = '💬 "' + data.transcript + '"';
                        if (data.is_correct) {
                            this.totalCorrect++;
                            this.correctCountForCurrentLesson++;
                            await this.speakTTS(data.feedback);
                            this.submitQuizAnswer(data.transcript, true);
                        } else {
                            this.attemptsForCurrentQuestion++;
                            if (this.attemptsForCurrentQuestion < 3) {
                                await this.speakTTS(data.feedback + ' Tekan SPASI untuk coba lagi!');
                            } else {
                                await this.speakTTS(data.feedback);
                                this.submitQuizAnswer(data.transcript, false);
                            }
                        }
                    } catch(err) {
                        this.processing = false;
                        this.quizStatus = '⚠️ Koneksi bermasalah. Coba lagi.';
                        await this.speakTTS('Terjadi kesalahan koneksi. Coba jawab lagi.');
                    }
                },

                startAdventure() {
                    if (!this.lessons.length) { this.speakTTS('Maaf, belum ada petualangan cerita untukmu hari ini.'); return; }
                    this.lessonIndex = 0;
                    this.currentState = 'narrator';
                    this.loadLesson();
                },

                loadLesson() {
                    this.currentLesson = this.lessons[this.lessonIndex];
                    this.questionsList = this.currentLesson.quiz_questions || [];
                    this.questionIndex = 0;
                    this.correctCountForCurrentLesson = 0;
                    this.quizFinished = false;
                    this.playNarration();
                },

                async replayQuestionAudio() {
                    if (!this.currentQuestion.audio_url) return;
                    this.stopActiveAudio();
                    this.quizStatus = '🎧 Memutar cerita...';
                    this.activeAudio = new Audio(encodeURI(this.currentQuestion.audio_url));
                    await new Promise((r) => { this.activeAudio.onended = r; this.activeAudio.onerror = r; this.activeAudio.play().catch(r); });
                    this.quizStatus = 'Cerita selesai. Tekan SPASI untuk menjawab!';
                    if (!this.quizFinished) { await this.speakTTS('Sekarang jawab pertanyaan tadi!'); }
                },

                async playNarration() {
                    this.stopActiveAudio();
                    this.narrationStatus = 'Memutar cerita...';
                    if (this.currentLesson.audio_story_url) {
                        try {
                            this.activeAudio = new Audio(encodeURI(this.currentLesson.audio_story_url));
                            await new Promise((res, rej) => { this.activeAudio.onended = res; this.activeAudio.onerror = rej; this.activeAudio.play().catch(rej); });
                        } catch(e) { await this.speakTTS(this.currentLesson.deskripsi); }
                    } else {
                        if (this.currentLesson.efek_suara) { try { await new Audio(encodeURI(this.currentLesson.efek_suara)).play().catch(()=>{}); } catch(e){} }
                        await this.speakTTS(this.currentLesson.deskripsi);
                    }
                    this.narrationStatus = 'Cerita selesai. Tekan kuis untuk mulai!';
                    await this.speakTTS('Cerita selesai. Silakan tekan tombol kuis di kanan bawah untuk mulai menjawab!');
                },

                startQuiz() {
                    this.stopActiveAudio();
                    if (!this.questionsList.length) { this.nextLessonOrFinish(); return; }
                    this.currentState = 'quiz';
                    this.loadQuestion();
                },

                async loadQuestion() {
                    this.currentQuestion = this.questionsList[this.questionIndex];
                    this.attemptsForCurrentQuestion = 0;
                    this.quizFinished = false;
                    this.timerStart = Date.now();
                    this.quizStatus = 'Membacakan pertanyaan...';
                    await this.speakTTS(this.currentQuestion.pertanyaan);
                    this.quizStatus = '⬇️ Tekan SPASI atau tombol merah untuk menjawab!';
                    await this.speakTTS('Tekan dan tahan tombol merah atau tombol SPASI, lalu ucapkan jawabanmu!');
                },

                async selectOptionFallback(opt) {
                    if (this.listening) this.stopRecording();
                    if (this.processing) return;
                    const norm = (s) => s.toLowerCase().trim().replace(/[^a-z0-9]/g, '');
                    const ok   = norm(opt) === norm(this.currentQuestion.jawaban_benar);
                    if (ok) {
                        this.totalCorrect++;
                        this.correctCountForCurrentLesson++;
                        this.quizStatus = 'Hebat! Jawabanmu benar! 🎉';
                        await this.speakTTS('Hebat! Jawabanmu benar!');
                        this.submitQuizAnswer(opt, true);
                    } else {
                        this.attemptsForCurrentQuestion++;
                        if (this.attemptsForCurrentQuestion < 3) {
                            this.quizStatus = 'Belum tepat. Coba pilih lagi!';
                            await this.speakTTS('Belum tepat. Coba pilih lagi.');
                        } else {
                            this.quizStatus = 'Jawaban benar: ' + this.currentQuestion.jawaban_benar;
                            await this.speakTTS('Belum tepat. Jawaban yang benar adalah ' + this.currentQuestion.jawaban_benar);
                            this.submitQuizAnswer(opt, false);
                        }
                    }
                },

                submitQuizAnswer(userAnswer, isCorrect) {
                    const elapsed = Math.round((Date.now() - this.timerStart) / 1000);
                    let skor = 0;
                    if (isCorrect) { skor = this.attemptsForCurrentQuestion <= 1 ? 100 : this.attemptsForCurrentQuestion === 2 ? 70 : 40; }
                    this.totalQuestions++;
                    fetch('{{ route('play.quiz.submit') }}', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': this.csrf },
                        body: JSON.stringify({ child_id: this.childId, lesson_id: this.currentLesson.id, quiz_question_id: this.currentQuestion.id, jawaban_anak: userAnswer, benar: isCorrect ? 1 : 0, skor, percobaan: this.attemptsForCurrentQuestion, waktu_detik: elapsed }),
                    }).then(r => r.json()).then(d => { if (d.new_badges?.length) this.earnedBadges = [...this.earnedBadges, ...d.new_badges]; });
                    this.quizFinished = true;
                    setTimeout(() => { if (this.quizFinished) this.nextSlide(); }, isCorrect ? 2000 : 2800);
                },

                nextSlide() {
                    this.stopActiveAudio();
                    if (!this.quizFinished && this.currentState === 'quiz') return;
                    this.quizFinished = false;
                    this.questionIndex++;
                    if (this.questionIndex < this.questionsList.length) this.loadQuestion();
                    else this.nextLessonOrFinish();
                },

                nextLessonOrFinish() {
                    this.stopActiveAudio();
                    this.lessonIndex++;
                    if (this.lessonIndex < this.lessons.length) {
                        this.currentState = 'next_lesson_prompt';
                        this.speakTTS('Pelajaran berikutnya sudah siap. Silakan tekan tombol besar untuk memulai cerita berikutnya.');
                    } else { this.finishAdventure(); }
                },

                finishAdventure() {
                    this.stopActiveAudio();
                    this.currentState = 'victory';
                    this.totalScore = this.totalQuestions > 0 ? Math.round((this.totalCorrect / this.totalQuestions) * 100) : 0;
                    let txt = `Hore! Petualangan selesai! Kamu mendapatkan skor ${this.totalScore}!`;
                    if (this.earnedBadges.length) txt += ' Kamu juga mendapatkan lencana baru!';
                    this.speakTTS(txt);
                },

                getStarsCount() { return this.totalScore >= 100 ? 3 : this.totalScore >= 70 ? 2 : this.totalScore >= 40 ? 1 : 0; },
                getStarsEmoji() { const s = this.getStarsCount(); return '⭐'.repeat(s) + '⏳'.repeat(Math.max(0, 3 - s)); },
            }));
        });
    </script>
@endsection
