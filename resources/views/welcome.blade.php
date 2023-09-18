<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@1/css/pico.min.css">
        <title>Laravel</title>

        <!-- Fonts -->

        <!-- Styles -->
        @livewireStyles
        <style>
        </style>
    </head>
    <body class="antialiased">
        <div>
            <h1>Test</h1>
        </div>
        <livewire:counter/>
        @livewireScripts
    </body>
</html>
