<div>
    <x-dialog-modal wire:model="showModal">
        <x-slot name="title">
            Neuen Benutzer erstellen
        </x-slot>

        <x-slot name="content">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" wire:model="name" class="mt-1 block w-full border rounded px-4 py-2">
                    @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">E-Mail</label>
                    <input type="email" wire:model="email" class="mt-1 block w-full border rounded px-4 py-2">
                    @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>

        </x-slot>

        <x-slot name="footer">
            <div class="flex items-center space-x-3">
                <x-button wire:click="createUser" class="btn-xs text-sm">Benutzer erstellen</x-button>
                <x-button wire:click="$set('showModal', false)" class="btn-xs text-sm">Abbrechen</x-button>
            </div>
        </x-slot>
    </x-dialog-modal>
</div>
