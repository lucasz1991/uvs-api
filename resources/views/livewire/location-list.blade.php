<div x-data="{
        showAddLocationModal: @entangle('showAddLocationModal'),
        selectedLocation: @entangle('selectedLocation'),
        selectedLocationId: @entangle('selectedLocationId'),
        selectedRetailSpaceId: @entangle('selectedRetailSpaceId'),
        selectedRetailSpaceEditorInit: @entangle('selectedRetailSpaceEditorInit'),
        editingSalesAreas: @entangle('editingSalesAreas'),
        editingRetailSpace: @entangle('editingRetailSpace'),
        newRetailAreaElement: @entangle('newRetailAreaElement'),
        showAddSalesAreaModal: @entangle('showAddSalesAreaModal'),
        showEditRetailSpaceModal: @entangle('showEditRetailSpaceModal')
    }"  wire:loading.class="cursor-wait">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight w-full mb-6">
            {{ __('Standorte Verwaltung') }}
        </h2>
        <div class="bg-blue-50 border-l-4 border-blue-500 text-blue-700 p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-6 w-6 text-blue-500" xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 23.625 23.625" fill="currentColor" aria-hidden="true">
                        <path
                            d="M11.812 0C5.289 0 0 5.289 0 11.812s5.289 11.813 11.812 11.813 11.813-5.29 11.813-11.813S18.335 0 11.812 0zm2.459 18.307c-.608.24-1.092.422-1.455.548a3.838 3.838 0 0 1-1.262.189c-.736 0-1.309-.18-1.717-.539s-.611-.814-.611-1.367c0-.215.015-.435.045-.659a8.23 8.23 0 0 1 .147-.759l.761-2.688c.067-.258.125-.503.171-.731.046-.23.068-.441.068-.633 0-.342-.071-.582-.212-.717-.143-.135-.412-.201-.813-.201-.196 0-.398.029-.605.09-.205.063-.383.12-.529.176l.201-.828c.498-.203.975-.377 1.43-.521a4.225 4.225 0 0 1 1.29-.218c.731 0 1.295.178 1.692.53.395.353.594.812.594 1.376 0 .117-.014.323-.041.617a4.129 4.129 0 0 1-.152.811l-.757 2.68a7.582 7.582 0 0 0-.167.736 3.892 3.892 0 0 0-.073.626c0 .356.079.599.239.728.158.129.435.194.827.194.185 0 .392-.033.626-.097.232-.064.4-.121.506-.17l-.203.827zm-.134-10.878a1.807 1.807 0 0 1-1.275.492c-.496 0-.924-.164-1.28-.492a1.57 1.57 0 0 1-.533-1.193c0-.465.18-.865.533-1.196a1.812 1.812 0 0 1 1.28-.497c.497 0 .923.165 1.275.497.353.331.53.731.53 1.196 0 .467-.177.865-.53 1.193z"
                            data-original="#030104" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm">
                        <strong class="text-lg">Willkommen zur Standortverwaltung!</strong> <br>
                        Auf dieser Seite kannst du alle Standorte bearbeiten und anpassen. Zusätzlich kannst du Verkaufsflächen in jedem Standort über den Verkaufsflächen-Editor verwalten.  
                        Organisiere deine Standorte und Flächen effizient, um ein optimales Kundenerlebnis zu gewährleisten.
                    </p>
                </div>
            </div>
        </div>
    <!-- Standorte Auflistung -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @if($locations->isEmpty())
            <div class="bg-white shadow-md rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-500 mb-2">Keine Standorte verfügbar</h3>
                <p class="text-sm text-gray-500">Es wurden noch keine Standorte hinzugefügt. Fügen Sie einen neuen Standort hinzu, um loszulegen.</p>
            </div>
        @else
            @foreach($locations as $location)
                <div 
                    class="bg-white shadow-md rounded-lg p-6 mb-4 border-2 cursor-pointer transition-all duration-200 hover:-translate-y-2 transition-all relative" 
                    :class="{ 'border-blue-500': selectedLocationId === {{ $location->id }} }"
                    @click="$wire.selectLocation({{ $location->id }})
                    ">
                    <!-- Statusanzeige oben rechts -->
                    @php
                        $statusClass = '';
                        $statusText = '';

                        switch ($location->status) {
                            case 1:
                                $statusClass = 'bg-yellow-500 text-white';
                                $statusText = 'Entwurf';
                                break;
                            case 2:
                                $statusClass = 'bg-green-500 text-white';
                                $statusText = 'Veröffentlicht';
                                break;
                            case 3:
                                $statusClass = 'bg-gray-500 text-white';
                                $statusText = 'Archiviert';
                                break;
                            default:
                                $statusClass = 'bg-gray-500 text-white';
                                $statusText = 'Unbekannt';
                                break;
                        }
                    @endphp
                    <span class="absolute top-2 right-2 px-3 py-1 text-xs font-semibold rounded-full {{ $statusClass }}">
                        {{ $statusText }}
                    </span>
                    <h3 class="text-lg font-semibold mb-2">{{ $location->name }}</h3>
                    <p class="text-sm text-gray-500 mb-4">{{ $location->address }}</p>
                    <p class="text-sm text-gray-500 mb-4">{{ $location->city }}, {{ $location->state }}</p>
                    <p class="text-sm text-gray-500 mb-4">{{ $location->postal_code }} - {{ $location->country }}</p>
                    <p class="text-sm text-gray-500 mb-4">Telefon: {{ $location->phone_number }}</p>
                </div>
            @endforeach
            @endif
            <Button @click="showAddLocationModal = true" class="mt-3 p-6  text-gray-500 font-semibold text-base rounded max-w-md h-52 flex flex-col items-center justify-center cursor-pointer border-2 border-gray-300  hover:border-blue-300 border-dashed mx-auto font-[sans-serif]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24" viewBox="0 0 500 500">
                    <path fill="currentColor" d="M332.68 232.19H159.34a8.95 8.95 0 0 1-8.95-8.95v-29.34a8.95 8.95 0 0 1 8.95-8.95h173.34a8.95 8.95 0 0 1 0 17.9h-164.4v11.44h164.4a8.95 8.95 0 0 1 0 17.9zM352.35 356.15H152a8.95 8.95 0 0 1-8.95-8.95v-29.34a8.95 8.95 0 0 1 8.95-8.95h200.35a8.95 8.95 0 0 1 8.95 8.95v29.34a8.95 8.95 0 0 1-8.95 8.95zm-191.4-17.9h182.45v-11.44H160.95v11.44zM332.68 324.19a8.95 8.95 0 0 1-8.95-8.95v-92a8.95 8.95 0 0 1 8.95-8.95h1.61v-15.19l-82.25-46.92-58.46 32.77h105.41a8.95 8.95 0 1 1 0 17.9H159.34a8.95 8.95 0 0 1-8.66-6.69 8.95 8.95 0 0 1 4.29-10.07l92.75-52c2.74-1.53 6.1-1.53 8.8 0l91.16 52a8.95 8.95 0 0 1 4.52 7.77v29.34a8.95 8.95 0 0 1-8.95 8.95h-1.61v83.05a8.95 8.95 0 0 1-8.95 8.95zM280.69 324.19a8.95 8.95 0 0 1-8.95-8.95v-92a8.95 8.95 0 1 1 17.9 0v92a8.95 8.95 0 0 1-8.95 8.95zM171.49 324.19a8.95 8.95 0 0 1-8.95-8.95v-92a8.95 8.95 0 1 1 17.9 0v92a8.95 8.95 0 0 1-8.95 8.95zM223.49 324.19a8.95 8.95 0 0 1-8.95-8.95v-92a8.95 8.95 0 1 1 17.9 0v92a8.95 8.95 0 0 1-8.95 8.95zM254.05 454.22c-113.14 0-205.19-92.05-205.19-205.2S140.91 43.83 254.05 43.83a204.5 204.5 0 0 1 68.15 11.59 8.95 8.95 0 1 1-5.94 16.87 186.1 186.1 0 0 0-62.21-10.56c-103.27 0-187.29 84.01-187.29 187.29s84.01 187.3 187.29 187.3 187.29-84.02 187.29-187.3c0-20.46-3.28-40.58-9.75-59.8a8.95 8.95 0 1 1 16.97-5.71c7.09 21.07 10.68 43.1 10.68 65.51 0 113.15-92.05 205.2-205.19 205.2zM391.42 153.07a8.95 8.95 0 0 1-8.95-8.95V65.62a8.95 8.95 0 1 1 17.9 0v78.5a8.95 8.95 0 0 1-8.95 8.95zM430.68 113.82h-78.5a8.95 8.95 0 0 1 0-17.9h78.5a8.95 8.95 0 1 1 0 17.9z"/>
                </svg>
                Standort Hinzufügen
                <p class="text-xs font-medium text-gray-400 mt-2">fügen sie einen Standort hinzu</p>
            </Button>
    </div>
