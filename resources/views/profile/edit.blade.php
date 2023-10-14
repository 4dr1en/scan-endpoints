<x-app-layout>
    <x-slot name="header">
        <h2>
            {{ __('Profile') }}
        </h2>
    </x-slot>
    <div class="container">
        <div>
            @include('profile.partials.logout-user-form')
        </div>
        <hr>
        <div class="">
            @include('profile.partials.update-profile-information-form')
        </div>
        <hr>
        <div class="">
            @include('profile.partials.update-password-form')
        </div>
        <hr>
        <div class="">
            @include('profile.partials.delete-user-form')
        </div>
    </div>
</x-app-layout>
