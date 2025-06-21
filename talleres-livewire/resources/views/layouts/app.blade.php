<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi App</title>

    @vite(['resources/css/app.css', 'resources/js/app.js']) {{-- ✅ carga Tailwind --}}
    @livewireStyles
</head>
<body>

    @yield('content')

    @livewireScripts
</body>
</html>