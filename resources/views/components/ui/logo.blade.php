@props([
    'sidebar' => false,
])

@if($sidebar)
    <flux:sidebar.brand name="SIG Learning" {{ $attributes }}>
        <x-slot name="logo" class="flex aspect-square size-8 items-center justify-center rounded-md bg-red-600 text-white">
            <x-ui.logo-icon class="size-5 fill-current" />
        </x-slot>
    </flux:sidebar.brand>
@else
    <flux:brand name="SIG Learning" {{ $attributes }}>
        <x-slot name="logo" class="flex aspect-square size-8 items-center justify-center rounded-md bg-red-600 text-white">
            <x-ui.logo-icon class="size-5 fill-current" />
        </x-slot>
    </flux:brand>
@endif
