<header class="app-header">
    <div class="app-header__wrapper">
        <div>
            <a href="/">
                upcheckr.io
            </a>
        </div>
        <div class="app-header__greeting">
            {{ __('Hello :name!', ['name' => Auth::user()->display_name ?: Auth::user()->first_name]) }}
        </div>
        <div class="app-header__menu">
            @include('layouts.navigation')
        </div>
    </div>
</header>
