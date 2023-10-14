<nav class="user-nav">
    <ul class="">
        <li>
            <x-responsive-nav-link :href="route('targets-monitored.index')">
                {{ __('Endpoints') }}
            </x-responsive-nav-link>
        </li>
        <li>
            <x-responsive-nav-link :href="route('workspace.index')">
                {{ __('Workspaces') }}
            </x-responsive-nav-link>
        </li>
        <li>
            <x-responsive-nav-link :href="route('profile.edit')">
                {{ __('Profile') }}
            </x-responsive-nav-link>
        </li>
    </ul>
</nav>
