<div>
    <!-- Basis Shop Seiten -->
    <div class="px-2 mb-6">
        <div class="flex justify-between mb-4">
            <h1 class="flex items-center justify-center text-lg px-2 py-1 w-max">
                <span class="w-max">Grundseiten</span>
                <span class="ml-2 bg-white text-sky-600 text-xs shadow border border-sky-200 font-bold aspect-square px-2 py-1 flex items-center justify-center rounded-full h-7 leading-none">
                    {{ $fixedPages->count() }}
                </span>
            </h1>
            <x-button wire:click="create" class="btn-xs py-0 leading-[0]  waves-effect">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v6h6a1 1 0 110 2h-6v6a1 1 0 11-2 0v-6H3a1 1 0 110-2h6V3a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Neue Seite erstellen
            </x-button>
        </div>
        <table class="w-full border-collapse">
            <thead class="bg-gray-200 text-left">
                <tr>
                    <th class="px-4 py-2">Titel</th>
                    <th class="px-4 py-2">Slug</th>
                    <th class="px-4 py-2 text-center">Status</th>
                    <th class="px-4 py-2"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($fixedPages as $page)
                    <tr class="border-b hover:bg-gray-50 relative">
                        <td class="px-4 py-2">{{ $page->title }}</td>
                        <td class="px-4 py-2 text-gray-600">{{ $page->slug }}</td>
                        <td class="px-4 py-2 text-center">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                {{ $page->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $page->is_active ? 'Aktiv' : 'Inaktiv' }}
                            </span>
                        </td>
                        <td class="px-4 py-2 text-right">
                            <div x-data="{ open: false }" class="relative">
                                <button @click="open = !open" class="w-max text-center px-4 py-2 text-xl font-semibold hover:bg-gray-100  rounded-lg">
                                    &#x22EE;
                                </button>
                                <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-40 bg-white border rounded-lg shadow-lg z-10">
                                    <ul>
                                        <li>
                                            <button 
                                                wire:click="edit({{ $page->id }})"
                                                class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">
                                                Einstellungen
                                            </button>
                                        </li>
                                        <li>
                                            <a href="{{ route('admin.cms.edit-project', ['projectId' => $page->pagebuilder_project]) }}" 
                                            class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">
                                                Bearbeiten
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="p-4">
                {{ $fixedPages->links() }}
        </div>
    </div>

    <!-- Eigene Seiten (nur anzeigen, wenn Einträge vorhanden sind) -->
    @if ($customPages->isNotEmpty())
        <div class="  mt-6">
            <div class="bg-gray-100 px-4 py-3 text-lg font-bold">Eigene Seiten</div>
            <table class="w-full border-collapse">
                <thead class="bg-gray-200 text-left">
                    <tr>
                        <th class="px-4 py-2">Titel</th>
                        <th class="px-4 py-2">Slug</th>
                        <th class="px-4 py-2 text-center">Status</th>
                        <th class="px-4 py-2 text-right">Aktionen</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($customPages as $page)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-2">{{ $page->title }}</td>
                            <td class="px-4 py-2 text-gray-600">{{ $page->slug }}</td>
                            <td class="px-4 py-2 text-center">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                    {{ $page->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ $page->is_active ? 'Aktiv' : 'Inaktiv' }}
                                </span>
                            </td>
                            <td class="px-4 py-2 text-right">
                                <div x-data="{ open: false }" class="relative">
                                    <button @click="open = !open" class="w-max text-center px-4 py-2 text-xl font-semibold hover:bg-gray-100  rounded-lg">
                                        &#x22EE;
                                    </button>
                                    <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-40 bg-white border rounded-lg shadow-lg z-10">
                                        <ul>
                                            <li>
                                                <button 
                                                    wire:click="edit({{ $page->id }})"
                                                    class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">
                                                    Einstellungen
                                                </button>
                                            </li>
                                            <li>
                                                <a href="{{ route('admin.cms.edit-project', ['projectId' => $page->pagebuilder_project]) }}" 
                                                class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">
                                                    Bearbeiten
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="p-4">
                {{ $customPages->links() }}
            </div>
        </div>
    @endif

