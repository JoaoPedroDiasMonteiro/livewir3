<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🎉 WireWire 🎉</title>

    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
</head>

<body class="bg-gray-200 flex flex-col min-h-screen gap-10">
    <header class="bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 h-20">

    </header>

    <main class="container mx-auto">
        {{ $slot }}
    </main>

    <footer class="bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 mt-auto h-10">
    </footer>
</body>

</html>
