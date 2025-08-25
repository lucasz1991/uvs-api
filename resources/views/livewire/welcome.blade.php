<div  wire:loading.class="cursor-wait">
    <!-- Hero Section -->
    <header class="relative bg-cover bg-center" style="background-image: url('{{ asset('site-images/background.webp') }}'); padding: 50px 0;">
        <div class="absolute inset-0 bg-[#f8f2e8e5]"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 text-center text-gray">
            <h1 class="text-4xl font-bold">Willkommen bei MiniFinds</h1>
            <p class="mx-4 text-lg mb-6">Dein Markt für Second-Hand-Kinderschätze</p>
            <x-bannerbuttons />   
        </div>
    </header>
    <x-features-banner />
        @if ($mostViewedProducts->isNotEmpty())
            <!-- Products Section -->
            <section class="py-12 bg-gray-100">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center">
                        <h2 class="text-2xl font-bold text-gray-800">Entdecke unsere beliebtesten Produkte</h2>
                        <p class="mt-4 text-gray-600">Stöbere durch unser vielfältiges Angebot an Kinderschätzen</p>
                    </div>
                    <div class="mt-10 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                        @foreach ($mostViewedProducts as $product)
                        <x-productlist-item :product="$product"  />   
                        @endforeach
                    </div>
                </div>
            </section>
        @endif
    <x-contact-banner />
</div>
