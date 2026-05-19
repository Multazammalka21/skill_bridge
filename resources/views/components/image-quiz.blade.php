{{-- Visual World: Image Quiz — NO AUDIO, WCAG AA contrast, 80×80px buttons --}}
@props(['pertanyaan', 'pilihan'])

<div x-data="imageQuiz()" class="image-quiz" role="region" aria-label="Kuis gambar" aria-live="polite">
    <style>
        .image-quiz{width:100%;text-align:center;}
        .image-quiz .question{font-size:2rem;font-weight:700;margin-bottom:2rem;color:var(--text);}
        .image-quiz .grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:1.5rem;margin-bottom:2rem;}
        .image-quiz .card img{width:100%;height:140px;object-fit:contain;margin-bottom:1rem;}
        .image-quiz .card .label{font-size:1.5rem;font-weight:600;}
        .image-quiz .card.salah{border-color:#ef233c;background:#fff0f2;}
        .image-quiz .shake{animation:iq-shake .5s;}
        @keyframes iq-shake{0%{transform:translateX(0)}25%{transform:translateX(-5px)}50%{transform:translateX(5px)}75%{transform:translateX(-5px)}100%{transform:translateX(0)}}
        .image-quiz .feedback{font-size:1.5rem;font-weight:700;min-height:2rem;margin-bottom:1.5rem;color:var(--text);}
    </style>

    <div class="question" aria-label="Pertanyaan kuis gambar">{{ $pertanyaan }}</div>

    <div class="grid" role="radiogroup" aria-label="Pilihan jawaban kuis gambar">
        @foreach($pilihan as $index => $item)
            <div
                class="card lesson-card animate-slide-up delay-{{ $index + 1 }}"
                role="radio"
                tabindex="0"
                :class="dipilih === {{ $index }} ? (status === 'benar' ? 'card-benar' : 'salah') : ''"
                @click="pilih({{ $index }}, {{ $item['benar'] ? 'true' : 'false' }})"
                @keydown.enter="pilih({{ $index }}, {{ $item['benar'] ? 'true' : 'false' }})"
                @keydown.space.prevent="pilih({{ $index }}, {{ $item['benar'] ? 'true' : 'false' }})"
                x-ref="card{{ $index }}"
                aria-label="Pilihan {{ $index + 1 }}: {{ $item['label'] }}"
                :aria-checked="dipilih === {{ $index }} ? 'true' : 'false'"
            >
                <img src="{{ $item['gambar'] }}" alt="{{ $item['label'] }}" loading="lazy" />
                <div class="label">{{ $item['label'] }}</div>
            </div>
        @endforeach
    </div>

    <p class="feedback" x-text="feedbackMsg" aria-live="assertive" aria-label="Hasil jawaban kuis"></p>

    <button
        class="btn"
        x-show="selesai"
        @click="$dispatch('quiz-selesai')"
        aria-label="Lanjut setelah kuis gambar selesai"
        id="btn-image-quiz-next"
    >Lanjut →</button>
</div>

<script>
function imageQuiz(){return{dipilih:null,status:null,selesai:false,feedbackMsg:'',
pilih(i,benar){if(this.selesai)return;this.dipilih=i;this.status=benar?'benar':'salah';
if(benar){this.feedbackMsg='✅ Benar! Hebat!';this.selesai=true;
if(window.confetti)confetti({particleCount:80,spread:70,origin:{y:.6}})}
else{this.feedbackMsg='❌ Coba lagi!';const c=this.$refs['card'+i];if(c){c.classList.add('shake');setTimeout(()=>c.classList.remove('shake'),600)}}}}}
</script>
