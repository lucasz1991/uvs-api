<div class="px-2">
    <div class="flex justify-between mb-4">
        <h1 class="flex items-center justify-center text-lg px-2 py-1 w-max">
            <span class="w-max">Module</span>
            <span class="ml-2 bg-white text-sky-600 text-xs shadow border border-sky-200 font-bold aspect-square px-2 py-1 flex items-center justify-center rounded-full  h-7  leading-none">
                {{ $projects->count() }}
            </span>
        </h1>
        <x-link-button href="{{ route('admin.cms.edit-project') }}" 
        class="btn-xs py-0 leading-[0]">
            + 
        </x-link-button>
    </div>
    <div class="w-full">
        <div class="grid grid-cols-12 bg-gray-100 p-2 font-semibold text-gray-700 border-b border-gray-300   text-left ">
            <div class="col-span-4">Name</div>
            <div class="col-span-2">Veröffentlichung</div>
            <div class="col-span-2">Seite</div>
            <div class="col-span-2">Position</div>
            <div class="col-span-2">Erstellung</div>
        </div>
        <div class="min-w-max lg:min-w-full" 
            x-sort="$dispatch('orderProject', { item: $item, position: $position })">
            @foreach ($projects as $project)
                <div x-sort:item="{{ $project }}">
                    <div class="grid grid-cols-12 relative border-b py-2 px-2">
                        <div class="col-span-4 pr-2 flex justify-between items-center">
                            <div class="flex items-center space-x-2">
                                @if($project->lock)
                                    <svg class="h-3 w-3 text-red-200" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                                        <path fill="currentColor" d="M144 144l0 48 160 0 0-48c0-44.2-35.8-80-80-80s-80 35.8-80 80zM80 192l0-48C80 64.5 144.5 0 224 0s144 64.5 144 144l0 48 16 0c35.3 0 64 28.7 64 64l0 192c0 35.3-28.7 64-64 64L64 512c-35.3 0-64-28.7-64-64L0 256c0-35.3 28.7-64 64-64l16 0z"/>
                                    </svg>
                                @else
                                    <svg class="h-3 w-3 text-green-200" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                                        <path fill="currentColor" d="M352 144c0-44.2 35.8-80 80-80s80 35.8 80 80l0 48c0 17.7 14.3 32 32 32s32-14.3 32-32l0-48C576 64.5 511.5 0 432 0S288 64.5 288 144l0 48L64 192c-35.3 0-64-28.7-64 64L0 448c0 35.3 28.7 64 64 64l320 0c35.3 0 64-28.7 64-64l0-192c0-35.3-28.7-64-64-64l-32 0 0-48z"/>
                                    </svg>
                                @endif
                                <span class="font-semibold truncate overflow-hidden whitespace-nowrap ">{{ $project->name }}</span>
                            </div>
                            <div  class=" flex justify-between items-center space-x-2">
                                @if (!empty($project->lang))
                                    <div class="text-xs font-semibold text-gray-700 bg-gray-200 px-2 py-1 rounded">
                                        {{ $project->lang }}
                                    </div>
                                @endif
                                <div>
                                    @if ($project->status === 0)
                                        <span class="px-2 py-1 text-xs font-semibold text-gray-700 bg-gray-100 rounded">Entwurf</span>
                                    @elseif ($project->status === 1)
                                        <span class="px-2 py-1 text-xs font-semibold text-green-700 bg-green-50 rounded">Aktiv</span>
                                    @elseif ($project->status === 2)
                                        <span class="px-2 py-1 text-xs font-semibold text-red-700 bg-red-50 rounded">Archiviert</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-span-2 text-xs px-2 text-gray-600 flex space-x-2 items-center">
                            <span class="font-semibold text-green-700">{{ $project->published_from ? \Carbon\Carbon::parse($project->published_from)->locale('de')->diffForHumans() : '' }}</span>
                            <span class="font-semibold ">{{ $project->published_from && $project->published_until ? 'bis' : '' }}</span>
                            <span class="font-semibold text-red-700">{{ $project->published_until ? \Carbon\Carbon::parse($project->published_until)->locale('de')->diffForHumans() : '' }}</span>
                        </div>
                        <div class="col-span-2 relative" x-data="{ showTooltip: false }">
                            <div class="flex items-center space-x-1 overflow-hidden text-ellipsis max-w-[150px] sm:max-w-[200px] whitespace-nowrap"
                                @mouseover="showTooltip = true" 
                                @mouseleave="showTooltip = false">
                                @if(is_array($project->page) && count($project->page) > 0)
                                    @foreach($project->page as $pag)
                                        <div 
                                            class="px-2 py-1 text-xs bg-gray-100 text-gray-700 rounded-md mr-1 mt-1 "
                                        >
                                            {{ $pag }}
                                        </div>
                                    @endforeach
                                @else
                                    <span class="text-xs text-gray-500">-</span>
                                @endif
                            </div>
                            <div x-show="showTooltip" x-transition 
                                class="absolute left-0 bottom-full mb-1 w-auto max-w-xs bg-gray-700 text-white text-xs rounded-md px-3 py-2 shadow-lg z-10">
                                <span class="font-semibold">Seiten:</span>
                                <ul>
                                    @if(is_array($project->page) && count($project->page) > 0)
                                        @foreach($project->page as $pag)
                                            <li class="mt-1">{{ $pag }}</li>
                                        @endforeach
                                    @else
                                        <li>-</li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                        <div class="col-span-2 relative" x-data="{ showTooltip: false }">
                            <div class="flex items-center space-x-1 overflow-hidden text-ellipsis max-w-[150px] sm:max-w-[200px] whitespace-nowrap"
                                @mouseover="showTooltip = true" 
                                @mouseleave="showTooltip = false">
                                @if(is_array($project->position) && count($project->position) > 0)
                                    @foreach($project->position as $pos)
                                        <div 
                                            class="px-2 py-1 text-xs bg-gray-100 text-gray-700 rounded-md mr-1 mt-1"
                                        >
                                            {{ $pos }}
                                        </div>
                                    @endforeach
                                @else
                                    <span class="text-xs text-gray-500">-</span>
                                @endif
                            </div>
                            <div x-show="showTooltip" x-transition 
                                class="absolute left-0 bottom-full mb-1 w-auto max-w-xs bg-gray-700 text-white text-xs rounded-md px-3 py-2 shadow-lg z-10">
                                <span class="font-semibold">Positionen:</span>
                                <ul>
                                    @if(is_array($project->position) && count($project->position) > 0)
                                        @foreach($project->position as $pos)
                                            <li class="mt-1">{{ $pos }}</li>
                                        @endforeach
                                    @else
                                        <li>-</li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                        <div class="col-span-2 text-xs text-gray-500 pr-4">
                            <span>{{ \Carbon\Carbon::parse($project->created_at)->locale('de')->diffForHumans() }}</span>
                            <br>
                            <small>(Bearbeitet: {{ \Carbon\Carbon::parse($project->updated_at)->locale('de')->diffForHumans() }})</small>
                        </div>
                        <div class="absolute right-0">
                            @if(!$project->lock || auth()->id() === $project->last_edited_by)
                                <div x-data="{ open: false }" class="relative">
                                    <button @click="open = !open" class="w-max text-center px-4 py-2 text-xl font-semibold hover:bg-gray-100  rounded-lg">
                                        &#x22EE;
                                    </button>
                                    <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-40 bg-white border rounded-lg shadow-lg z-10">
                                        <ul>
                                            @if(!$project->lock)
                                                <li>
                                                    <button 
                                                        @click="$dispatch('open-project-settings', { projectId: {{ $project->id }} }), open = !open"
                                                        class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">
                                                        Einstellungen
                                                    </button>
                                                </li>
                                                <li>
                                                    <a href="{{ route('admin.cms.edit-project', ['projectId' => $project->id]) }}" 
                                                    class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">
                                                        Bearbeiten
                                                    </a>
                                                </li>
                                                <li>
                                                    <button wire:click="duplicateProject({{ $project->id }})" @click="open = !open"
                                                            class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">
                                                        Duplizieren
                                                    </button>
                                                </li>
                                            @endif
                                                <li>
                                                    <button wire:click="toggleLockProject({{ $project->id }})" 
                                                            class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">
                                                        {{ $project->lock ? 'Entsperren' : 'Sperren' }}
                                                    </button>
                                                </li>
                                            @if(!$project->lock)
                                                <li>
                                                    <button wire:click="deleteProject({{ $project->id }})"  @click="open = !open"
                                                            class="block w-full text-left px-4 py-2 text-red-600 hover:bg-gray-100">
                                                        Löschen
                                                    </button>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @livewire('admin.cms.project-settings-manager')
</div>