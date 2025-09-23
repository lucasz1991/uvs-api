<div class="mt-8 border-t pt-6">
    <h3 class="text-xl font-semibold mb-4">UVS Datenbank Test</h3>

    <div class="flex gap-2">
        <x-button wire:click="testConnection">
            Verbindung & Struktur prüfen
        </x-button>
        @if ($connected)
        <x-button wire:click="exportText">
            Als Text Datei exportieren
        </x-button>
        @endif
    </div>

    @if ($errorMessage)
        <div class="mt-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            Fehler: {{ $errorMessage }}
        </div>
    @endif

    @if ($connected)
        <div class="mt-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            ✅ Verbindung erfolgreich
        </div>

        <div class="mt-6">
            <p class="text-sm text-gray-600 mb-2">
                Gefundene Tabellen: <strong>{{ count($tables) }}</strong>
            </p>

            <div class="space-y-4">
                @foreach ($tables as $table)
                    <details class="border rounded bg-white shadow-sm">
                        <summary class="cursor-pointer px-4 py-3 font-semibold">
                            {{ $table['name'] }} 
                            <span class="ml-2 text-sm text-gray-500">
                                ({{ count($table['columns']) }} Spalten)
                            </span>
                        </summary>
                        <div class="p-4 overflow-x-auto">
                            <table class="w-full text-sm border">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="border px-2 py-1 text-left">Spalte</th>
                                        <th class="border px-2 py-1 text-left">Typ</th>
                                        <th class="border px-2 py-1 text-left">Länge</th>
                                        <th class="border px-2 py-1 text-left">NULL</th>
                                        <th class="border px-2 py-1 text-left">Default</th>
                                        <th class="border px-2 py-1 text-left">Key</th>
                                        <th class="border px-2 py-1 text-left">Extra</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($table['columns'] as $col)
                                        <tr>
                                            <td class="border px-2 py-1">{{ $col['name'] }}</td>
                                            <td class="border px-2 py-1">{{ $col['type'] }}</td>
                                            <td class="border px-2 py-1">{{ $col['length'] ?? '–' }}</td>
                                            <td class="border px-2 py-1">{{ $col['nullable'] ? 'Ja' : 'Nein' }}</td>
                                            <td class="border px-2 py-1">{{ is_null($col['default']) ? '–' : $col['default'] }}</td>
                                            <td class="border px-2 py-1">{{ $col['key'] ?: '–' }}</td>
                                            <td class="border px-2 py-1">{{ $col['extra'] ?: '–' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </details>
                @endforeach
            </div>
        </div>
    @endif
</div>
