{{-- Visual World: Progress Bar — NO AUDIO, WCAG AA contrast --}}
@props(['completed' => 0, 'total' => 1])

<div x-data="visualProgress({{ $completed }}, {{ $total }})" class="visual-progress" role="region" aria-label="Progress belajar visual" aria-live="polite">
    <style>
        .visual-progress{width:100%;max-width:600px;margin:1rem auto;padding:1rem}
        .visual-progress .bar-bg{width:100%;height:28px;background:#e2e8f0;border-radius:14px;overflow:hidden;position:relative}
        .visual-progress .bar-fill{height:100%;background:linear-gradient(90deg,#2563eb,#3b82f6);transition:width .6s ease;border-radius:14px}
        .visual-progress .percentage{position:absolute;top:0;left:50%;transform:translateX(-50%);width:100%;height:100%;display:flex;align-items:center;justify-content:center;font-size:1rem;font-weight:700;color:#1a1a2e;pointer-events:none}
        .visual-progress .stars{margin-top:.75rem;text-align:center;font-size:1.4rem;color:#ca8a04;letter-spacing:.2em}
        .visual-progress .label-text{text-align:center;margin-top:.5rem;font-size:.9rem;font-weight:600;color:#2d2d4e}
    </style>

    <div class="bar-bg" role="progressbar" aria-valuenow="{{ $completed }}" aria-valuemin="0" aria-valuemax="{{ $total }}" aria-label="Progress: {{ $completed }} dari {{ $total }} pelajaran selesai">
        <div class="bar-fill" :style="`width: ${pct}%`"></div>
        <div class="percentage" x-text="`${Math.round(pct)}%`"></div>
    </div>
    <div class="stars" x-html="starHtml" aria-label="Bintang progress"></div>
    <p class="label-text" aria-label="Detail progress">{{ $completed }} dari {{ $total }} pelajaran selesai</p>

    <script>
        function visualProgress(c,t){return{pct:t?(c/t)*100:0,starHtml:'',init(){let s='';for(let i=0;i<Math.min(c,t);i++)s+='★';for(let i=Math.min(c,t);i<t;i++)s+='☆';this.starHtml=s}}}
    </script>
</div>
