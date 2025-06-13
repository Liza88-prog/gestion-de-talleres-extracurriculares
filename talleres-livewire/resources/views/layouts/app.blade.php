<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi App de Talleres</title>
    @livewireStyles
</head>
<body>
    <header>
        <h1>Bienvenido a la gestión de talleres</h1>
    </header>

    <main>
        @yield('content')
    </main>

    @livewireScripts
</body>
</html>