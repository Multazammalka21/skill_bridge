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

                <!-- Mic indicator -->
                <div x-show="listening" class="mic-indicator" style="display: flex; align-items: center; justify-content: center; gap: 15px; margin: 10px 0; padding: 15px 30px; background: rgba(155, 114, 247, 0.15); border-radius: 50px; border: 1px solid rgba(155,114,247,0.3);">
                    <div class="mic-icon" style="background: #9b72f7; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 20px; color: white; animation: pulse 1.5s infinite;">🎤</div>
                    <span style="font-size: 1.4rem; color: #d8c4ff; font-weight: bold;">Mendengarkan suaramu...</span>
                </div>

                <p class="status-text" style="font-size: 1.5rem; color: #ffeb3b; font-weight: bold; text-align: center; min-height: 2.5rem;" x-text="quizStatus"></p>

                <!-- Fallback Multiple Choice Options (Visible for mentor/parent help) -->
                <div x-show="!quizFinished" style="width: 100%; max-width: 500px; margin-top: 1rem;">
                    <p style="font-size: 1.1rem; color: #b48fff; margin-bottom: 0.8rem; text-align: center; font-weight: bold;">
                        💡 Pilihan Jawaban (Pendamping dapat mengklik tombol ini jika mikrofon bermasalah):
                    </p>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; width: 100%;">
                        <template x-for="(opt, idx) in currentQuestion.pilihan" :key="idx">
                            <button
                                class="btn"
                                style="min-height: 60px; font-size: 1.2rem; background: rgba(155, 114, 247, 0.15); border: 2px solid rgba(155, 114, 247, 0.3); color: #fffffe; border-radius: 15px; padding: 10px; box-shadow: none;"
                                @click="selectOptionFallback(opt)"
                            >
                                <span x-text="opt"></span>
                            </button>
                        </template>
                    </div>
                </div>

                <div style="display: flex; gap: 1.5rem; flex-wrap: wrap; justify-content: center;">
                    <button
                        class="btn btn-repeat"
                        x-show="!listening && !quizFinished"
                        @click="startListeningSession()"
                        aria-label="Ulangi mendengarkan jawaban suara"
                        style="min-height: 80px;"
                    >
                        🎤 Jawab Lagi
                    </button>
                    <button
                        class="btn btn-next"
                        x-show="quizFinished"
                        @click="nextSlide()"
                        aria-label="Lanjut"
                        style="min-height: 80px;"
                    >
                        Lanjut ➡️
                    </button>
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

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('audioWorldApp', () => ({
                currentState: 'welcome', // welcome, narrator, quiz, victory
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
                quizFinished: false,
                activeAudio: null,

                // Quiz session metrics
                totalCorrect: 0,
                totalQuestions: 0,
                totalScore: 0,
                earnedBadges: [],
                correctCountForCurrentLesson: 0,
                attemptsForCurrentQuestion: 0,
                timerStart: null,

                init() {
                    this.speakTTS("Halo {{ $child->nama_panggilan ?? 'Petualang' }}, selamat datang di dunia audio Pinteria. Tekan tombol besar di tengah layar untuk memulai!");
                },

                speakTTS(msg) {
                    return new Promise((resolve) => {
                        const utter = new SpeechSynthesisUtterance(msg);
                        utter.lang = 'id-ID';
                        utter.rate = 0.85;
                        utter.pitch = 1.2;
                        utter.onend = resolve;
                        utter.onerror = resolve;
                        speechSynthesis.speak(utter);
                    });
                },

                startAdventure() {
                    if (this.lessons.length === 0) {
                        this.speakTTS("Maaf, belum ada petualangan cerita untukmu hari ini. Silakan hubungi admin.");
                        return;
                    }
                    this.lessonIndex = 0;
                    this.currentState = 'narrator';
                    this.loadLesson();
                },

                loadLesson() {
                    this.currentLesson = this.lessons[this.lessonIndex];
                    this.questionsList = this.currentLesson.quiz_questions || [];
                    this.questionIndex = 0;
                    this.correctCountForCurrentLesson = 0;
                    // Reset agar timer dari soal sebelumnya tidak bisa masuk ke lesson baru
                    this.quizFinished = false;
                    this.playNarration();
                },

                stopActiveAudio() {
                    if (this.activeAudio) {
                        try {
                            this.activeAudio.pause();
                            this.activeAudio.currentTime = 0;
                        } catch(e) {}
                    }
                },

                async replayQuestionAudio() {
                    if (!this.currentQuestion.audio_url) return;
                    // Pause any active speech recognition while audio plays
                    if (this.recognizer) {
                        try { this.recognizer.abort(); } catch(e) {}
                    }
                    this.stopActiveAudio();
                    this.quizStatus = '🎧 Memutar cerita...';
                    try {
                        this.activeAudio = new Audio(encodeURI(this.currentQuestion.audio_url));
                        await new Promise((resolve) => {
                            this.activeAudio.onended = resolve;
                            this.activeAudio.onerror = resolve;
                            this.activeAudio.play().catch(resolve);
                        });
                    } catch(e) {}
                    this.quizStatus = 'Cerita selesai. Jawab pertanyaan di atas!';
                    // Resume listening session after replay
                    if (!this.quizFinished) {
                        await this.speakTTS('Sekarang jawab pertanyaan tadi dengan suaramu!');
                        this.startListeningSession();
                    }
                },


                async playNarration() {
                    this.stopActiveAudio();
                    this.narrationStatus = 'Memutar cerita...';
                    
                    // Check if lesson has a high-quality pre-recorded audio file
                    if (this.currentLesson.audio_story_url) {
                        try {
                            this.activeAudio = new Audio(encodeURI(this.currentLesson.audio_story_url));
                            await new Promise((resolve, reject) => {
                                this.activeAudio.onended = resolve;
                                this.activeAudio.onerror = reject;
                                this.activeAudio.play().catch(reject);
                            });
                        } catch (e) {
                            console.error("Audio playback error, falling back to TTS:", e);
                            await this.speakTTS(this.currentLesson.deskripsi);
                        }
                    } else {
                        // Play sound effects if present
                        if (this.currentLesson.efek_suara) {
                            try {
                                const audio = new Audio(encodeURI(this.currentLesson.efek_suara));
                                await audio.play().catch(() => {});
                            } catch (e) {}
                        }
                        await this.speakTTS(this.currentLesson.deskripsi);
                    }

                    this.narrationStatus = 'Cerita selesai. Tekan kuis untuk mulai kuis!';
                    await this.speakTTS("Cerita selesai. Silakan tekan tombol kuis di kanan bawah untuk mulai menjawab kuis!");
                },

                startQuiz() {
                    this.stopActiveAudio();
                    if (this.questionsList.length === 0) {
                        this.nextLessonOrFinish();
                        return;
                    }
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
                    
                    this.startListeningSession();
                },

                recognizer: null,

                async startListeningSession() {
                    // Stop any existing recognizer session before starting a new one
                    if (this.recognizer) {
                        try {
                            this.recognizer.abort();
                        } catch(e) {}
                    }

                    // Activate Mic Speech Recognition (hanya didukung Chrome & Edge)
                    const SpeechRec = window.SpeechRecognition || window.webkitSpeechRecognition;
                    if (!SpeechRec) {
                        this.quizStatus = '⚠️ Browser ini belum mendukung kuis suara. Gunakan Google Chrome atau Microsoft Edge untuk pengalaman terbaik!';
                        await this.speakTTS('Browser ini tidak mendukung kuis suara. Silakan gunakan Google Chrome untuk pengalaman belajar terbaik.');
                        this.quizFinished = true;
                        return;
                    }

                    this.recognizer = new SpeechRec();
                    this.recognizer.lang = 'id-ID';
                    this.recognizer.interimResults = false;
                    this.recognizer.maxAlternatives = 1;

                    this.recognizer.onstart = () => {
                        this.listening = true;
                        this.quizStatus = 'Mendengarkan suaramu...';
                    };

                    this.recognizer.onend = () => {
                        this.listening = false;
                    };

                    this.recognizer.onerror = async (e) => {
                        console.error('Speech Recognition Error:', e.error);
                        this.listening = false;
                        
                        if (e.error === 'not-allowed') {
                            this.quizStatus = '⚠️ Akses mikrofon ditolak atau diblokir browser (perlu SSL/HTTPS/localhost).';
                            await this.speakTTS("Akses mikrofon ditolak atau diblokir. Pendamping dapat membantu mengeklik pilihan jawaban di bawah.");
                            return; // Stop retrying to prevent loops!
                        }

                        this.attemptsForCurrentQuestion++;
                        if (this.attemptsForCurrentQuestion < 3) {
                            this.quizStatus = 'Suara kurang terdengar. Mari coba lagi!';
                            await this.speakTTS("Suara kurang terdengar. Coba katakan sekali lagi.");
                            this.startListeningSession();
                        } else {
                            this.quizStatus = 'Kesalahan kuis. Lanjut ke soal berikutnya.';
                            await this.speakTTS("Waktu menjawab habis. Mari lanjut ke pertanyaan berikutnya.");
                            this.submitQuizAnswer(this.currentQuestion.pilihan[0] || 'Tidak ada', false);
                        }
                    };

                    this.recognizer.onresult = async (e) => {
                        const transcript = e.results[0][0].transcript;
                        const cleanAns = this.normalize(transcript);
                        const cleanCorrect = this.normalize(this.currentQuestion.jawaban_benar);

                        if (cleanAns === cleanCorrect) {
                            this.totalCorrect++;
                            this.correctCountForCurrentLesson++;
                            this.quizStatus = 'Hebat! Jawabanmu benar! 🎉';
                            await this.speakTTS("Hebat! Jawabanmu benar!");
                            this.submitQuizAnswer(transcript, true);
                        } else {
                            this.attemptsForCurrentQuestion++;
                            if (this.attemptsForCurrentQuestion < 3) {
                                this.quizStatus = `Jawabannya hampir benar! Coba lagi ya!`;
                                await this.speakTTS("Hampir tepat. Coba katakan sekali lagi.");
                                this.startListeningSession();
                            } else {
                                this.quizStatus = `Salah. Jawaban benar adalah ${this.currentQuestion.jawaban_benar}.`;
                                await this.speakTTS(`Belum tepat. Jawaban yang benar adalah ${this.currentQuestion.jawaban_benar}.`);
                                this.submitQuizAnswer(transcript, false);
                            }
                        }
                    };

                    try {
                        this.recognizer.start();
                    } catch(err) {
                        console.error('Error starting recognition:', err);
                    }
                },

                async selectOptionFallback(opt) {
                    if (this.recognizer) {
                        try {
                            this.recognizer.abort();
                        } catch(e) {}
                    }
                    this.listening = false;

                    const cleanAns = this.normalize(opt);
                    const cleanCorrect = this.normalize(this.currentQuestion.jawaban_benar);

                    if (cleanAns === cleanCorrect) {
                        this.totalCorrect++;
                        this.correctCountForCurrentLesson++;
                        this.quizStatus = 'Hebat! Jawabanmu benar! 🎉';
                        await this.speakTTS("Hebat! Jawabanmu benar!");
                        this.submitQuizAnswer(opt, true);
                    } else {
                        this.attemptsForCurrentQuestion++;
                        if (this.attemptsForCurrentQuestion < 3) {
                            this.quizStatus = `Jawabannya hampir benar! Coba lagi ya!`;
                            await this.speakTTS("Belum tepat. Mari coba pilih lagi.");
                        } else {
                            this.quizStatus = `Salah. Jawaban benar adalah ${this.currentQuestion.jawaban_benar}.`;
                            await this.speakTTS(`Belum tepat. Jawaban yang benar adalah ${this.currentQuestion.jawaban_benar}.`);
                            this.submitQuizAnswer(opt, false);
                        }
                    }
                },

                normalize(text) {
                    const numberMap = {
                        '1':'satu','2':'dua','3':'tiga','4':'empat','5':'lima',
                        '6':'enam','7':'tujuh','8':'delapan','9':'sembilan','10':'sepuluh'
                    };
                    let cleaned = text.toLowerCase().trim().replace(/[^a-z0-9]/g, '');
                    return numberMap[cleaned] || cleaned;
                },

                submitQuizAnswer(userAnswer, isCorrect) {
                    const elapsed = Math.round((Date.now() - this.timerStart) / 1000);
                    // Skor bertahap: percobaan ke-1=100, ke-2=70, ke-3=40, gagal=0
                    let calculatedScore = 0;
                    if (isCorrect) {
                        if (this.attemptsForCurrentQuestion <= 1) calculatedScore = 100;
                        else if (this.attemptsForCurrentQuestion === 2) calculatedScore = 70;
                        else calculatedScore = 40;
                    }
                    this.totalQuestions++;

                    fetch('{{ route('play.quiz.submit') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            child_id: this.childId,
                            lesson_id: this.currentLesson.id,
                            quiz_question_id: this.currentQuestion.id,
                            jawaban_anak: userAnswer,
                            benar: isCorrect ? 1 : 0,
                            skor: calculatedScore,
                            percobaan: this.attemptsForCurrentQuestion,
                            waktu_detik: elapsed
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        // Gather earned badges if any
                        if (data.new_badges && data.new_badges.length > 0) {
                            this.earnedBadges = [...this.earnedBadges, ...data.new_badges];
                        }
                    });

                    this.quizFinished = true;

                    // Auto-advance ke soal berikutnya setelah jeda singkat
                    const delay = isCorrect ? 2000 : 2800;
                    setTimeout(() => {
                        if (this.quizFinished) {
                            this.nextSlide();
                        }
                    }, delay);
                },

                nextSlide() {
                    this.stopActiveAudio();
                    // Guard: cegah double-advance dari timer otomatis + klik manual
                    if (!this.quizFinished && this.currentState === 'quiz') return;
                    this.quizFinished = false; // reset agar timer duplikat tidak aktif lagi
                    this.questionIndex++;
                    if (this.questionIndex < this.questionsList.length) {
                        this.loadQuestion();
                    } else {
                        this.nextLessonOrFinish();
                    }
                },

                nextLessonOrFinish() {
                    this.stopActiveAudio();
                    this.lessonIndex++;
                    if (this.lessonIndex < this.lessons.length) {
                        this.currentState = 'next_lesson_prompt';
                        this.speakTTS("Pelajaran berikutnya sudah siap. Silakan tekan tombol besar di tengah layar untuk memulai cerita berikutnya.");
                    } else {
                        this.finishAdventure();
                    }
                },

                finishAdventure() {
                    this.stopActiveAudio();
                    this.currentState = 'victory';
                    this.totalScore = this.totalQuestions > 0 ? Math.round((this.totalCorrect / this.totalQuestions) * 100) : 0;
                    
                    const stars = this.getStarsCount();
                    let endText = `Hore! Petualangan selesai! Kamu mendapatkan skor ${this.totalScore} dan mendapat ${stars} bintang!`;
                    if (this.earnedBadges.length > 0) {
                        endText += ` Kamu juga mendapatkan lencana baru!`;
                    }
                    this.speakTTS(endText);
                },

                getStarsCount() {
                    if (this.totalScore >= 100) return 3;
                    if (this.totalScore >= 70) return 2;
                    if (this.totalScore >= 40) return 1;
                    return 0;
                },

                getStarsEmoji() {
                    const stars = this.getStarsCount();
                    return '⭐'.repeat(stars) + '⏳'.repeat(Math.max(0, 3 - stars));
                }
            }));
        });
    </script>
@endsection
