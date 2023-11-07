<x-app-layout>
    <x-slot name="header">
        <h2 class="">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
	<div class="container">
		<livewire:workspace-list/>
		<livewire:workspace-new/>
	</div>
</x-app-layout>