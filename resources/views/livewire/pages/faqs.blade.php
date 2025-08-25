<div class=" pt-3 md:pt-12 bg-[#f8f2e8f2] antialiased" wire:loading.class="cursor-wait">
    <x-slot name="header">
            <h1 class="font-semibold text-2xl text-gray-800 leading-tight flex items-center">
                FAQ's 
              <svg width="80px" class="aspect-square text-[#333] ml-10  inline opacity-30" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                  <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.529 9.988a2.502 2.502 0 1 1 5 .191A2.441 2.441 0 0 1 12 12.582V14m-.01 3.008H12M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
              </svg>  
            </h1>
    </x-slot>
    <div class="max-w-7xl mx-auto px-5 pb-12">
        <div class="bg-white shadow-lg rounded-lg p-6 md:p-10">
            <div class="faq-container ">
                <div class="mb-6">
                    <input type="text" wire:model.live.debounce.250ms="search"  class="p-3 border border-gray-300 rounded-md w-full focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Suche nach einer Frage..." />
                </div>
                <div class="space-y-6">
                    @foreach($faqs as $faq)
                        <div x-data="{ open: false }"  @click.away="open = false" class="faq-item border-b border-gray-200 py-4">
                            <div class="faq-question flex items-center justify-between cursor-pointer text-lg font-semibold text-gray-800" 
                                x-on:click="open = !open" >
                                <span>{{ $faq->key }}</span>
                                <span class="ml-2 text-xl transition-transform transform" x-bind:class="open ? 'rotate-180' : 'rotate-0'">
                                    <svg class="w-4 h-4 ml-2  "  aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m19 9-7 7-7-7"/>
                                    </svg>
                                </span>
                            </div>
                            <div class="faq-answer mt-4" x-show="open" x-cloak x-collapse>
                                <p class="text-gray-600">{{ $faq->value }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