<!-- Verkaufsflächen Editor -->
@if($selectedLocation && $editingSalesAreas)
        <div class="mt-10 bg-white shadow-md rounded-lg p-6">
            <h3 class="text-xl font-semibold mb-4">Verkaufsflächen für {{ $selectedLocation->name }}</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($editingSalesAreas as $salesArea)
                    @php
                        // Status-Klassen und Texte für die Verkaufsflächen bestimmen
                        $statusClass = '';
                        $statusText = '';

                        switch ($salesArea['status']) {
                            case 1:
                                $statusClass = 'bg-yellow-500 text-white';
                                $statusText = 'Entwurf';
                                break;
                            case 2:
                                $statusClass = 'bg-green-500 text-white';
                                $statusText = 'Veröffentlicht';
                                break;
                            case 3:
                                $statusClass = 'bg-gray-500 text-white';
                                $statusText = 'Archiviert';
                                break;
                            default:
                                $statusClass = 'bg-gray-500 text-white';
                                $statusText = 'Unbekannt';
                                break;
                        }
                    @endphp

                    <div 
                        class="bg-gray-100 border-2 p-4 rounded-lg shadow-md cursor-pointer transition-all duration-200 relative" 
                        :class="{ 'border-blue-500': selectedRetailSpaceId === {{ $salesArea->id }} }"
                        @click="$wire.selectRetailSpace({{ $salesArea->id }})"
                    >
                        
                        <!-- Statusanzeige oben rechts -->
                        <span class="absolute top-2 right-2 px-3 py-1 text-xs font-semibold rounded-full {{ $statusClass }}">
                            {{ $statusText }}
                        </span>
                        
                        <p class="text-sm text-gray-600">{{ $salesArea['name'] }}</p>
           
                    </div>
                @empty
                    
                @endforelse
                <Button @click="showAddSalesAreaModal = true" class=" p-4 bg-white text-gray-500 font-semibold text-base rounded w-full flex flex items-center justify-center cursor-pointer border-2 border-gray-300  hover:border-blue-300 border-dashed mx-auto font-[sans-serif]">
                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w-[18px] h-[18px] mr-3" viewBox="0 0 24 24">
                        <path d="M18 2c2.206 0 4 1.794 4 4v12c0 2.206-1.794 4-4 4H6c-2.206 0-4-1.794-4-4V6c0-2.206 1.794-4 4-4zm0-2H6a6 6 0 0 0-6 6v12a6 6 0 0 0 6 6h12a6 6 0 0 0 6-6V6a6 6 0 0 0-6-6z" data-original="#000000"></path>
                        <path d="M12 18a1 1 0 0 1-1-1V7a1 1 0 0 1 2 0v10a1 1 0 0 1-1 1z" data-original="#000000"></path>
                        <path d="M6 12a1 1 0 0 1 1-1h10a1 1 0 0 1 0 2H7a1 1 0 0 1-1-1z" data-original="#000000"></path>
                </svg>
                <p class="text-xs font-medium text-gray-400">Verkaufsfläche hinzufügen</p>
                </Button>
            </div>
        </div>
@endif

@if($selectedRetailSpaceId)
    <div class="mt-6 bg-white shadow-md rounded-lg p-6 w-full">
        <h3 class="text-xl font-semibold mb-4">Verkaufsfläche</h3>
        <!-- Layout Grundform der Verkaufsfläche basierend auf Matrix -->
        @php
            if (!is_array($editingRetailSpace)) {
                $editingRetailSpace = json_decode($editingRetailSpace, true);
            }
        @endphp
        <div class="mb-4">
        @if($selectedLocation && $selectedLocation->status == 2)
    <div class="bg-yellow-100 text-yellow-800 border border-yellow-300 rounded-lg p-4 mb-4">
        <p class="font-semibold">Hinweis:</p>
        <p>Veröffentlichte Verkaufsflächen können nicht mehr bearbeitet werden. Bitte kontaktiere den Admin, wenn du Änderungen vornehmen möchtest.</p>
        <!-- Anzeige der Anzahl der Regale -->
        <p class="text-sm text-gray-600 mt-2">
            Insgesamt gibt es <strong>{{ $totalShelves }}</strong> Regale in dieser Verkaufsfläche.
        </p>
    </div>
@endif
            <span class="block text-sm font-medium text-gray-700 mb-2">Layout der Verkaufsfläche</span>
            @if(auth()->user()->current_team_id == 1)
    <!-- Buttons zum Bearbeiten und Löschen der Verkaufsfläche -->
    <div class="flex justify-start mt-4 mb-2">
        <button type="button" @click="showEditRetailSpaceModal = true" class="text-blue-500 hover:text-blue-700 mr-4">Bearbeiten</button>
        <button type="button" wire:click="deleteRetailSpace" class="text-red-500 hover:text-red-700">Löschen</button>
    </div>
