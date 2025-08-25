<div class="w-full relative bg-cover bg-center backgroundimageOverlay bg-[#f8f2e8f2] py-10"  wire:loading.class="cursor-wait">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <a 
                    onclick="window.history.back()"  
                    wire:navigate  
                    class="transition-all duration-100 inline-flex items-center px-2 py-1 text-sm bg-gray-100 text-gray-900 rounded hover:bg-gray-200 cursor-pointer"
                            x-data="{ isClicked: false }" 
                            @click="isClicked = true; setTimeout(() => isClicked = false, 100)"
                            style="transform:scale(1);"
                            :style="isClicked ? 'transform:scale(0.7);' : ''"
                    >
                    ← Zurück
                </a>
    <h1 class="text-2xl font-bold mb-6">Meine geliketen Produkte</h1>

    @if (session()->has('success'))
        <div class="bg-green-100 text-green-700 p-4 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if ($likedProducts->isEmpty())
        <p class="text-gray-500">Du hast noch keine Produkte geliket.</p>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach ($likedProducts as $product)
                <div class="bg-white shadow p-5 relative"  wire:key="{{ $product->id }}">
                    <!-- Produktbild -->
                    <img src="{{ url($product->getImageUrl(0,'m')) }}" alt="{{ $product->name }}" class="object-cover mb-4">

                    <!-- Produktdetails -->
                    <h3 class="text-lg font-bold">{{ $product->name }}</h3>
                    <p class="text-gray-500">{{ Str::limit($product->description, 50) }}</p>
                    <h4 class="font-bold mt-4">€{{ number_format($product->price, 2) }}</h4>

                    <!-- Entfernen-Button -->
                    <button 
                        wire:click="removeFromLiked({{ $product->id }})"
                        class="absolute top-4 right-4 bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600"
                    >
                        Entfernen
                    </button>
                </div>
            @endforeach
        </div>
    @endif
    </div>
</div>
