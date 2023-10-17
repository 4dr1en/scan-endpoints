<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@1/css/pico.min.css">
    <!-- Scripts -->
    @vite(['resources/css/app.scss', 'resources/js/app.js', 'resources/js/homepage.js'])
</head>

<body class="">
    <div class="">
        <header class="homepage-header">
            <nav class="container homepage-navigation">
                <ul class="homepage-navigation__list">
                    @auth()
                        <li class="homepage-navigation__item">
                            <a href="{{ route('targets-monitored.index') }}">Targets</a>
                        </li>
                        <li class="homepage-navigation__item homepage-navigation__item--secondary"><a
                                href="{{ route('workspace.index') }}">Workspaces</a></li>
                    @endauth
                    @guest()
                        <li class="homepage-navigation__item"><a href="{{ route('register') }}">Register</a></li>
                        <li class="homepage-navigation__item homepage-navigation__item--secondary"><a
                                href="{{ route('login') }}">Login</a></li>
                    @endguest
                </ul>
            </nav>
        </header>

        <main>
            {{ $slot }}
        </main>
    </div>
</body>

</html>
