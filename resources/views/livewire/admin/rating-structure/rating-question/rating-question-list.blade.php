<div class="px-2">
    <div class="flex justify-between mb-4">
        <h1 class="flex items-center justify-center text-lg px-2 py-1 w-max">
            <span class="w-max">Fragen</span>
            <span class="ml-2 bg-white text-sky-600 text-xs shadow border border-sky-200 font-bold aspect-square px-2 py-1 flex items-center justify-center rounded-full h-7 leading-none">
                {{ $questions->count() }}
            </span>
        </h1>
        <x-link-button href="#" @click.prevent="$dispatch('open-rating-question-form')" class="btn-xs py-0 leading-[0]">
            +
        </x-link-button>
    </div>

    @livewire('admin.rating-structure.rating-question.rating-question-create-edit')

    <div class="w-full">
        <div class="grid grid-cols-12 bg-gray-100 p-2 font-semibold text-gray-700 border-b border-gray-300 text-left">
            <div class="col-span-5">Name</div>
            <div class="col-span-3">Typ</div>
            <div class="col-span-2">Status</div>
            <div class="col-span-2">Erstellt</div>
        </div>

        <div class="min-w-max lg:min-w-full">
            @foreach ($questions as $question)
                <div >
                    <div class="grid grid-cols-12 relative border-b py-2 px-2 items-center">
                        <div class="col-span-5 font-semibold truncate">
                            {{ $question->title }}
                        </div>

                        <div class="col-span-3 text-sm text-gray-600">
                            {{ $question->type }}
                        </div>

                        <div class="col-span-2 text-sm">
                            @if($question->is_active)
                                <span class="text-green-700 bg-green-50 px-2 py-1 text-xs rounded">Aktiv</span>
                            @else
                                <span class="text-red-700 bg-red-50 px-2 py-1 text-xs rounded">Inaktiv</span>
                            @endif
                        </div>

                        <div class="col-span-2 text-xs text-gray-500">
                            <span>{{ $question->created_at->locale('de')->diffForHumans() }}</span>
                            <br>
                            <small>(Bearbeitet: {{ $question->updated_at->locale('de')->diffForHumans() }})</small>
                        </div>

                        <div class="absolute right-0">
                            <div x-data="{ open: false }" class="relative">
                                <button @click="open = !open" class="w-max text-center px-4 py-2 text-xl font-semibold hover:bg-gray-100 rounded-lg">
                                    &#x22EE;
                                </button>
                                <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-40 bg-white border rounded-lg shadow-lg z-10">
                                    <ul>
                                        <li>
                                            <a href="#" @click.prevent="$dispatch('open-rating-question-form',[ {{ $question->id }}])"
                                               class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">
                                                Bearbeiten
                                            </a>
                                        </li>
                                        <li>
                                            <button wire:click="toggleActive({{ $question->id }})"
                                                    class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">
                                                {{ $question->is_active ? 'Deaktivieren' : 'Aktivieren' }}
                                            </button>
                                        </li>
                                        <li>
                                            <button wire:click="delete({{ $question->id }})" @click="open = false"
                                                    class="block w-full text-left px-4 py-2 text-red-600 hover:bg-gray-100">
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
</div>
