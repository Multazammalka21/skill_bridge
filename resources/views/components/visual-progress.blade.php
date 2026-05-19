{{-- Visual World: Progress Bar — NO AUDIO, WCAG AA contrast --}}
@props(['completed' => 0, 'total' => 1])

<div x-data="visualProgress({{ $completed }}, {{ $total }})" class="visual-progress" role="region" aria-label="Progress belajar visual" aria-live="polite">
    <style>
        .visual-progress{width:100%;max-width:600px;margin:1rem auto;padding:1rem}
        .visual-progress .bar-bg{width:100%;height:32px;background:rgba(255, 107, 53, 0.1);border-radius:16px;overflow:hidden;position:relative}
        .visual-progress .bar-fill{height:100%;background:linear-gradient(90deg,var(--accent),var(--accent-hover, #ff8c5a));transition:width .6s ease;border-radius:16px}
        .visual-progress .percentage{position:absolute;top:0;left:50%;transform:translateX(-50%);width:100%;height:100%;display:flex;align-items:center;justify-content:center;font-size:1.25rem;font-weight:700;color:#fff;pointer-events:none;text-shadow: 0 2px 4px rgba(0,0,0,0.2);}
        .visual-progress .stars{margin-top:1rem;text-align:center;font-size:2rem;color:var(--accent-3);letter-spacing:.2em;}
        .visual-progress .label-text{text-align:center;margin-top:1rem;font-size:1.25rem;font-weight:700;color:var(--text);}
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
