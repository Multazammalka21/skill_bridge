<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visual World - Skill Bridge Kids</title>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.2/dist/confetti.browser.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lottie-web/5.12.2/lottie.min.js"></script>
    <style>
        :root {
            --visual-bg: #fffbf0;
            --visual-primary: #ff6b35;
            --visual-success: #06d6a0;
            --visual-error: #ef233c;
            --card-radius: 20px;
        }

        body {
            margin: 0;
            padding: 0;
            background-color: var(--visual-bg);
            color: #333;
            font-family: 'Comic Sans MS', 'Chalkboard SE', sans-serif; /* Ramah anak */
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        main {
            flex: 1;
            padding: 1rem;
            max-width: 800px;
            margin: 0 auto;
            width: 100%;
            box-sizing: border-box;
        }
    </style>
</head>
<body>
    <main>
        @yield('content')
    </main>
</body>
</html>
