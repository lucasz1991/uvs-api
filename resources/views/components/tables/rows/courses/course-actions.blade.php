<x-dropdown align="right" width="48">
    <x-slot name="trigger">
        <button type="button" class="text-center px-4 py-2 text-xl font-semibold hover:bg-gray-100 rounded-lg">
            &#x22EE;
        </button>
    </x-slot>

    <x-slot name="content">
        {{-- Bearbeiten: Event dispatchen + Dropdown schließen --}}
        <x-dropdown-link
            href="#"
            @click.prevent="
                $dispatch('open-course-create-edit', { courseId: {{ $item->id }} });
                $dispatch('close');
            "
        >
            Bearbeiten
        </x-dropdown-link>

        {{-- Details: normale Navigation --}}
        <x-dropdown-link href="">
            Details
        </x-dropdown-link>
    </x-slot>
</x-dropdown>
