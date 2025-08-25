<div class="space-y-6">
  <button
    wire:click="$dispatch('open-api-key-form', { userId: {{ $user->id }} })"
    class="px-2 py-1 bg-blue-600 text-white rounded"
  >
    Neuer Schlüssel
  </button>

  <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
    @forelse ($keys as $key)
      <div class="border bg-white rounded p-4 space-y-2 relative">
        <div class="font-medium">{{ $key->name }}</div>
        <div class="text-sm text-gray-500">ID: {{ $key->id }}</div>
        <div class="text-sm">Aktiv: {{ $key->active ? 'Ja' : 'Nein' }}</div>
        <div class="text-sm">Ablauf: {{ $key->expires_at?->format('d.m.Y H:i') ?? '—' }}</div>
        <div class="text-sm">Zuletzt genutzt: {{ $key->last_used_at?->format('d.m.Y H:i') ?? '—' }}</div>

        @php $abilities = $key->settings['abilities'] ?? []; @endphp
        <div class="text-sm">
          Abilities:
          @if ($abilities && is_array($abilities))
            <div class="mt-1">
              @foreach ($abilities as $ab)
                <span class="inline-block text-xs bg-gray-100 px-2 py-1 rounded mr-1 mb-1">{{ $ab }}</span>
              @endforeach
            </div>
          @else 
            <span class="text-gray-400">Keine</span>
          @endif
        </div>
        {{-- Actions: Edit --}}
        <div class="absolute top-1 right-2">
            <x-dropdown class="" :width="'min'">
                <x-slot name="trigger">
                    <button type="button" class="inline-flex items-center px-2 py-1 bg-gray-200 text-gray-600 rounded hover:bg-gray-300">
                        <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7.75 4H19M7.75 4a2.25 2.25 0 0 1-4.5 0m4.5 0a2.25 2.25 0 0 0-4.5 0M1 4h2.25m13.5 6H19m-2.25 0a2.25 2.25 0 0 1-4.5 0m4.5 0a2.25 2.25 0 0 0-4.5 0M1 10h11.25m-4.5 6H19M7.75 16a2.25 2.25 0 0 1-4.5 0m4.5 0a2.25 2.25 0 0 0-4.5 0M1 16h2.25"></path>
                        </svg>
                    </button>
                </x-slot>
                <x-slot name="content">
                    <x-dropdown-link wire:click="$dispatch('open-api-key-form', { userId: {{ $user->id }}, apiKeyId: {{ $key->id }} })" class="flex items-center gap-2 cursor-pointer">
                        Bearbeiten
                    </x-dropdown-link>
                    <x-dropdown-link wire:click="deleteKey({{ $key->id }})" wire:confirm="Bist du sicher, dass du diesen API-Schlüssel löschen möchtest?" class="flex items-center gap-2 cursor-pointer">
                        Löschen
                    </x-dropdown-link>
                </x-slot>
            </x-dropdown>
        </div>
      </div>
    @empty
      <div class="col-span-full text-sm text-gray-500">
        Keine API-Schlüssel vorhanden.
      </div>
    @endforelse
  </div>

  {{ $keys->links() }}

  {{-- Modal einmal einbinden --}}
  <livewire:users.ap-management.api-key-form-modal />
</div>