@endif
            <div class="w-80 relative border overflow-hidden svg-editor-wrapper-preview">
                <!-- SVG-Darstellung der Verkaufsfläche -->
                <svg xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 {{ $editingRetailSpace['dimensions']['width'] }} {{ $editingRetailSpace['dimensions']['height'] }}"
                            class="w-full h-full"
                            style="background-image: url('{{ $editingRetailSpace['backgroundimg']['url'] ?? '' }}');background-size: {{ $editingRetailSpace['backgroundimg']['size'] ?? 'cover' }};background-repeat: no-repeat;background-position: center;"
                            preserveAspectRatio="xMidYMid meet">
                            <!-- Dynamische Elemente anzeigen -->
                            @foreach($editingRetailSpace['elements']['shelves'] as $shelf)
                            @php
                                $svgWidth = $editingRetailSpace['dimensions']['width'];
                                $svgHeight = $editingRetailSpace['dimensions']['height'];
                                $x = ($shelf['x'] / $svgWidth) * $svgWidth;
                                $y = ($shelf['y'] / $svgHeight) * $svgHeight;
                                $width = $shelf['width'];
                                $height = $shelf['height'];
                                $textX = $x + $width / 2; // Text mittig platzieren
                                $textY = $y + $height / 2; // Text mittig platzieren
                            @endphp
                            <g x="{{ $x }}" y="{{ $y }}" width="{{ $width }}" height="{{ $height }}" fill="{{ $shelf['color'] ?? '#4caf50' }}" data-type="shelf" data-id="{{ $shelf['element_id'] ?? null }}" data-text="{{ $shelf['text'] ?? 'Regal' }}" data-color="{{ $shelf['color'] ?? '#4caf50' }}">
                                <rect  x="{{ $x }}" y="{{ $y }}" width="{{ $width }}" height="{{ $height }}" fill="{{ $shelf['color'] ?? '#4caf50' }}"></rect>
                                <text  x="{{ $textX }}" y="{{ $textY }}" font-family="Arial" font-size="1em" fill="#fff" text-anchor="middle" alignment-baseline="middle">
                                    {{ $shelf['text'] ?? 'Regal' }}
                                </text>
                            </g>
                            @endforeach
                            @foreach($editingRetailSpace['elements']['others'] as $element)
                            @php
                                $x = ($element['x'] / $svgWidth) * $svgWidth;
                                $y = ($element['y'] / $svgHeight) * $svgHeight;
                                $width = $element['width'];
                                $height = $element['height'];
                                $textX = $x + $width / 2; // Text mittig platzieren
                                $textY = $y + $height / 2; // Text mittig platzieren
                            @endphp
                            <g x="{{ $x }}" y="{{ $y }}" width="{{ $width }}" height="{{ $height }}" fill="{{ $shelf['color'] ?? '#4caf50' }}" data-type="other" data-id="{{ $element['element_id'] ?? null }}" data-text="{{ $element['text'] ?? 'Eingang' }}" data-color="{{ $element['color'] ?? '#f44336' }}">
                                <rect  x="{{ $x }}" y="{{ $y }}" width="{{ $width }}" height="{{ $height }}" fill="{{ $element['color'] ?? '#f44336' }}"></rect>
                                <text  x="{{ $textX }}" y="{{ $textY }}" font-family="Arial" font-size="1em" fill="#fff" text-anchor="middle" alignment-baseline="middle">
                                    {{ $element['text'] ?? 'Eingang' }}
                                </text>
                            </g>
                            @endforeach
                        </svg>
            </div>
        </div>
    </div>
    @endif
    <x-dialog-modal wire:model="showEditRetailSpaceModal">
        <x-slot name="title">
            <h3 class="text-xl font-semibold mb-4">Verkaufsfläche Bearbeiten</h3>
        </x-slot>
        <x-slot name="content">
        <div class="grid grid-cols-2 gap-4">
            <div>

                <span class="block text-sm font-medium text-gray-700 mb-2">Layout der Verkaufsfläche bearbeiten</span>
                @if($editingRetailSpace!=null)
                <span class="block text-sm font-medium text-gray-700 mb-2">Breite: {{ $editingRetailSpace['dimensions']['width'] }}cm  Höhe:{{ $editingRetailSpace['dimensions']['height'] }}cm</span>
                @endif
                @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                            <strong class="font-bold">Fehler:</strong>
                            <ul class="list-disc pl-5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
            </div>

                <div class="flex justify-end mt-4">
                    <button type="button" @click="showEditRetailSpaceModal = false" class="text-gray-500 hover:text-gray-700 mr-2">Abbrechen</button>
                    <button type="button"  @click="syncRetailSpaceFromSVG()" class="text-blue-500 hover:text-blue-700">Speichern</button>
                </div>
        </div>

        @if($editingRetailSpace!=null)
        <div class="relative mb-4 mt-2 h-full">
            
            <!-- Verfügbare Elemente als Drag-and-Drop-Leiste über dem Layout -->
            <div class="flex items-end gap-4 absolute left-2 top-2" style="z-index:998;">
                <div class="flex justify-center">
                    <div
                        x-data="{
                            open: false,
                            toggle() {
                                if (this.open) {
                                    return this.close()
                                }
                
                                this.$refs.button.focus()
                
                                this.open = true
                            },
                            close(focusAfter) {
                                if (! this.open) return
                
                                this.open = false
                
                                focusAfter && focusAfter.focus()
                            }
                        }"
                        x-on:keydown.escape.prevent.stop="close($refs.button)"
                        
                        x-id="['dropdown-button']"
                        class="relative"
                    >
                        <!-- Button -->
                        <button
                            x-ref="button"
                            x-on:click="toggle()"
                            :aria-expanded="open"
                            :aria-controls="$id('dropdown-button')"
                            type="button"
                            class="relative flex items-center whitespace-nowrap justify-center gap-2 py-2 rounded-lg shadow-sm bg-white hover:bg-gray-50 text-gray-800 border border-gray-200 hover:border-gray-200 px-4 shadow-xl"
                        >
                            <span>Optionen</span>
                
                            <!-- Heroicon: micro chevron-down -->
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                                <path fill-rule="evenodd" d="M4.22 6.22a.75.75 0 0 1 1.06 0L8 8.94l2.72-2.72a.75.75 0 1 1 1.06 1.06l-3.25 3.25a.75.75 0 0 1-1.06 0L4.22 7.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                            </svg>
                        </button>
                
                        <!-- Panel -->
                        <div
                            x-ref="panel"
                            x-show="open"
                            x-transition.origin.top.left
                            :id="$id('dropdown-button')"
                            x-cloak
                            class="absolute left-0 min-w-48 rounded-lg shadow-sm mt-2 z-10 origin-top-left bg-white p-1.5 outline-none rounded    border-2 border-gray-300  hover:border-blue-300 border-dashed shadow-xl"
                        >


                            <div class="col-span-1 p-4 bg-white text-gray-500   ">
                                <div class="flex items-center justify-start gap-4 flex-wrap">
                                    @foreach($RetailAreaElements as $element)
                                        <div draggable="true" 
                                            data-type="{{ $element['type'] }}" 
                                            data-width="{{ $element['width'] }}" 
                                            data-height="{{ $element['height'] }}" 
                                            data-color="{{ $element['color'] }}" 
                                            data-text="{{ $element['name'] }}"
                                            class="draggable-item text-center cursor-move flex items-center justify-center relative group hover:shadow-lg transition-all w-20 h-10"
                                            style="aspect-ratio: {{ $element['width'] / $element['height'] }}; 
                                                background-color: {{ $element['color'] }};  
                                                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);">
                                            <!-- Element Name -->
                                            <span class="text-xs font-semibold text-white">{{ $element['name'] }}</span>
                
                                            <!-- Tooltip -->
                                            <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 hidden group-hover:flex flex-col items-center bg-gray-800 text-white text-xs rounded-md p-2 shadow-lg z-10">
                                                <span><strong>Name:</strong> {{ $element['name'] }}</span>
                                                <span><strong>Typ:</strong> {{ ucfirst($element['type']) }}</span>
                                                <span><strong>Abmessungen:</strong> {{ $element['width'] }}cm x {{ $element['height'] }}cm</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                                <!-- Neues Flächenelement hinzufügen -->
                                <div 
                                    x-data="{
                                        elementOpen: false,
                                        toggleElementDropdown() {
                                            this.elementOpen = !this.elementOpen;
                                        }
                                    }"                                
                                class="col-span-1 p-4 bg-white text-gray-500 mt-4 border-t border-gray-200">
                                    <button
                                        x-ref="button"
                                        x-on:click="toggleElementDropdown()"
                                        type="button"
                                        class="relative flex items-center whitespace-nowrap justify-center gap-2 py-2 rounded-lg  bg-white hover:bg-gray-50 text-gray-800 border  hover:border-gray-200 px-4"
                                    >
                                        <span>Neues Flächenelement</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                                            <path fill-rule="evenodd" d="M4.22 6.22a.75.75 0 0 1 1.06 0L8 8.94l2.72-2.72a.75.75 0 1 1 1.06 1.06l-3.25 3.25a.75.75 0 0 1-1.06 0L4.22 7.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                                        </svg>
                                    </button>

                                    <div
                                        x-ref="elementPanel"
                                        x-show="elementOpen"
                                        x-transition.origin.top.left
                                        x-cloak
                                        class="mt-2 rounded-lg shadow-sm p-4 border border-gray-300 bg-white"
                                    >
                                        <h3 class="text-sm font-semibold text-gray-700 mb-2">Neues Flächenelement erstellen</h3>
                                        <form  wire:submit.prevent="addRetailAreaElement">
                                            <div class="mb-2">
                                                <label class="block text-sm font-medium text-gray-700">Typ</label>
                                                <x-input-error for="newRetailAreaElement.type" class="mt-2" />
                                                <select x-model="newRetailAreaElement.type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                                    <option value="shelf">Regal</option>
                                                    <option value="other">Diverses</option>
                                                </select>
                                            </div>
                                            <div class="mb-2">
                                                <label class="block text-sm font-medium text-gray-700">Name</label>
                                                <x-input-error for="newRetailAreaElement.name" class="mt-2" />
                                                <input type="text" x-model="newRetailAreaElement.name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                            </div>
                                            <div class="mb-2">
                                                <label class="block text-sm font-medium text-gray-700">Breite (cm)</label>
                                                <x-input-error for="newRetailAreaElement.width" class="mt-2" />
                                                <input type="number" x-model="newRetailAreaElement.width" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                            </div>
                                            <div class="mb-2">
                                                <label class="block text-sm font-medium text-gray-700">Höhe (cm)</label>
                                                <x-input-error for="newRetailAreaElement.height" class="mt-2" />
                                                <input type="number" x-model="newRetailAreaElement.height" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                            </div>
                                            <div class="mb-2">
                                                <label class="block text-sm font-medium text-gray-700">Farbe</label>
                                                <x-input-error for="newRetailAreaElement.color" class="mt-2" />
                                                <input type="color" x-model="newRetailAreaElement.color" class="mt-1 block w-full h-10 rounded-md border-gray-300 shadow-sm">
                                            </div>
                                            <button type="submit" class="mt-2 px-4 py-2 bg-green-500 text-white text-sm font-semibold rounded-lg hover:bg-green-600">Hinzufügen</button>
                                        </form>
                                    </div>
                                </div>
                                <!-- Hintergrundbild hochladen -->
                                <div
                                    x-data="{
                                        backgroundOpen: false,
                                        toggleBackgroundDropdown() {
                                            this.backgroundOpen = !this.backgroundOpen;
                                        }
                                    }"
                                 class="col-span-1 p-4 bg-white text-gray-500 mt-4 border-t border-gray-200">
                                    <button
                                        x-ref="button"
                                        x-on:click="toggleBackgroundDropdown()"
                                        type="button"
                                        class="relative flex items-center whitespace-nowrap justify-center gap-2 py-2 rounded-lg  bg-white hover:bg-gray-50 text-gray-800 border  hover:border-gray-200 px-4"
                                    >
                                        <span>Hintergrundoptionen</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                                            <path fill-rule="evenodd" d="M4.22 6.22a.75.75 0 0 1 1.06 0L8 8.94l2.72-2.72a.75.75 0 1 1 1.06 1.06l-3.25 3.25a.75.75 0 0 1-1.06 0L4.22 7.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                    <div
                                        x-ref="backgroundPanel"
                                        x-show="backgroundOpen"
                                        x-transition.origin.top.left
                                        x-cloak
                                        class="mt-2 rounded-lg shadow-sm p-4 border border-gray-300 bg-white"
                                    >
                                        <h3 class="text-sm font-semibold text-gray-700 mb-2">Hintergrundbild hochladen</h3>
                                        <form wire:submit.prevent="uploadBackgroundImage" enctype="multipart/form-data">
                                                <input type="file" wire:model="backgroundImage" accept="image/*" class="block w-full text-sm text-gray-500
                                                    file:mr-4 file:py-2 file:px-4
                                                    file:rounded-full file:border-0
                                                    file:text-sm file:font-semibold
                                                    file:bg-blue-50 file:text-blue-700
                                                    hover:file:bg-blue-100">
                                                <button type="submit" class="mt-2 px-4 py-2 bg-blue-500 text-white text-sm font-semibold rounded-lg hover:bg-blue-600">Hochladen</button>
                                                <!-- Ladeindikator -->
                                                <div wire:loading wire:target="backgroundImage" class="text-blue-500 text-sm mt-2">
                                                    Bild wird hochgeladen...
                                                </div>
                                        </form>
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class=" max-h-full" style="position: relative;height: 100%;">
                @php
                    $svgWidth = $editingRetailSpace['dimensions']['width'];
                    $svgHeight = $editingRetailSpace['dimensions']['height'];
                        
                    function gcd($a, $b) {
                        return ($b == 0) ? $a : gcd($b, $a % $b);
                    }                          
                    $divisor = gcd($svgWidth, $svgHeight);                    
                    $ratio = ($svgWidth / $divisor) . "/" . ($svgHeight / $divisor);
                @endphp
                <div id="svg-editor-wrapper" class="svg-editor-wrapper max-h-full max-w-full overflow-hidden" 
                        style="aspect-ratio: {{$ratio}};"
                        viewBox="0 0 {{ $svgWidth }} {{ $svgHeight }}"
                        width="{{ $svgWidth }}" 
                        height="{{ $svgHeight }}" 
                        x-data x-init="() => { 
                            $watch('showEditRetailSpaceModal', value => { initializeInteract(); document.getElementById('bodyid').classList.toggle('overflow-hidden'); document.getElementById('main').classList.toggle('overflow-hidden'); });
                            $watch('selectedRetailSpaceEditorInit', value => { initializeInteract(); selectedRetailSpaceEditorInit = false; });
                        }"
                >
                    <div class="zoomist-wrapper">
                        <div id="zoomist-image" class="zoomist-image relative">
                            <svg xmlns="http://www.w3.org/2000/svg" id="editFloorPlan" 
                                class="w-full h-full"
                                style="aspect-ratio:{{ $ratio }};background-image: url('{{ $editingRetailSpace['backgroundimg']['url'] ?? '' }}');background-size: {{ $editingRetailSpace['backgroundimg']['size'] ?? 'cover' }};background-repeat: no-repeat;background-position: center;"
                                viewBox="0 0 {{ $svgWidth }} {{ $svgHeight }}"
                                width="{{ $svgWidth }}" height="{{ $svgHeight }}"
                                preserveAspectRatio="xMidYMid meet"
                                >
                                <!-- Dynamische Elemente anzeigen -->
                                @foreach($editingRetailSpace['elements']['shelves'] as $shelf)
                                @php
                                    $x = ($shelf['x'] / $svgWidth) * $svgWidth;
                                    $y = ($shelf['y'] / $svgHeight) * $svgHeight;
                                    $width = $shelf['width'];
                                    $height = $shelf['height'];
                                    $textX = $x + $width / 2; // Text mittig platzieren
                                    $textY = $y + $height / 2; // Text mittig platzieren
                                @endphp
                                <g  draggable="true"  class="svg-draggable" x="{{ $x }}" y="{{ $y }}" width="{{ $width }}" height="{{ $height }}" fill="{{ $shelf['color'] ?? '#4caf50' }}" data-type="shelf" data-id="{{ $shelf['element_id'] ?? null }}" data-text="{{ $shelf['text'] ?? 'Regal' }}" data-color="{{ $shelf['color'] ?? '#4caf50' }}">
                                    <rect  x="{{ $x }}" y="{{ $y }}" width="{{ $width }}" height="{{ $height }}" fill="{{ $shelf['color'] ?? '#4caf50' }}"></rect>
                                    <text  x="{{ $textX }}" y="{{ $textY }}" width="{{ $width }}" height="{{ $height }}"  font-family="Arial" font-size="2em" fill="#fff" text-anchor="middle" alignment-baseline="baseline">
                                        {{ $shelf['text'] ?? 'Regal' }}
                                    </text>
                                </g>
                                @endforeach
            
                                @foreach($editingRetailSpace['elements']['others'] as $element)
                                @php
                                    $x = ($element['x'] / $svgWidth) * $svgWidth;
                                    $y = ($element['y'] / $svgHeight) * $svgHeight;
                                    $width = $element['width'];
                                    $height = $element['height'];
                                    $textX = $x + $width / 2; // Text mittig platzieren
                                    $textY = $y + $height / 2; // Text mittig platzieren
                                @endphp
                                <g  draggable="true"  class="svg-draggable" x="{{ $x }}" y="{{ $y }}" width="{{ $width }}" height="{{ $height }}" fill="{{ $shelf['color'] ?? '#4caf50' }}" data-type="other" data-id="{{ $element['element_id'] ?? null }}" data-text="{{ $element['text'] ?? 'Eingang' }}" data-color="{{ $element['color'] ?? '#f44336' }}">
                                    <rect  x="{{ $x }}" y="{{ $y }}" width="{{ $width }}" height="{{ $height }}" fill="{{ $element['color'] ?? '#f44336' }}"></rect>
                                    <text  x="{{ $textX }}" y="{{ $textY }}" width="{{ $width }}" height="{{ $height }}"  font-family="Arial" font-size="2em" fill="#fff" text-anchor="middle" alignment-baseline="baseline">
                                        {{ $element['text'] ?? 'Eingang' }}
                                    </text>
                                </g>
                                @endforeach
                            </svg>
                            <!-- Popover für Aktionen -->
                            <div id="popoverMenu" class="hidden absolute bg-white shadow-lg rounded-lg p-2 text-sm">
                                <button id="renameButton" class="block w-full text-left px-2 py-1 hover:bg-gray-200">Umbenennen</button>
                                <button id="deleteButton" class="block w-full text-left px-2 py-1 hover:bg-gray-200 text-red-500">Löschen</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
        </x-slot>
        <x-slot name="footer">
        </x-slot>
    </x-dialog-modal>

