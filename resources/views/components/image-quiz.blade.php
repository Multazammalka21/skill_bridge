{{-- Visual World: Image Quiz — NO AUDIO, WCAG AA contrast, 80×80px buttons --}}
@props(['pertanyaan', 'pilihan'])

<div x-data="imageQuiz()" class="image-quiz" role="region" aria-label="Kuis gambar" aria-live="polite">
    <style>
        .image-quiz{max-width:100%;margin:2rem auto;padding:1rem;background:#fff;color:#1a1a2e}
        .image-quiz .question{font-size:1.5rem;font-weight:700;text-align:center;margin-bottom:1.5rem;color:#1a1a2e}
        .image-quiz .grid{display:grid;grid-template-columns:repeat(2,minmax(140px,1fr));gap:1rem}
        .image-quiz .card{position:relative;border:3px solid #e2e8f0;border-radius:12px;overflow:hidden;cursor:pointer;transition:transform .2s,border-color .2s;background:#f8fafc;min-height:80px}
        .image-quiz .card:hover,.image-quiz .card:focus-visible{transform:translateY(-2px);border-color:#2563eb;outline:none}
        .image-quiz .card img{width:100%;height:140px;object-fit:cover}
        .image-quiz .card .label{padding:.75rem;text-align:center;font-weight:600;color:#2d2d4e;font-size:1rem}
        .image-quiz .card.benar{border-color:#16a34a;background:#f0fdf4}
        .image-quiz .card.salah{border-color:#dc2626;background:#fef2f2}
        .image-quiz .shake{animation:iq-shake .5s}
        @keyframes iq-shake{0%{transform:translateX(0)}25%{transform:translateX(-5px)}50%{transform:translateX(5px)}75%{transform:translateX(-5px)}100%{transform:translateX(0)}}
        .image-quiz .feedback{text-align:center;font-size:1.25rem;font-weight:700;margin-top:1rem;min-height:2rem;color:#1a1a2e}
        .image-quiz .nav-btn{min-width:80px;min-height:80px;padding:1rem 2rem;font-size:1.125rem;font-weight:700;color:#fff;background:linear-gradient(135deg,#16a34a,#15803d);border:none;border-radius:12px;cursor:pointer;transition:transform .15s,box-shadow .2s;margin-top:1.5rem;display:block;margin-left:auto;margin-right:auto;font-family:inherit}
        .image-quiz .nav-btn:hover,.image-quiz .nav-btn:focus-visible{transform:translateY(-2px);box-shadow:0 6px 20px rgba(22,163,74,.3);outline:3px solid #4ade80;outline-offset:2px}
    </style>

    <div class="question" aria-label="Pertanyaan kuis gambar">{{ $pertanyaan }}</div>

    <div class="grid" role="radiogroup" aria-label="Pilihan jawaban kuis gambar">
        @foreach($pilihan as $index => $item)
            <div
                class="card"
                role="radio"
                tabindex="0"
                :class="dipilih === {{ $index }} ? (status === 'benar' ? 'benar' : 'salah') : ''"
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
        class="nav-btn"
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
