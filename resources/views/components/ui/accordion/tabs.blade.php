@props([
    /**
     * Array der Tabs, z. B.:
     * ['basic' => 'Basis', 'seo' => 'SEO']
     */
    'tabs' => [],

    /**
     * Default-Tab-Key (fällt sonst auf den ersten Key aus $tabs zurück)
     */
    'default' => null,
])

@php
    $firstKey = array_key_first($tabs);
    $initial  = $default ?? $firstKey ?? 'tab-1';
@endphp

<div
    x-data="{ openTab: '{{ $initial }}' }"
    class="w-full"
    role="tablist"
>
    <!-- Tab-Leiste -->
    <div class="flex -mb-[1px] space-x-2">
        @foreach($tabs as $key => $label)
            <button
                type="button"
                @click="openTab = '{{ $key }}'"
                :class="openTab === '{{ $key }}'
                    ? 'text-blue-600 border-blue-600 bg-gray-100 border-b-0'
                    : 'text-gray-500 bg-white'"
                class="px-4 py-2 text-sm font-medium transition-all border border-gray-300 rounded-t-lg"
                role="tab"
                :aria-selected="openTab === '{{ $key }}'"
                :tabindex="openTab === '{{ $key }}' ? 0 : -1"
            >
                {{ $label }}
            </button>
        @endforeach
    </div>

    <!-- Panels (Slot-Inhalte) -->
    <div class="">
        {{ $slot }}
    </div>
</div>
