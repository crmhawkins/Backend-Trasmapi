<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Bootstrap (por si no lo tienes incluido ya con Vite) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Nunito', sans-serif;
        }
        .sidebar {
            height: 100vh;
            background-color: #f8f9fa;
            border-right: 1px solid #dee2e6;
        }
        .sidebar a {
            color: #333;
            padding: 1rem;
            display: block;
            text-decoration: none;
        }
        .sidebar a:hover {
            background-color: #e9ecef;
            color: #000;
        }
        .main-content {
            padding: 2rem;
        }
    </style>
</head>
<body>
<div id="app">
    <div class="d-flex">
        <!-- Sidebar -->
        @auth
        <div class="sidebar p-3">
            <h4 class="mb-4">{{ config('app.name', 'Laravel') }}</h4>

            <a href="{{ route('anuncios.index') }}">📢 Anuncios</a>
            <a href="{{ route('tortuga.index') }}">🐢 Tortugas</a>
            <a href="{{ route('limpieza.index') }}">🕸️ Limpieza</a>
            <a href="{{ route('posidonia.index') }}">🪸 Poseidonias</a>


            <hr>
            <span class="d-block px-3 text-muted mb-2">{{ Auth::user()->name }}</span>
            <form id="logout-form" action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-danger w-100">Cerrar sesión</button>
            </form>
        </div>
    @endauth

        <!-- Main Content -->
        <div class="flex-grow-1">
            <!-- Navbar superior opcional -->
            <nav class="navbar navbar-light bg-white shadow-sm px-4">
                <div class="container-fluid">
                    <span class="navbar-brand mb-0 h5">Panel de Administración</span>
                </div>
            </nav>

            <main class="main-content">
                @yield('content')
            </main>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
