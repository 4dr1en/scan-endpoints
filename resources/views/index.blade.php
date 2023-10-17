<x-homepage-layout>

    <section class="homepage-hero">
        <div class="homepage-hero__wrapper">
            <h1 class="homepage-hero__title" id="animated-title">{{ __('Keep your endpoints in check.') }}</h1>
        </div>
    </section>

    <section class="homepage-feature">
        <hgroup>
            <h2 class="homepage-feature__title">{{ __('Never miss a broken endpoint again.') }}</h2>
            <p class="homepage-feature__description">
                {{ __('Scan endpoint is a simple tool to keep track of your endpoints. It will notify you when an endpoint is broken.') }}
            </p>
        </hgroup>
        <img src="{{ asset('img/homepage1.png') }}" alt="" class="homepage-feature__image">
    </section>

    <section class="homepage-feature homepage-feature--reverse">
        <hgroup>
            <h2 class="homepage-feature__title">
                {{ __('Keep track of your endpoints.') }}
            </h2>
            <p class="homepage-feature__description">
                {{ __('Visualize the long-term health of your network with a complete history of pings.') }}
            </p>
        </hgroup>
        <img src="{{ asset('img/homepage2.png') }}" alt="" class="homepage-feature__image">
    </section>

    <section class="homepage-feature">
        <hgroup>
            <h2 class="homepage-feature__title">
                {{ __('Get organized thank to the workspaces.') }}
            </h2>
            <p class="homepage-feature__description">
                {{ __('Group your endpoints by team, project, or any other way you like.') }}
            </p>
        </hgroup>
        <img src="{{ asset('img/homepage3.png') }}" alt="" class="homepage-feature__image">
    </section>

    <section class="homepage-cta">
        <div class="homepage-cta__wrapper">
            <h2 class="homepage-cta__title">
                {{ __('Get started today.') }}
            </h2>
            <p class="homepage-cta__description">
                {{ __('No credit card required. No strings attached.') }}
            </p>
            <a href="{{ route('register') }}" class="homepage-cta__button">
                {{ __('Create my account') }}
            </a>
        </div>
    </section>
</x-homepage-layout>
