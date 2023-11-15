<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ env('APP_NAME') }} - {{ $title ?? 'Untitle' }}</title>

  <link rel="shortcut icon" type="image/x-icon" href="/img/logo.ico" />

  <!-- Scripts -->
  @vite(['resources/sass/app.scss', 'resources/js/app.js'])

  @stack('styles')
</head>

<body class="d-flex flex-column min-vh-100">
  <x-header />

  <main class="flex-shrink-0">{{ $slot }}</main>

  <x-footer />

  @stack('scripts')
</body>

</html>
