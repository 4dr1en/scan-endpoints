<form method="POST" action="{{ route('logout') }}">
    @csrf
    <x-responsive-nav-link :href="route('logout')" class="btn"
        onclick="event.preventDefault(); this.closest('form').submit();" role="button">
        {{ __('Log Out') }}
    </x-responsive-nav-link>
</form>
