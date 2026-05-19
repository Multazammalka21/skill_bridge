@props(['completed' => 0, 'total' => 1])

<div x-data="visualProgress({{ $completed }}, {{ $total }})" class="visual-progress" aria-live="polite">
    <style>
        .visual-progress {
            width: 100%;
            max-width: 600px;
            margin: 1rem auto;
        }
        .visual-progress .bar-bg {
            width: 100%;
            height: 24px;
            background: #e0e0e0;
            border-radius: 12px;
            overflow: hidden;
            position: relative;
        }
        .visual-progress .bar-fill {
            height: 100%;
            background: linear-gradient(90deg, #4caf50, #81c784);
            transition: width 0.6s ease;
        }
        .visual-progress .percentage {
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            font-weight: 700;
            color: #ffffff;
            pointer-events: none;
        }
        .visual-progress .stars {
            margin-top: 0.5rem;
            text-align: center;
            font-size: 1.2rem;
            color: #ffd700;
        }
    </style>

    <div class="bar-bg" aria-label="Progress bar: {{ $completed }} of {{ $total }} lessons completed">
        <div class="bar-fill" :style="`width: ${percentage}%`"></div>
        <div class="percentage" x-text="`${Math.round(percentage)}%`"></div>
    </div>
    <div class="stars" x-html="starHtml"></div>

    <script>
        function visualProgress(completed, total) {
            return {
                percentage: (total ? (completed / total) * 100 : 0),
                starHtml: '',
                init() {
                    const count = Math.min(completed, total);
                    let stars = '';
                    for (let i = 0; i < count; i++) { stars += '★'; }
                    for (let i = count; i < total; i++) { stars += '☆'; }
                    this.starHtml = stars;
                }
            };
        }
    </script>
</div>