@assets
<link href="/adminresources/css/zoomist.css" rel="stylesheet" />
<script src="/adminresources/js/interact.min.js"></script>
<script src="/adminresources/js/zoomist.js"></script>
@endassets


@script
<script>

window.initializeInteract = async function() {
        var zoomistImageHeight = 0;
        var elements = document.querySelectorAll('.zoomist-image');
        var lastElement = elements.length > 0 ? elements[elements.length - 1] : null;
        zoomistImageHeight = lastElement ? lastElement.offsetHeight : 0;
        console.log(zoomistImageHeight);
        var wire;
        var zoomistInstance;

        while (zoomistImageHeight == 0 && typeof wire == 'undefined') {
            await new Promise(resolve => setTimeout(resolve, 5)); 
            elements = document.querySelectorAll('.zoomist-image');
            lastElement = elements.length > 0 ? elements[elements.length - 1] : null;
            zoomistImageHeight = lastElement ? lastElement.offsetHeight : 0;
            wire = Livewire.find(document.querySelectorAll('[wire\\:id]')[document.querySelectorAll('[wire\\:id]').length-1].getAttribute('wire:id'));
        }

        // Hol den SVG-Wrapper
        var svgWrapperElements = document.querySelectorAll('.svg-editor-wrapper');
        var svgWrapper = svgWrapperElements.length > 0 ? svgWrapperElements[svgWrapperElements.length - 1] : null;

        if (!svgWrapper) {
            console.error("SVG-Wrapper nicht gefunden.");
            return;
        }


        function initializeZoomist() {

            if (zoomistInstance) {
                zoomistInstance.destroy();
            }

            // Initialisiere die neue Zoomist-Instanz
            zoomistInstance = new Zoomist(svgWrapper, {
                draggable: false,
                wheelable: true,
                controls: true,
                slider: true,
                zoomer: true,
                minScale: 0.7,
                maxScale: 2,
                pinchable: true,
                bounds: false,
                initScale: 0.9,
            });

        }

        initializeZoomist();
        

        console.log("initializeInteract");

        // Sicherstellen, dass das SVG vorhanden ist
        var svgElement = document.getElementById('editFloorPlan');
        if (!svgElement) {
            console.log("SVG-Element nicht gefunden.");
            return;
        }

       // Zuerst prüfen, ob `width` und `height`-Attribute vorhanden sind
       if (!svgElement.hasAttribute('width') || !svgElement.hasAttribute('height')) {
            console.log("SVG-Element hat keine definierte Größe.");

            // Versuche, die Größe aus der `viewBox` zu holen
            const viewBox = svgElement.getAttribute('viewBox'); // Holt den `viewBox`-String, falls vorhanden

            if (viewBox) {
                // `viewBox` besteht aus vier Werten: "min-x min-y width height"
                const viewBoxValues = viewBox.split(' ').map(Number);
                var svgWidth = viewBoxValues[2]; // Breite aus `viewBox`
                var svgHeight = viewBoxValues[3]; // Höhe aus `viewBox`

                if (svgWidth > 0 && svgHeight > 0) {
                    // Setze die `viewBox`-Daten als Größe
                    console.log(`Größe aus der viewBox gesetzt: Breite=${svgWidth}, Höhe=${svgHeight}`);
                } else {
                    // Fallback auf Standardwerte, wenn `viewBox` ungültig ist
                    var svgWidth = 2000;
                    var svgHeight = 2000;
                    console.log("viewBox-Daten ungültig. Standardgrößen gesetzt: Breite=500, Höhe=500");
                }
            } else {
                // Fallback auf Standardwerte, wenn keine `viewBox` vorhanden ist
                var svgWidth = 2000;
                var svgHeight = 2000;
                console.log("viewBox nicht vorhanden. Standardgrößen gesetzt: Breite=500, Höhe=500");
            }
        } else {
            // Logging, falls `width` und `height`-Attribute vorhanden sind
            var svgWidth = svgElement.getAttribute('width');
            var svgHeight = svgElement.getAttribute('height');
            console.log(`SVG-Element hat definierte Größe: Breite=${svgWidth}, Höhe=${svgHeight}`);
        }

        interact('.draggable-item').draggable({
            inertia: true,
            listeners: {
                move(event) {
                    const target = event.target;

                    // Berechnung der neuen Position
                    const rawX = (parseFloat(target.dataset.x) || 0) + event.dx;
                    const rawY = (parseFloat(target.dataset.y) || 0) + event.dy;

                    // SVG-Dimensionen und Elemente abrufen
                    const boundingRect = svgElement.getBoundingClientRect();
                    const svgWidth = parseFloat(svgElement.getAttribute('width')) || boundingRect.width;
                    const svgHeight = parseFloat(svgElement.getAttribute('height')) || boundingRect.height;
                    const elements = svgElement.querySelectorAll('g'); // Alle bestehenden Elemente im SVG abrufen

                    

                    // Setze die validierte Position
                    target.style.transform = `translate(${rawX}px, ${rawY}px)`;
                    target.dataset.x = rawX;
                    target.dataset.y = rawY;
                },
                end(event) {
                    const target = event.target;
                    // SVG-Dimensionen und Elemente abrufen
                    const svgWidth = parseFloat(svgElement.getAttribute('width')) || boundingRect.width;
                    const svgHeight = parseFloat(svgElement.getAttribute('height')) || boundingRect.height;

                    // Berechnung der Drop-Koordinaten im SVG
                    const boundingRect = svgElement.getBoundingClientRect();
                    const rawDropX = (event.clientX - boundingRect.left) * (svgWidth / boundingRect.width);
                    const rawDropY = (event.clientY - boundingRect.top) * (svgHeight / boundingRect.height);

                    const elements = svgElement.querySelectorAll('g'); // Alle bestehenden Elemente im SVG abrufen

                    // Validierte Position berechnen
                    const { x: validDropX, y: validDropY } = validatePosition(
                        rawDropX,
                        rawDropY,
                        parseFloat(target.dataset.width),
                        parseFloat(target.dataset.height),
                        svgWidth,
                        svgHeight,
                        elements
                    );

                    // Füge das Element ins SVG ein
                    const id = target.dataset.id || `${Date.now()}`;
                    const color = target.dataset.color || '#333';
                    const width = parseFloat(target.dataset.width) || 50;
                    const height = parseFloat(target.dataset.height) || 50;
                    let text;
                        if (target.dataset.type === 'shelf') {
                            // Zähle die bestehenden Regale im SVG
                            const shelfCount = svgElement.querySelectorAll('g[data-type="shelf"]').length;
                            text = (shelfCount + 1).toString(); // Nächste Regalnummer
                        } else {
                            text = target.dataset.text || ''; // Text für andere Elemente übernehmen
                        }
                    const type = target.dataset.type || 'other';
                    console.log(`addElementToSVG (dragged): X=${validDropX}, Y=${validDropY}, ID=${id}, Text=${text}`);
                    addElementToSVG(validDropX, validDropY, svgElement, id, color, width, height, text, type);

                },
            },
        });

        // Draggable- und bewegliche SVG-Elemente
        interact('.svg-draggable').draggable({
            inertia: true,
            listeners: {
                move(event) {
                    console.log(`svgElement: ${svgElement.getAttribute('width')}`);
                    // SVG-Größe in cm
                    var svgWidth = parseFloat(svgElement.getAttribute('width'));
                    var svgHeight = parseFloat(svgElement.getAttribute('height'));
                    const targetGroup = event.target; // Die `<g>`-Gruppe
                    const boundingRect = svgElement.getBoundingClientRect();
                    console.log(`svgWidth &  boundingRect.width :  X=${svgWidth}, Y=${boundingRect.width}`);
                    const scaleX = svgWidth / boundingRect.width;
                    const scaleY = svgHeight / boundingRect.height;
                    console.log(`move Element scale: ID=${targetGroup.dataset.id}, X=${scaleX}, Y=${scaleY}`);
                    const adjustedDx = event.dx * scaleX;
                    const adjustedDy = event.dy * scaleY;
                    console.log(`move Element koordinaten: ID=${targetGroup.dataset.id}, X=${adjustedDx}, Y=${adjustedDy}`);

                    const x = (parseFloat(targetGroup.getAttribute('x')) || 0) + adjustedDx;
                    const y = (parseFloat(targetGroup.getAttribute('y')) || 0) + adjustedDy;

                    // Wende die Positionsvalidierung an
                        const { x: validX, y: validY } = validatePosition(x, y, parseFloat(targetGroup.getAttribute('width')), parseFloat(targetGroup.getAttribute('height')),targetGroup.dataset.id);

                        // Setze die validierte Position auf das Element
                        targetGroup.setAttribute('x', validX);
                        targetGroup.setAttribute('y', validY);

                    const rect = targetGroup.querySelector('rect');
                    const text = targetGroup.querySelector('text');

                    if (rect) {
                        rect.setAttribute('x', x);
                        rect.setAttribute('y', y);
                    }

                    if (text) {
                        const width = parseFloat(targetGroup.getAttribute('width'));
                        const height = parseFloat(targetGroup.getAttribute('height'));
                        text.setAttribute('x', x + width / 2);
                        text.setAttribute('y', y + height / 2);
                    }

                    console.log(`move Element verschoben: ID=${targetGroup.dataset.id}, X=${x}, Y=${y}`);
                },
                end(event) {
                    
                    const targetGroup = event.target;
                    // Wende die Positionsvalidierung an
                    const { x: validX, y: validY } = validatePosition(targetGroup.getAttribute('x'), targetGroup.getAttribute('y'), parseFloat(targetGroup.getAttribute('width')), parseFloat(targetGroup.getAttribute('height')),targetGroup.dataset.id);

                    
                    const x = validX;
                    const y = validY;
                    const id = targetGroup.dataset.id;
                    const type = targetGroup.dataset.type;

                    

                    console.log(`dragend Element aktualisiert: ID=${id}, X=${x}, Y=${y}`);
                    
                },
            },
        });

        function addElementToSVG(x, y, svg, id, color, width, height, text, type) {

            // Position validieren
            const { x: newX, y: newY } = validatePosition(x, y, width, height, id);

            // Neues Element hinzufügen
            const group = document.createElementNS('http://www.w3.org/2000/svg', 'g');
            group.setAttribute('class', 'svg-draggable');
            group.setAttribute('data-id', id);
            group.setAttribute('data-type', type);
            group.setAttribute('data-text', text);
            group.setAttribute('data-color', color);
            group.setAttribute('x', newX);
            group.setAttribute('y', newY);
            group.setAttribute('width', width);
            group.setAttribute('height', height);

            const rect = document.createElementNS('http://www.w3.org/2000/svg', 'rect');
            rect.setAttribute('x', newX);
            rect.setAttribute('y', newY);
            rect.setAttribute('width', width);
            rect.setAttribute('height', height);
            rect.setAttribute('fill', color);

            const textElement = document.createElementNS('http://www.w3.org/2000/svg', 'text');
            textElement.setAttribute('x', newX + width / 2);
            textElement.setAttribute('y', newY + height / 2);
            textElement.setAttribute('width', width);
            textElement.setAttribute('height', height);
            textElement.setAttribute('font-family', 'Arial');
            textElement.setAttribute('font-size', '2em');
            textElement.setAttribute('fill', '#fff');
            textElement.setAttribute('text-anchor', 'middle');
            textElement.setAttribute('alignment-baseline', 'middle');
            textElement.textContent = text;

            group.appendChild(rect);
            group.appendChild(textElement);
            svg.appendChild(group);

            console.log(`Element hinzugefügt: ID=${id}, X=${newX}, Y=${newY}, Typ=${type}`);
        }


        function validatePosition(x, y, width, height, movingElementId = null) {
            let validX = x;
            let validY = y;

            // SVG und seine Elemente abrufen
            const svg = document.getElementById('editFloorPlan');
            if (!svg) {
                console.error('SVG-Element nicht gefunden.');
                return { x: 0, y: 0 }; // Fallback-Position
            }

            const elements = svg.querySelectorAll('g'); // Alle SVG-Gruppen abrufen
            const svgWidth = parseFloat(svg.getAttribute('width'));
            const svgHeight = parseFloat(svg.getAttribute('height'));

            // Kollisionsprüfung aktivieren
            let collisionDetected = elements.length > 0;

            // Priorisierte Bewegungsrichtungen: rechts, unten, links, oben
            const directions = [
                { dx: 10, dy: 0 },   // Rechts
                { dx: 0, dy: 10 },   // Unten
                { dx: -10, dy: 0 },  // Links
                { dx: 0, dy: -10 },  // Oben
            ];

            // Schutz vor Endlosschleifen
            let iterationLimit = 500; // Maximale Anzahl von Anpassungsversuchen

            while (
                (collisionDetected || validX < 0 || validY < 0 || validX + width > svgWidth || validY + height > svgHeight) &&
                iterationLimit > 0
            ) {
                collisionDetected = false;

                // Prüfen auf Kollisionen mit bestehenden Elementen
                for (const element of elements) {
                    // Überspringe das aktuell bewegte Element
                    if (movingElementId && element.dataset.id === movingElementId) {
                        continue;
                    }

                    const existingX = parseFloat(element.getAttribute('x'));
                    const existingY = parseFloat(element.getAttribute('y'));
                    const existingWidth = parseFloat(element.getAttribute('width'));
                    const existingHeight = parseFloat(element.getAttribute('height'));

                    if (
                        validX < existingX + existingWidth &&
                        validX + width > existingX &&
                        validY < existingY + existingHeight &&
                        validY + height > existingY
                    ) {
                        collisionDetected = true;

                        // Neue Position basierend auf verfügbaren Richtungen suchen
                        for (const direction of directions) {
                            const newX = validX + direction.dx;
                            const newY = validY + direction.dy;

                            // Prüfen, ob die neue Position innerhalb des SVG bleibt
                            if (
                                newX >= 0 &&
                                newY >= 0 &&
                                newX + width <= svgWidth &&
                                newY + height <= svgHeight
                            ) {
                                validX = newX;
                                validY = newY;
                                break; // Richtung gefunden, aus der Schleife ausbrechen
                            }
                        }

                        // Kollision erkannt, aber keine Lösung in dieser Richtung gefunden
                        break;
                    }
                }

                // Prüfung auf Austritt aus dem SVG
                if (validX < 0) validX = 0;
                if (validY < 0) validY = 0;
                if (validX + width > svgWidth) validX = svgWidth - width;
                if (validY + height > svgHeight) validY = svgHeight - height;

                iterationLimit--;
            }

            if (iterationLimit === 0) {
                console.warn('Maximale Anzahl an Anpassungen erreicht. Position möglicherweise nicht optimal.');
            }

            return { x: validX, y: validY };
        }







        // Doppelklick-Listener für Vorlagenelemente
        document.querySelectorAll('.draggable-item').forEach(item => {
            item.addEventListener('dblclick', event => {
                if (zoomistInstance) {
                    const currentData = zoomistInstance.getImageData();
                    const boundingRect = svgElement.getBoundingClientRect();
                    const scaleX = svgWidth / boundingRect.width;
                    const scaleY = svgHeight / boundingRect.height;

                    // Drop-Koordinaten in der Mitte des SVGs
                    const dropX = (svgWidth / 2 );
                    const dropY = (svgHeight / 2 );

                    // Breite und Höhe aus data-Attributen lesen
                    
                    const width = item.dataset.width; // Default in Pixel
                    const height = item.dataset.height;

                    // Unveränderte ID des Elements
                    let uniqueId = `${Date.now()}`;

                    // Text basierend auf dem Element-Typ ermitteln
                    let text;
                    if (item.dataset.type === "shelf") {
                        // Dynamische Nummerierung der Regale
                        text = svgElement.querySelectorAll('g[data-type="shelf"]').length + 1;
                    } else {
                        // Text aus dem data-text-Attribut für andere Elemente
                        text = item.dataset.text || '';
                    }
                    // Element ins SVG einfügen
                    console.log(`addElementToSVG (Doppelklick): X=${dropX}, Y=${dropY}, ID=${uniqueId}, Text=${text}`);
                    addElementToSVG(dropX, dropY, svgElement, uniqueId, item.dataset.color || '#333', width, height, text, item.dataset.type || 'other');

                }
            });
        });

        const svg = document.getElementById('editFloorPlan');
        const popoverMenu = document.getElementById('popoverMenu');
        const renameButton = document.getElementById('renameButton');
        const deleteButton = document.getElementById('deleteButton');

        let currentElement = null; // Das aktuell ausgewählte SVG-Element

        // Funktion: Listener für Klick-Events auf `<g>`-Elemente hinzufügen
        function addClickListenerToElement(g) {
            g.addEventListener('dblclick', (event) => {
                console.log(event);
                event.stopPropagation(); // Verhindert das Auslösen von Events auf Elternknoten

                currentElement = g;

                // Popover relativ zur Mausposition anzeigen
                popoverMenu.style.left = `${event.clientX}px`;
                popoverMenu.style.top = `${event.clientY}px`;
                popoverMenu.classList.remove('hidden');
            });
        }

        // `<g>`-Elemente initialisieren
        svg.querySelectorAll('g').forEach(addClickListenerToElement);

        // Event-Listener für Umbenennen
        renameButton.addEventListener('click', () => {
            if (currentElement) {
                const newText = prompt('Neuer Name:', currentElement.querySelector('text')?.textContent || '');
                if (newText !== null) {
                    currentElement.querySelector('text').textContent = newText; // Text aktualisieren
                }
                popoverMenu.classList.add('hidden'); // Popover ausblenden
            }
        });

        // Event-Listener für Löschen
        deleteButton.addEventListener('click', () => {
            if (currentElement) {
                currentElement.remove(); // Element entfernen
                popoverMenu.classList.add('hidden'); // Popover ausblenden
            }
        });

        // Popover ausblenden, wenn außerhalb des Popovers oder SVGs geklickt wird
        document.addEventListener('click', (event) => {
            if (!popoverMenu.contains(event.target) && !svg.contains(event.target)) {
                popoverMenu.classList.add('hidden');
                currentElement = null;
            }
        });
           
  
}





