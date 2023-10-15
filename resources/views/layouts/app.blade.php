<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Scan endpoints') }}</title>
    @livewireStyles
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@1/css/pico.min.css">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

    <!-- Scripts -->
    @vite(['resources/css/app.scss', 'resources/js/app.js', 'resources/js/chart.js'])
</head>

<body class="app-body">
    <div class="">
        @include('layouts.header')

        <!-- Page Heading -->
        @if (isset($header))
            <header class="container">
                {{ $header }}
            </header>
        @endif

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>
    @livewireScripts

    <dialog x-data="{ open: false, message: '' }" x-show="open" :open="open"
        @notify.window="open = true; message = $event.detail;">
        <article @click.outside="open = false">
            <a href="#close" aria-label="Close" class="close" @click="open = false"></a>
            <p x-text="message"></p>
        </article>
    </dialog>
</body>

</html>
