<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard') | Observatorium Langit Gelap</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@600;700&family=Instrument+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/observatorium.css') }}">
</head>
<body>
<div class="app">
    @include('partials.sidebar')
    <main class="main" id="konten">
        @include('partials.flash')
        @yield('content')
    </main>
</div>
<script src="{{ asset('js/app.js') }}"></script>
@stack('scripts')
</body>
</html>