window.syncRetailSpaceFromSVG = async function() {
    // SVG-Element direkt abrufen
    const svgElement = document.getElementById('editFloorPlan');
    if (!svgElement) {
        console.error("SVG-Element nicht gefunden.");
        return;
    }

    // Zugriff auf die Livewire-Instanz
    let wire;
    while (typeof wire == 'undefined') {
        await new Promise(resolve => setTimeout(resolve, 5)); 
        wire = Livewire.find(
            document.querySelectorAll('[wire\\:id]')[document.querySelectorAll('[wire\\:id]').length - 1]
                .getAttribute('wire:id')
        );
    }
    if (!wire) {
        console.error("Livewire-Instanz nicht verfügbar.");
        return;
    }

    // Alte Daten aus editingRetailSpace abrufen
    const oldShelves = wire.get('editingRetailSpace.elements.shelves') || [];
    const oldOthers = wire.get('editingRetailSpace.elements.others') || [];

    // Elemente sammeln
    const shelves = [];
    const others = [];
    const elements = svgElement.querySelectorAll('g');

    elements.forEach((element) => {
        const type = element.getAttribute('data-type') || 'other';
        const x = parseFloat(element.getAttribute('x')) || 0;
        const y = parseFloat(element.getAttribute('y')) || 0;
        const width = parseFloat(element.getAttribute('width')) || 0;
        const height = parseFloat(element.getAttribute('height')) || 0;
        const elementId = element.getAttribute('data-id') || Date.now().toString(); // Fallback: Zeitstempel als ID
        const text = element.getAttribute('data-text') || '';
        const color = element.getAttribute('data-color') || '#000';

        const newElement = {
            element_id: elementId,
            x: x,
            y: y,
            width: width,
            height: height,
            text: text,
            color: color,
        };

        // Prüfen, ob das Element bereits existiert
        const oldElement =
            (type === 'shelf' ? oldShelves : oldOthers).find((el) => el.element_id === elementId);

        if (oldElement) {
            // Vergleichen und nur aktualisieren, wenn Daten geändert wurden
            if (
                oldElement.x !== newElement.x ||
                oldElement.y !== newElement.y ||
                oldElement.width !== newElement.width ||
                oldElement.height !== newElement.height ||
                oldElement.text !== newElement.text ||
                oldElement.color !== newElement.color
            ) {
                console.log(`Element aktualisiert: ${elementId}`);
            }
        } else {
            console.log(`Neues Element hinzugefügt: ${elementId}`);
        }

        // Sortiere nach Typ
        if (type === 'shelf') {
            shelves.push(newElement);
        } else {
            others.push(newElement);
        }
    });

    // Synchronisiere nur die Elemente in editingRetailSpace
    wire.editingRetailSpace.elements.shelves = shelves;
    wire.editingRetailSpace.elements.others = others;
    wire.dispatch('saveRetailSpaceLayout');

    console.log("SVG-Elemente erfolgreich synchronisiert mit editingRetailSpace.");
};



        
</script>
@endscript
















    <x-dialog-modal wire:model="showAddSalesAreaModal">
        <x-slot name="title">
            <h3 class="text-xl font-semibold mb-4">Neue Verkaufsfläche Hinzufügen</h3>
        </x-slot>
        <x-slot name="content">
            <form wire:submit.prevent="createSalesArea">
                <!-- Name der Verkaufsfläche -->
                <div class="mb-4">
                    <label for="salesAreaName" class="block text-sm font-medium text-gray-700">Name der Verkaufsfläche</label>
                    <input type="text" id="salesAreaName" wire:model="newSalesArea.name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    @error('newSalesArea.name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <!-- Höhe und Breite in cm -->
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="salesAreaWidth" class="block text-sm font-medium text-gray-700">Breite (cm)</label>
                        <input type="number" id="salesAreaWidth" wire:model="newSalesArea.width" step="0.01" min="0" placeholder="z. B. 2000" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        @error('newSalesArea.width') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="salesAreaHeight" class="block text-sm font-medium text-gray-700">Höhe (cm)</label>
                        <input type="number" id="salesAreaHeight" wire:model="newSalesArea.height" step="0.01" min="0" placeholder="z. B. 3000" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        @error('newSalesArea.height') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>
                <!-- Buttons -->
                <div class="flex justify-end">
                    <button type="button" @click="showAddSalesAreaModal = false" class="text-gray-500 hover:text-gray-700 mr-2">Abbrechen</button>
                    <button type="submit" class="text-green-500 hover:text-green-700">Hinzufügen</button>
                </div>
            </form>
        </x-slot>
        <x-slot name="footer">
        </x-slot>
    </x-dialog-modal>






    <!-- Modales Fenster: Neuer Standort Hinzufügen -->
    <x-dialog-modal wire:model="showAddLocationModal">
        <x-slot name="title">
            <h3 class="text-xl font-semibold mb-4">Neuen Standort Hinzufügen</h3>
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="addLocation">
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Name des Standorts</label>
                    <input type="text" id="name" wire:model="newLocation.name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    @error('newLocation.name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="mb-4">
                    <label for="address" class="block text-sm font-medium text-gray-700">Adresse</label>
                    <input type="text" id="address" wire:model="newLocation.address" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    @error('newLocation.address') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="mb-4">
                    <label for="city" class="block text-sm font-medium text-gray-700">Stadt</label>
                    <input type="text" id="city" wire:model="newLocation.city" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    @error('newLocation.city') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="mb-4">
                    <label for="state" class="block text-sm font-medium text-gray-700">Bundesland</label>
                    <input type="text" id="state" wire:model="newLocation.state" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    @error('newLocation.state') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="mb-4">
                    <label for="postal_code" class="block text-sm font-medium text-gray-700">Postleitzahl</label>
                    <input type="text" id="postal_code" wire:model="newLocation.postal_code" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    @error('newLocation.postal_code') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="mb-4">
                    <label for="country" class="block text-sm font-medium text-gray-700">Land</label>
                    <input type="text" id="country" wire:model="newLocation.country" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    @error('newLocation.country') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="mb-4">
                    <label for="phone_number" class="block text-sm font-medium text-gray-700">Telefonnummer</label>
                    <input type="text" id="phone_number" wire:model="newLocation.phone_number" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    @error('newLocation.phone_number') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="flex justify-end">
                    <button @click="showAddLocationModal = false" type="button" class="text-gray-500 hover:text-gray-700 mr-2">Abbrechen</button>
                    <button type="submit" class="text-green-500 hover:text-green-700">Hinzufügen</button>
                </div>
            </form>
        </x-slot>
        <x-slot name="footer">
        </x-slot>
    </x-dialog-modal>


</div>
