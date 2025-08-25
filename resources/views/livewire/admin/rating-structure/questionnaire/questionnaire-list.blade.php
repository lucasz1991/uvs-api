<div class="px-2">
    <div class="flex justify-between mb-4">
        <h1 class="flex items-center justify-center text-lg px-2 py-1 w-max">
            <span class="w-max">Fragebögen</span>
            <span class="ml-2 bg-white text-sky-600 text-xs shadow border border-sky-200 font-bold aspect-square px-2 py-1 flex items-center justify-center rounded-full h-7 leading-none">
                {{ $types->count() }}
            </span>
        </h1>
    </div>

    @livewire('admin.rating-structure.questionnaire.questionnaire-edit')

    <div class="w-full">
        <div class="grid grid-cols-12 bg-gray-100 p-2 font-semibold text-gray-700 border-b border-gray-300 text-left">
            <div class="col-span-8">Versicherungstyp</div>
            <div class="col-span-2">Fragen</div>
            <div class="col-span-1">Status</div>
            <div class="col-span-1"></div>
        </div>

        @foreach ($types as $type)
            <div class="grid grid-cols-12 relative border-b py-2 px-2 items-center">
                <div class="col-span-8 font-semibold truncate">
                    {{ $type->name }}
                </div>

                <div class="col-span-2">
                    <span class="bg-gray-100 text-gray-700 text-xs px-2 py-1 rounded">
                        {{ $type->ratingQuestions->count() }} Fragen
                    </span>
                </div>
                <div class="col-span-1">
                    @if ($type->latestVersion)
                        <span class="ml-1 px-2 py-1 rounded  text-xs {{ $type->latestVersion->is_active ? 'text-green-700 bg-green-50' : 'text-red-700 bg-red-50' }}">
                            {{ $type->latestVersion->is_active ? 'Aktiv' : 'Inaktiv' }}
                        </span>
                    @else
                        <span class="ml-1 px-2 py-1 rounded text-xs bg-yellow-100 text-yellow-700">
                            Keine Version
                        </span>
                    @endif

                </div>
                <div class="col-span-1 flex justify-end">
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="w-max text-center px-4 py-2 text-xl font-semibold hover:bg-gray-100 rounded-lg">
                            &#x22EE;
                        </button>
                        <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-40 bg-white border rounded-lg shadow-lg z-10">
                            <ul>
                                <li>
                                    <a href="#"
                                       @click.prevent="$dispatch('open-formbuilder', [{{ $type->id }}])"
                                       class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">
                                        Bearbeiten
                                    </a>
                                </li>
                                <li>
    <button wire:click="toggleActiveVersion({{ $type->id }})"
            class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">
        {{ $type->latestVersion && $type->latestVersion->is_active ? 'Deaktivieren' : 'Aktivieren' }}
    </button>
</li>

                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
