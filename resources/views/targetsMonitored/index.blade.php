<x-app-layout>
    <x-slot name="header">
        <h2 class="">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="">
        <livewire:endpoint-list/>
    </div>
</x-app-layout>
