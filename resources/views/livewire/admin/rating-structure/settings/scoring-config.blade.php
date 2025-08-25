<x-settings-collapse>
    <x-slot name="trigger">
        Scoring Konfiguration
    </x-slot>

    <x-slot name="content">
        @if (session()->has('success'))
            <div class="text-green-600 mb-4">{{ session('success') }}</div>
        @endif

        <form wire:submit.prevent="saveSettings" class="space-y-6">
            <div>
                <label for="regulation_speed" class="block text-sm font-medium text-gray-700">Regulierungsgeschwindigkeit</label>
                <input type="range" id="regulation_speed" wire:model="regulation_speed" min="0" max="100" class="mt-1 w-full">
                <span class="text-sm text-gray-600">Wert: {{ $regulation_speed }}</span>
                @error('regulation_speed') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
            </div>

            <div>
                <label for="customer_service" class="block text-sm font-medium text-gray-700">Kundenservice</label>
                <input type="range" id="customer_service" wire:model="customer_service" min="0" max="100" class="mt-1 w-full">
                <span class="text-sm text-gray-600">Wert: {{ $customer_service }}</span>
                @error('customer_service') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
            </div>

            <div>
                <label for="fairness" class="block text-sm font-medium text-gray-700">Fairness</label>
                <input type="range" id="fairness" wire:model="fairness" min="0" max="100" class="mt-1 w-full">
                <span class="text-sm text-gray-600">Wert: {{ $fairness }}</span>
                @error('fairness') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
            </div>

            <div>
                <label for="transparency" class="block text-sm font-medium text-gray-700">Transparenz</label>
                <input type="range" id="transparency" wire:model="transparency" min="0" max="100" class="mt-1 w-full">
                <span class="text-sm text-gray-600">Wert: {{ $transparency }}</span>
                @error('transparency') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
            </div>

            <div>
                <label for="overall_satisfaction" class="block text-sm font-medium text-gray-700">Gesamtzufriedenheit</label>
                <input type="range" id="overall_satisfaction" wire:model="overall_satisfaction" min="0" max="100" class="mt-1 w-full">
                <span class="text-sm text-gray-600">Wert: {{ $overall_satisfaction }}</span>
                @error('overall_satisfaction') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
            </div>

            <div class="flex justify-end">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Speichern
                </button>
            </div>
        </form>
    </x-slot>
</x-settings-collapse>
