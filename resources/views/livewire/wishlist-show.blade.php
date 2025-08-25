<div class="pt-3 md:pt-12 bg-[#f8f2e8f2] antialiased" wire:loading.class="cursor-wait">
    <x-slot name="header">
        <h1 class="font-semibold text-2xl text-gray-800 leading-tight flex items-center">
        Wunschliste von {{ $user->name }}
        </h1>
    </x-slot>
    <div class="max-w-7xl mx-auto px-5 pb-12">
        <div class="">
            @if ($wishlistItems->isEmpty())
                <p class="mt-4 text-gray-600">Diese Wunschliste ist leer.</p>
            @else
                <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach ($wishlistItems as $item)
                        <x-productlist-item :product="$item" />
                    @endforeach
                </div>
            @endif
        </div>
    </div>
    <!-- Features Banner -->
    <x-features-banner />
</div>