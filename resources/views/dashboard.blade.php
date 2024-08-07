<x-app-layout>
    <x-slot name="header">
        {{ __('Dashboard') }}
    </x-slot>

    <div class="bg-gray-800 shadow sm:rounded-lg">
        <div class="p-6 text-gray-100">
            <h2 class="text-2xl font-semibold">Hoşgeldiniz!</h2>
            <p class="mt-4">{{ __("You're logged in!") }}</p>
        </div>
    </div>
</x-app-layout>
