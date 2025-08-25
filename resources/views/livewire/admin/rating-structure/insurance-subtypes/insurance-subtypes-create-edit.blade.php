<div>
    <x-dialog-modal wire:model="showModal">
        <x-slot name="title">
            @if($insuranceSubtypeId)
                Versicherungs-Untertyp bearbeiten
            @else
                Neuen Versicherungs-Untertyp erstellen
            @endif
        </x-slot>

        <x-slot name="content">

            <div class="space-y-4">
                <div class="mb-4 grid grid-cols-5 gap-4">
                    <div class="col-span-4 mb-4">
                        <label class="block text-sm font-medium text-gray-700">Name</label>
                        <input type="text" wire:model.defer="name" class="mt-1 block w-full border rounded px-4 py-2">
                        @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-span-1 mb-6 flex items-end">
                        <label for="is_active" class="flex items-end cursor-pointer">
                            <input 
                                id="is_active" 
                                name="is_active" 
                                type="checkbox" 
                                wire:model.live="is_active" 
                                class="sr-only peer" 
                            />
                            <div class="relative w-9 h-5 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                            <span class="ms-3 text-sm font-medium ">Aktiv</span>
                        </label>
                    </div>
                </div>
                <div class="mb-8">
                    <label class="block text-sm font-medium text-gray-700">Beschreibung</label>
                    <textarea wire:model.defer="description" rows="3" class="mt-1 block w-full border rounded px-4 py-2"></textarea>
                    @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>



                <!-- Accordion -->
                <div x-data="{ openTab: 'incuranceType' }" class="">
                    <!-- Tabs -->
                    <div class="flex -mb-[1px] space-x-2">
                        <button @click="openTab = 'incuranceType'" 
                            :class="openTab === 'incuranceType' ? 'text-blue-600 border-blue-600 bg-gray-100 border-b-0' : 'text-gray-500 bg-white'" 
                            class="px-4 py-2  text-sm font-medium transition-all border  border-gray-300 rounded-t-lg z-30">
                            <h1 class="flex items-center justify-center">
                                <span class="w-max">Versicherungstypen</span>
                                <span class="ml-2 bg-white text-sky-600 text-xs shadow border border-sky-200 font-bold aspect-square px-2 py-1 flex items-center justify-center rounded-full h-7 leading-none">
                                    {{ count($assignedInsuranceTypes) }}
                                </span>
                            </h1>
                        </button>
                    </div>

                    <!-- incuranceType Settings -->
                    <div x-show="openTab === 'incuranceType'">
                        <div class="space-y-4 bg-gray-100 p-4 rounded-b-lg rounded-se-lg border border-gray-300  z-10">
                                <!-- Sortierbare Liste -->
                                <div class="mt-4" x-data="{ addIncuranceTypeOpen: false }">
                                    <div class="flex justify-end items-center mb-3">
                                        
                                        <!-- Dropdown für neue Versicherung -->
                                        <div class="relative" >
                                            <button @click="addIncuranceTypeOpen = !addIncuranceTypeOpen" type="button" class="flex items-center text-sm px-2 py-1 bg-white border rounded shadow-sm hover:bg-gray-50">
                                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path>
                                                </svg>
                                                Hinzufügen
                                            </button>
                                            
                                        </div>
                                    </div>
                                    <div x-show="addIncuranceTypeOpen" @click.away="addIncuranceTypeOpen = false" class="mt-2 mb-5  bg-white border rounded">
                                                <div class="p-2 flex items-center space-x-3">
                                                    <select wire:model="insuranceTypeToAdd" class="w-full border rounded px-2 py-1">
                                                        <option value="">Bitte auswählen</option>
                                                        @foreach ($availableInsuranceTypes as $availableInsuranceType)
                                                            <option value="{{ $availableInsuranceType->id }}">{{ $availableInsuranceType->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <button  wire:click="addInsuranceType" class="flex items-center text-sm px-2 py-1 bg-white border rounded shadow-sm hover:bg-gray-50">
                                                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path>
                                                        </svg>
                                                        
                                                    </button>
                                                </div>
                                            </div>
                                    <div class="min-w-max lg:min-w-full max-h-96 overflow-y-scroll p-3 bg-white scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100 shadow-inner border scroll-container" x-sort="$dispatch('reorderAssignedInsuranceTypes', { item: $item, position: $position })">
                                        @foreach ($assignedInsuranceTypes as $assignedInsuranceType)
                                            <div x-sort:item="{ id: {{ $assignedInsuranceType['id'] }}, name: '{{ $assignedInsuranceType['name'] }}' }">
                                                <div class="bg-blue-50 px-3 py-2 rounded flex justify-between items-center border mb-2">
                                                    <span class="text-sm">{{ $assignedInsuranceType['name'] }}</span>
                                                    <button type="button" class="text-red-500" wire:click="removeInsurance({{ $assignedInsuranceType['id'] }})">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x-circle"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>                                                
                                                    </button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                </div>
                        </div>
                    </div>
                </div>
            </div>

        </x-slot>

        <x-slot name="footer">
            <div class="flex items-center space-x-3">
                <x-secondary-button wire:click="$set('showModal', false)">
                    Abbrechen
                </x-secondary-button>

                <x-button wire:click="save">
                    Speichern
                </x-button>
            </div>
        </x-slot>
    </x-dialog-modal>
</div>
