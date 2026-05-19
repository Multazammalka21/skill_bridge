{{-- Visual World: Lesson — NO AUDIO, WCAG AA contrast, 80×80px buttons --}}
@props(['judul', 'gambar', 'animasiLottie' => null, 'teksKeterangan'])

<div class="visual-lesson" role="region" aria-label="Pelajaran visual: {{ $judul }}">
    <style>
        .visual-lesson{display:flex;flex-direction:column;align-items:center;gap:1.25rem;padding:1.5rem;background:#fff;color:#1a1a2e}
        .visual-lesson .title{font-size:2rem;font-weight:800;text-align:center;color:#1a1a2e}
        .visual-lesson .media{width:100%;max-height:60vh;display:flex;align-items:center;justify-content:center;border-radius:12px;overflow:hidden;background:#f0f0f5}
        .visual-lesson .media img{max-width:100%;max-height:100%;object-fit:contain}
        .visual-lesson .lottie-box{width:100%;min-height:300px;display:flex;align-items:center;justify-content:center;color:#4a4a6a;font-size:1.1rem;text-align:center;padding:2rem}
        .visual-lesson .caption{font-size:1.5rem;font-weight:600;color:#2d2d4e;text-align:center;max-width:90%;line-height:1.5}
        .visual-lesson .nav-btn{min-width:80px;min-height:80px;padding:1rem 2rem;font-size:1.125rem;font-weight:700;color:#fff;background:linear-gradient(135deg,#2563eb,#1d4ed8);border:none;border-radius:12px;cursor:pointer;transition:transform .15s,box-shadow .2s;font-family:inherit;margin-top:1rem}
        .visual-lesson .nav-btn:hover,.visual-lesson .nav-btn:focus-visible{transform:translateY(-2px);box-shadow:0 6px 20px rgba(37,99,235,.3);outline:3px solid #60a5fa;outline-offset:2px}
    </style>

    <h2 class="title">{{ $judul }}</h2>

    <div class="media" aria-label="Media pelajaran visual">
        @if($animasiLottie)
            <div class="lottie-box" x-data="lazyLottie({{ \Illuminate\Support\Js::from($animasiLottie) }})" x-ref="lottieContainer" aria-label="Animasi pelajaran {{ $judul }}"></div>
        @else
            <img src="{{ $gambar }}" alt="Gambar pelajaran: {{ $judul }}" loading="lazy" />
        @endif
    </div>

    <p class="caption" aria-label="Deskripsi pelajaran">{{ $teksKeterangan }}</p>

    <button class="nav-btn" @click="$dispatch('next-lesson')" aria-label="Lanjut ke pelajaran berikutnya" id="btn-visual-next">Lanjut →</button>
</div>

<script>
function lazyLottie(path){return{loaded:false,init(){const obs=new IntersectionObserver(e=>{e.forEach(en=>{if(en.isIntersecting&&!this.loaded){this.loaded=true;this.load(path);obs.disconnect()}})},{threshold:.1});obs.observe(this.$refs.lottieContainer)},async load(p){if(!window.lottie){const s=document.createElement('script');s.src='https://cdnjs.cloudflare.com/ajax/libs/lottie-web/5.12.2/lottie.min.js';s.onload=()=>this.render(p);document.head.appendChild(s)}else this.render(p)},render(p){if(window.lottie&&this.$refs.lottieContainer)lottie.loadAnimation({container:this.$refs.lottieContainer,renderer:'svg',loop:true,autoplay:true,path:p})}}}
</script>
