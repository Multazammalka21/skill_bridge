{{-- Visual World: Lesson — NO AUDIO, WCAG AA contrast, 80×80px buttons --}}
@props(['judul', 'gambar', 'animasiLottie' => null, 'teksKeterangan'])

<div class="visual-lesson lesson-card animate-slide-up" role="region" aria-label="Pelajaran visual: {{ $judul }}">
    <style>
        .visual-lesson{display:flex;flex-direction:column;align-items:center;gap:1.5rem;text-align:center;}
        .visual-lesson .title{font-size:2.5rem;font-weight:800;color:var(--text);margin-bottom:1rem;}
        .visual-lesson .media{width:100%;max-height:60vh;display:flex;align-items:center;justify-content:center;border-radius:16px;overflow:hidden;background:rgba(255, 107, 53, 0.05);}
        .visual-lesson .media img{max-width:100%;max-height:100%;object-fit:contain;}
        .visual-lesson .lottie-box{width:100%;min-height:300px;display:flex;align-items:center;justify-content:center;padding:2rem;}
        .visual-lesson .caption{font-size:1.75rem;font-weight:600;color:var(--text);max-width:90%;line-height:1.5;margin-bottom:1rem;}
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

    <button class="btn" @click="$dispatch('next-lesson')" aria-label="Lanjut ke pelajaran berikutnya" id="btn-visual-next">Lanjut →</button>
</div>

<script>
function lazyLottie(path){return{loaded:false,init(){const obs=new IntersectionObserver(e=>{e.forEach(en=>{if(en.isIntersecting&&!this.loaded){this.loaded=true;this.load(path);obs.disconnect()}})},{threshold:.1});obs.observe(this.$refs.lottieContainer)},async load(p){if(!window.lottie){const s=document.createElement('script');s.src='https://cdnjs.cloudflare.com/ajax/libs/lottie-web/5.12.2/lottie.min.js';s.onload=()=>this.render(p);document.head.appendChild(s)}else this.render(p)},render(p){if(window.lottie&&this.$refs.lottieContainer)lottie.loadAnimation({container:this.$refs.lottieContainer,renderer:'svg',loop:true,autoplay:true,path:p})}}}
</script>
