<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'E-Learning SMK taruna Bangsa')</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }

        .bg-pattern {
            background-color: #0f172a;
            background-image: radial-gradient(at 20% 50%, hsla(221, 100%, 27%, 0.4) 0px, transparent 50%),
                radial-gradient(at 80% 20%, hsla(217, 91%, 60%, 0.2) 0px, transparent 50%),
                radial-gradient(at 60% 80%, hsla(213, 100%, 25%, 0.3) 0px, transparent 50%);
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
    </style>
</head>

<body class="bg-pattern min-h-screen flex items-center justify-center p-4">
    @yield('content')
</body>

</html>