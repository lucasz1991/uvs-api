<div class="overflow-auto">
    <h1 class="text-xl font-bold mb-4">Activity Logs</h1>
    <div class="mb-4">
        <select wire:model.live="filterMode" class="border rounded px-4 py-2">
            <option value="all">Alle</option>
            <option value="user">Benutzer</option>
            <option value="guest">Gäste</option>
        </select>
    </div>
    <div class="flex justify-between mb-4">
        <input 
            type="text" 
            placeholder="Suchen..." 
            class="border rounded px-4 py-2" 
            wire:model.live.debounce.300ms="search"
        />
        <select wire:model.live="perPage" class="border rounded px-4 py-2">
            <option value="5">5 pro Seite</option>
            <option value="10">10 pro Seite</option>
            <option value="25">25 pro Seite</option>
            <option value="50">50 pro Seite</option>
        </select>
    </div>
    <div class="overflow-auto">
        <table class="table-auto w-full border-collapse border border-gray-200">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border px-4 py-2">Datum</th>
                    <th class="border px-4 py-2">Beschreibung</th>
                    <th class="border px-4 py-2">Verursacher</th>
                    <th class="border px-4 py-2">IP-Adresse</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($activities as $activity)
                    <tr>
                        <td class="border px-4 py-2">{{ $activity->created_at->format('d.m.Y H:i:s') }}</td>
                        <td class="border px-4 py-2 truncate max-w-xs" title="{{ $activity->description }}">
                            {{ Str::limit($activity->description, 50) }}
                        </td>
                        <td class="border px-4 py-2">
                            {{ $activity->causer ? $activity->causer->name : 'Gast' }}
                        </td>
                        <td class="border px-4 py-2">
                            {{ $activity->properties['ip'] ?? 'Unbekannt' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="border px-4 py-2 text-center">Keine Einträge gefunden</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $activities->links() }}
    </div>
</div>
