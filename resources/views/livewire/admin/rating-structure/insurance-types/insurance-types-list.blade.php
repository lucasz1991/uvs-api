<div class="px-2">
    <div class="flex justify-between mb-4">
        <h1 class="flex items-center justify-center text-lg px-2 py-1 w-max">
            <span class="w-max">Versicherungstypen</span>
            <span class="ml-2 bg-white text-sky-600 text-xs shadow border border-sky-200 font-bold aspect-square px-2 py-1 flex items-center justify-center rounded-full h-7 leading-none">
                {{ $types->count() }}
            </span>
        </h1>
        <x-link-button href="#" @click.prevent="$dispatch('open-insurance-type-form')" class="btn-xs py-0 leading-[0]">
            +
        </x-link-button>
    </div>

    <div class="w-full">
        <div class="grid grid-cols-12 bg-gray-100 p-2 font-semibold text-gray-700 border-b border-gray-300 text-left">
            <div class="col-span-4">Name</div>
            <div class="col-span-2">Versicherungen</div>
            <div class="col-span-2">Versicherungenarten</div>
            <div class="col-span-2">Status</div>
            <div class="col-span-2">Erstellung</div>
        </div>

        <div class="min-w-max lg:min-w-full" x-sort="$dispatch('orderInsuranceType', { item: $item, position: $position })">
            @foreach ($types as $type)
                <div x-sort:item="{ id: {{ $type->id }} }">
                    <div class="grid grid-cols-12 relative border-b py-2 px-2 items-center">
                        <div class="col-span-4 font-semibold truncate pr-4">
                            {{ $type->name }}
                        </div>

                        <div class="col-span-2 text-xs text-gray-700">
                            <span class="bg-gray-200 px-2 py-1 rounded text-xs">{{ $type->insurances->count() }}</span>
                        </div>

                        <div class="col-span-2 text-xs text-gray-700">
                            <span class="bg-gray-200 px-2 py-1 rounded text-xs">{{ $type->subtypes->count() }}</span>
                        </div>

                        <div class="col-span-2 text-sm">
                            @if($type->is_active)
                                <span class="text-green-700 bg-green-50 px-2 py-1 text-xs rounded">Aktiv</span>
                            @else
                                <span class="text-red-700 bg-red-50 px-2 py-1 text-xs rounded">Inaktiv</span>
                            @endif
                        </div>

                        <div class="col-span-2 text-xs text-gray-500">
                            <span>{{ $type->created_at->locale('de')->diffForHumans() }}</span><br>
                            <small>(Bearbeitet: {{ $type->updated_at->locale('de')->diffForHumans() }})</small>
                        </div>

                        <div class="absolute right-0">
                            <div x-data="{ open: false }" class="relative">
                                <button @click="open = !open" class="w-max text-center px-4 py-2 text-xl font-semibold hover:bg-gray-100 rounded-lg">
                                    &#x22EE;
                                </button>
                                <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-40 bg-white border rounded-lg shadow-lg z-10">
                                    <ul>
                                        <li>
                                            <a href="#" @click.prevent="$dispatch('open-insurance-type-form', [{{ $type->id }}])" class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">
                                                Bearbeiten
                                            </a>
                                        </li>
                                        <li>
                                            <button wire:click="toggleActive({{ $type->id }})" class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">
                                                {{ $type->is_active ? 'Deaktivieren' : 'Aktivieren' }}
                                            </button>
                                        </li>
                                        <li>
                                            <button wire:click="deleteInsuranceType({{ $type->id }})" @click="open = false" class="block w-full text-left px-4 py-2 text-red-600 hover:bg-gray-100">
                                                Löschen
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @livewire('admin.rating-structure.insurance-types.insurance-types-create-edit')

</div>