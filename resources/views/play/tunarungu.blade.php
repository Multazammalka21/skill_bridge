@extends('layouts.visual-world')

@section('content')
    <!-- Canvas Confetti CDN -->
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>

    <div x-data="visualWorldApp()" class="w-full" style="width: 100%;">
        <!-- Dynamic Progress Indicator -->
        <div class="visual-progress animate-slide-up" style="display: flex; gap: 8px; justify-content: center; margin-bottom: 2rem;" x-show="currentState !== 'welcome' && currentState !== 'victory'">
            <template x-for="n in totalStepsCount">
                <div 
                    style="height: 12px; border-radius: 6px; flex: 1; max-width: 80px; transition: background-color 0.3s;"
                    :style="n <= currentStepIndex + 1 ? 'background-color: #ff6b35;' : 'background-color: rgba(255,255,255,0.15);'"
                ></div>
            </template>
        </div>

        <!-- 1. Onboarding/Welcome Screen -->
        <template x-if="currentState === 'welcome'">
            <div class="lesson-card animate-slide-up" style="text-align: center; padding: 3rem; display: flex; flex-direction: column; align-items: center; gap: 2rem;">
                <h1 style="color: #ff6b35; font-size: 3rem; font-family: 'Fredoka', sans-serif;">👁️ Dunia Visual Pinteria</h1>
                <p style="font-size: 1.5rem; color: var(--text); text-align: center; max-width: 600px; font-weight: 600;">
                    Selamat datang! Mari melihat gambar-gambar yang indah dan ikuti kuis seru hari ini!
                </p>
                <button 
                    class="btn animate-pulse" 
                    @click="startAdventure()"
                    style="font-size: 2.2rem; min-height: 90px; padding: 0 50px; background: #ff6b35; color: white; box-shadow: 0 8px 24px rgba(255,107,53,0.4);"
                >
                    Mulai Belajar 🚀
                </button>
            </div>
        </template>

        <!-- 2. Visual Lesson Presentation -->
        <template x-if="currentState === 'lesson'">
            <div class="visual-lesson lesson-card animate-slide-up" style="display: flex; flex-direction: column; align-items: center; gap: 2rem; text-align: center;">
                <h2 class="title" style="font-size: 2.5rem; font-weight: 800; color: var(--text);" x-text="currentLesson.judul"></h2>

                <!-- Render media -->
                <div class="media" style="width: 100%; max-height: 45vh; display: flex; align-items: center; justify-content: center; border-radius: 16px; overflow: hidden; background: rgba(255, 107, 53, 0.05); border: 2px solid rgba(255, 107, 53, 0.15);">
                    <img 
                        :src="currentLesson.gambar || '/images/placeholder.png'" 
                        :alt="currentLesson.judul" 
                        style="max-width: 100%; max-height: 350px; object-fit: contain; padding: 10px;"
                    />
                </div>

                <p class="caption" style="font-size: 1.75rem; font-weight: 600; color: var(--text); line-height: 1.5;" x-text="currentLesson.deskripsi"></p>

                <button 
                    class="btn" 
                    @click="startQuiz()"
                    style="min-height: 80px; width: 220px; font-size: 1.5rem; background: #ff6b35; color: white;"
                >
                    Mulai Kuis ➡️
                </button>
            </div>
        </template>

        <!-- 3. Visual Image Quiz Option -->
        <template x-if="currentState === 'quiz'">
            <div class="image-quiz lesson-card animate-slide-up" style="display: flex; flex-direction: column; align-items: center; gap: 2rem; width: 100%;">
                <div class="question" style="font-size: 2.2rem; font-weight: 800; color: var(--text); text-align: center;" x-text="currentQuestion.pertanyaan"></div>

                <!-- Display question image if available -->
                <template x-if="currentQuestion.gambar">
                    <div style="width: 100%; max-width: 300px; margin: 0 auto; border-radius: 16px; overflow: hidden; background: rgba(255, 255, 255, 0.05); padding: 10px; border: 1px solid rgba(255,255,255,0.1);">
                        <img :src="currentQuestion.gambar" alt="Gambar Soal" style="width: 100%; max-height: 200px; object-fit: contain;">
                    </div>
                </template>

                <!-- Grid of Multiple Choice Option Cards -->
                <div class="grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1.5rem; width: 100%; margin-top: 1rem;">
                    <template x-for="(opt, idx) in currentQuestion.pilihan" :key="idx">
                        <div
                            class="card lesson-card choice-card cursor-pointer"
                            style="text-align: center; padding: 20px; position: relative;"
                            :style="getSelectedCardStyle(idx)"
                            @click="selectOption(idx, opt)"
                        >
                            <!-- Feedback symbols -->
                            <div 
                                style="position: absolute; top: 10px; right: 10px; font-size: 24px;"
                                x-html="getFeedbackSymbol(idx)"
                            ></div>

                            <div style="font-size: 1.6rem; font-weight: bold; color: var(--text); padding: 15px 5px;" x-text="opt"></div>
                        </div>
                    </template>
                </div>

                <p class="feedback" style="font-size: 1.8rem; font-weight: 800; min-height: 2.5rem; text-align: center;" :style="isAnswerCorrect ? 'color: #4caf50;' : 'color: #ff5252;'" x-text="feedbackMsg"></p>

                <button
                    class="btn"
                    x-show="quizFinished"
                    @click="nextSlide()"
                    style="min-height: 80px; width: 220px; font-size: 1.5rem; background: #ff6b35; color: white;"
                >
                    Lanjut ➡️
                </button>
            </div>
        </template>

        <!-- 4. Victory / Results Screen -->
        <template x-if="currentState === 'victory'">
            <div class="lesson-card animate-slide-up" style="display: flex; flex-direction: column; align-items: center; gap: 2rem; padding: 3rem; text-align: center;">
                <h1 style="color: #ffd600; font-size: 3.5rem; font-family: 'Fredoka', sans-serif;">🎉 Petualangan Selesai! 🎉</h1>
                
                <div style="font-size: 5rem; letter-spacing: 10px; margin: 1rem 0;" x-text="getStarsEmoji()"></div>
                
                <div style="background: rgba(255, 255, 255, 0.05); padding: 2rem; border-radius: 20px; border: 1px solid rgba(255, 107, 53, 0.2); width: 100%; max-width: 500px;">
                    <h3 style="font-size: 2rem; color: var(--text); margin-bottom: 1rem;">Skor Kamu</h3>
                    <h2 style="font-size: 4rem; color: #ffd600; margin: 0;" x-text="totalScore + '%'"></h2>
                    <p style="font-size: 1.4rem; color: #888; margin-top: 1rem;" x-text="'Benar ' + totalCorrect + ' dari ' + totalQuestions + ' soal'"></p>
                </div>

                <!-- Newly Earned Badges -->
                <div x-show="earnedBadges.length > 0" style="width: 100%; max-width: 500px; margin-top: 1rem;">
                    <h3 style="font-size: 1.8rem; color: #ffd600; margin-bottom: 1rem;">🏆 Lencana Baru Diraih!</h3>
                    <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
                        <template x-for="badge in earnedBadges" :key="badge.id">
                            <div style="background: rgba(255,107,53,0.15); border: 2px solid #ff6b35; padding: 15px; border-radius: 16px; min-width: 120px; text-align: center;">
                                <div style="font-size: 3rem; margin-bottom: 5px;" x-text="badge.ikon"></div>
                                <div style="font-weight: bold; color: var(--text); font-size: 1.2rem;" x-text="badge.nama"></div>
                            </div>
                        </template>
                    </div>
                </div>

                <div style="display: flex; gap: 1.5rem; width: 100%; justify-content: center; margin-top: 2rem;">
                    <button 
                        @click="window.location.href='/dashboard'"
                        class="btn"
                        style="min-height: 80px; background: rgba(255,255,255,0.08); border: 2px solid rgba(255,255,255,0.15); color: var(--text);"
                        aria-label="Kembali ke halaman dashboard utama"
                    >
                        🏠 Dashboard
                    </button>
                    <button 
                        @click="location.reload()"
                        class="btn"
                        style="min-height: 80px; background: #ff6b35; color: white;"
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
            Alpine.data('visualWorldApp', () => ({
                currentState: 'welcome', // welcome, lesson, quiz, victory
                childId: @json($child->id),
                lessons: @json($lessons),
                
                lessonIndex: 0,
                questionIndex: 0,
                
                currentLesson: null,
                currentQuestion: null,
                questionsList: [],
                
                // Navigation/Progress tracking
                totalStepsCount: 0,
                currentStepIndex: 0,

                // Quiz selection states
                selectedOptionIndex: null,
                isAnswerCorrect: false,
                feedbackMsg: '',
                quizFinished: false,

                // Quiz session metrics
                totalCorrect: 0,
                totalQuestions: 0,
                totalScore: 0,
                earnedBadges: [],
                correctCountForCurrentLesson: 0,
                attemptsForCurrentQuestion: 0,
                timerStart: null,

                init() {
                    // Precalculate steps (Lessons + Questions)
                    let total = 0;
                    this.lessons.forEach(l => {
                        total += 1; // Narrator lesson card step
                        if (l.quiz_questions) {
                            total += l.quiz_questions.length;
                        }
                    });
                    this.totalStepsCount = total;
                },

                startAdventure() {
                    if (this.lessons.length === 0) {
                        alert("Maaf, belum ada petualangan kuis untukmu hari ini. Silakan hubungi admin.");
                        return;
                    }
                    this.lessonIndex = 0;
                    this.currentState = 'lesson';
                    this.currentStepIndex = 0;
                    this.loadLesson();
                },

                loadLesson() {
                    this.currentLesson = this.lessons[this.lessonIndex];
                    this.questionsList = this.currentLesson.quiz_questions || [];
                    this.questionIndex = 0;
                    this.correctCountForCurrentLesson = 0;
                },

                startQuiz() {
                    if (this.questionsList.length === 0) {
                        this.nextLessonOrFinish();
                        return;
                    }
                    this.currentState = 'quiz';
                    this.currentStepIndex++;
                    this.loadQuestion();
                },

                loadQuestion() {
                    this.currentQuestion = this.questionsList[this.questionIndex];
                    this.selectedOptionIndex = null;
                    this.isAnswerCorrect = false;
                    this.feedbackMsg = '';
                    this.quizFinished = false;
                    this.attemptsForCurrentQuestion = 0;
                    this.timerStart = Date.now();
                },

                selectOption(idx, optionLabel) {
                    if (this.quizFinished) return;
                    
                    this.selectedOptionIndex = idx;
                    const correctAns = this.currentQuestion.jawaban_benar.toLowerCase().trim();
                    const cleanOpt = optionLabel.toLowerCase().trim();
                    
                    if (cleanOpt === correctAns) {
                        this.isAnswerCorrect = true;
                        this.totalCorrect++;
                        this.correctCountForCurrentLesson++;
                        this.feedbackMsg = '✅ Benar! Kamu Hebat!';
                        this.attemptsForCurrentQuestion++;
                        
                        // Fire confetti!
                        if (window.confetti) {
                            confetti({
                                particleCount: 120,
                                spread: 80,
                                origin: { y: 0.6 }
                            });
                        }
                        
                        this.submitQuizAnswer(optionLabel, true);
                    } else {
                        this.isAnswerCorrect = false;
                        this.attemptsForCurrentQuestion++;
                        
                        if (this.attemptsForCurrentQuestion < 3) {
                            this.feedbackMsg = '❌ Coba lagi! Pasti kamu bisa!';
                        } else {
                            this.feedbackMsg = `❌ Salah. Jawaban benar adalah: ${this.currentQuestion.jawaban_benar}`;
                            this.submitQuizAnswer(optionLabel, false);
                        }
                    }
                },

                getSelectedCardStyle(idx) {
                    if (this.selectedOptionIndex === idx) {
                        if (this.isAnswerCorrect) {
                            return 'border-color: #4caf50; background: rgba(76,175,80,0.1); box-shadow: 0 0 20px rgba(76,175,80,0.4);';
                        } else {
                            return 'border-color: #ff5252; background: rgba(255,82,82,0.1); animation: shake 0.5s;';
                        }
                    }
                    return '';
                },

                getFeedbackSymbol(idx) {
                    if (this.selectedOptionIndex === idx) {
                        return this.isAnswerCorrect ? '🟢' : '🔴';
                    }
                    return '';
                },

                submitQuizAnswer(userAnswer, isCorrect) {
                    const elapsed = Math.round((Date.now() - this.timerStart) / 1000);
                    // Skor bertahap: percobaan ke-1=100, ke-2=70, ke-3+=40, gagal=0
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
                        if (data.new_badges && data.new_badges.length > 0) {
                            this.earnedBadges = [...this.earnedBadges, ...data.new_badges];
                        }
                    });

                    this.quizFinished = true;
                },

                nextSlide() {
                    this.questionIndex++;
                    if (this.questionIndex < this.questionsList.length) {
                        this.currentState = 'quiz';
                        this.currentStepIndex++;
                        this.loadQuestion();
                    } else {
                        this.nextLessonOrFinish();
                    }
                },

                nextLessonOrFinish() {
                    this.lessonIndex++;
                    if (this.lessonIndex < this.lessons.length) {
                        this.currentState = 'lesson';
                        this.currentStepIndex++;
                        this.loadLesson();
                    } else {
                        this.finishAdventure();
                    }
                },

                finishAdventure() {
                    this.currentState = 'victory';
                    this.totalScore = this.totalQuestions > 0 ? Math.round((this.totalCorrect / this.totalQuestions) * 100) : 0;
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

    <style>
        .choice-card {
            border: 3px solid rgba(255, 255, 255, 0.1) !important;
            background: rgba(255, 255, 255, 0.03) !important;
            transition: transform 0.2s, border-color 0.2s, box-shadow 0.2s, background-color 0.2s !important;
            border-radius: 20px !important;
        }
        .choice-card:hover {
            transform: translateY(-5px) scale(1.03) !important;
            border-color: #ff6b35 !important;
            box-shadow: 0 10px 20px rgba(255, 107, 53, 0.15) !important;
        }
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-6px); }
            50% { transform: translateX(6px); }
            75% { transform: translateX(-6px); }
        }
    </style>
@endsection