<!-- X-Dialog-Modal für Erstellen/Bearbeiten -->
<x-dialog-modal wire:model="modalOpen">
    <x-slot name="title">
        <div class="flex justify-between items-center">
            <span>{{ $editingId ? 'Seite bearbeiten' : 'Neue Seite erstellen' }}</span>

            <div class="flex items-center gap-4">
                <!-- Sprache -->
                <select wire:model="language" class="border bg-white rounded px-3 py-1 text-sm">
                    <option value="de">Deutsch</option>
                    <option value="en">Englisch</option>
                    <option value="es">Spanisch</option>
                    <option value="fr">Französisch</option>
                </select>

                <!-- Status -->
                <label class="flex items-center text-sm">
                    <input type="checkbox" wire:model="is_active" class="mr-2">
                    Aktiv
                </label>
            </div>
        </div>
    </x-slot>

    <x-slot name="content">
        <div class="space-y-4">
            <!-- Titel & Slug nebeneinander -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium">Titel</label>
                    <input type="text" wire:model="title" class="w-full border rounded px-4 py-2">
                    @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium">Slug</label>
                    <input type="text" wire:model="slug" class="w-full border rounded px-4 py-2">
                    @error('slug') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Accordion -->
            <div x-data="{ openTab: 'basic' }" class="">
                <!-- Tabs -->
                <div class="flex -mb-[1px] space-x-2">
                    <button @click="openTab = 'basic'" 
                        :class="openTab === 'basic' ? 'text-blue-600 border-blue-600 bg-gray-100  border-b-0' : 'text-gray-500 bg-white'" 
                        class="px-4 py-2 text-sm font-medium transition-all border border-gray-300 rounded-t-lg z-40">
                        Basis
                    </button>
                    <button @click="openTab = 'seo'" 
                        :class="openTab === 'seo' ? 'text-blue-600 border-blue-600 bg-gray-100 border-b-0' : 'text-gray-500 bg-white'" 
                        class="px-4 py-2  text-sm font-medium transition-all border  border-gray-300 rounded-t-lg z-30">
                        SEO
                    </button>
                </div>

                <!-- Basic Settings -->
                <div x-show="openTab === 'basic'">
                    <div class="space-y-4 bg-gray-100 p-4 rounded-b-lg rounded-se-lg border border-gray-300  z-10">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="flex items-center space-x-3 mt-2 ">
                                <label for="showHeader" class="flex items-center cursor-pointer">
                                    <input 
                                        id="showHeader" 
                                        name="showHeader" 
                                        type="checkbox" 
                                        wire:model.live="showHeader" 
                                        class="sr-only peer" 
                                    />
                                    <div class="relative w-9 h-5 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                                    <span class="ms-3 text-sm font-medium ">Header anzeigen</span>
                                </label>
                            </div>
                            <div>

                            </div>
                            <div x-data="{ showTextarea: false }">
                                <label class="block text-sm font-medium">SVG-Icon</label>
                                <div class="flex items-center gap-2 mt-2">
                                    <!-- Button für das Öffnen der Textarea -->
                                    <button @click="showTextarea = !showTextarea" 
                                            class="border rounded p-2 w-full aspect-video bg-white ">
                                            <div class="flex items-center justify-center object-contain object-center max-h-full svg-icon-button overflow-hidden" >
                                                {!! $icon ?: '<span class="text-gray-400 text-xs">SVG</span>' !!}
                                            </div>
                                    </button>
                                </div>

                                <!-- Dropdown mit der Textarea -->
                                <div x-show="showTextarea" class="mt-2" @click.away="showTextarea = !showTextarea">
                                    <textarea wire:model.live.defer="icon" class="w-full border rounded px-4 py-2 h-20 font-mono text-xs"></textarea>
                                    @error('icon') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div x-data="{ showFileInput: false }">
                                <label class="block text-sm font-medium">Header-Hintergrundbild</label>
                                
                                <!-- Button / Bild für das Öffnen der Datei-Auswahl -->
                                <button @click="showFileInput = !showFileInput"
                                        class="border rounded p-2 bg-white flex items-center justify-center  w-full aspect-video mt-2">
                                    @if($header_image)
                                        <img src="{{ asset('storage/' . $header_image) }}" class="w-full h-full object-cover object-center rounded border">
                                    @else
                                        <span class="text-gray-400 text-xs">Kein Bild vorhanden</span>
                                    @endif
                                </button>

                                <!-- Datei-Upload-Feld (versteckt, bis Button geklickt wird) -->
                                <div x-show="showFileInput" class="mt-2">
                                    <input type="file" wire:model="new_header_image" class="w-full border rounded px-4 py-2">
                                    @if($new_header_image)
                                        <span class="text-sm text-green-600">Neues Bild ausgewählt</span>
                                    @endif
                                </div>
                            </div>
                        </div>
    
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium">Veröffentlicht von</label>
                                <input type="datetime-local" wire:model="published_from" class="w-full border rounded px-4 py-2 mt-2">
                                @error('published_from') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium">Veröffentlicht bis</label>
                                <input type="datetime-local" wire:model="published_until" class="w-full border rounded px-4 py-2 mt-2">
                                @error('published_until') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SEO Settings -->
                <div x-show="openTab === 'seo'" class="">
                    <div class="space-y-4 bg-gray-100 p-4 rounded-b-lg rounded-se-lg border border-gray-300  z-10">
                        <div>
                            <label class="block text-sm font-medium">Meta-Beschreibung</label>
                            <textarea wire:model="meta_description" class="w-full border rounded px-4 py-2 mt-2"></textarea>
                            @error('meta_description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium">Meta-Keywords (komma-getrennt)</label>
                            <textarea  wire:model="meta_keywords" class="w-full border rounded px-4 py-2 mt-2"></textarea>
                            @error('meta_keywords') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium">Kanonische URL</label>
                            <input type="text" wire:model="canonical_url" class="w-full border rounded px-4 py-2 mt-2">
                            @error('canonical_url') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium">Robots Meta</label>
                            <input type="text" wire:model="robots_meta" class="w-full border rounded px-4 py-2 mt-2">
                            @error('robots_meta') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium">Benutzerdefiniertes CSS</label>
                            <textarea wire:model="custom_css" class="w-full border rounded px-4 py-2 font-mono text-sm mt-2"></textarea>
                            @error('custom_css') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium">Benutzerdefiniertes JavaScript</label>
                            <textarea wire:model="custom_js" class="w-full border rounded px-4 py-2 font-mono text-sm mt-2"></textarea>
                            @error('custom_js') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <x-slot name="footer">
        <button wire:click="save" class="bg-green-500 text-white px-4 py-2 rounded">Speichern</button>
        <button wire:click="$set('modalOpen', false)" class="text-gray-500 hover:text-gray-700 px-4 py-2">Abbrechen</button>
    </x-slot>
</x-dialog-modal>


</div>
