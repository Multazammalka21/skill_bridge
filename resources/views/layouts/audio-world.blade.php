<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Audio World - Skill Bridge Kids</title>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        :root {
            --audio-bg: #0d0d1a;
            --audio-text: #f0f0f0;
            --audio-accent: #7c6af7;
            --btn-size: 80px;
        }

        body {
            margin: 0;
            padding: 0;
            background-color: var(--audio-bg);
            color: var(--audio-text);
            font-family: sans-serif;
            font-size: 1.25rem;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        main {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 1rem;
        }
    </style>
</head>
<body>
    <main>
        @yield('content')
    </main>
</body>
</html>
