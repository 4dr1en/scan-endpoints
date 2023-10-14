<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
	<div class="container">
		<livewire:workspace-list/>
		<livewire:workspace-new/>
	</div>
</x-app-layout>