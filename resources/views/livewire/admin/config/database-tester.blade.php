<div class="mt-8 border-t pt-6">
    <h3 class="text-xl font-semibold mb-4">UVS Datenbank Test</h3>

    <div class="flex flex-wrap items-center gap-3">
        <x-button wire:click="testConnection">
            Verbindung & Struktur prüfen
        </x-button>

        <label class="inline-flex items-center gap-2 text-sm">
            <input type="checkbox" class="rounded border-gray-300"
                   wire:model.live="exactCounts">
            <span>Exakte Row-Counts (langsam)</span>
        </label>

        @if ($connected)
            <x-button wire:click="exportText">
                Als Text-Datei exportieren
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
                <span class="ml-3 inline-flex items-center rounded-full px-2 py-0.5 text-xs border
                    {{ $exactCounts ? 'bg-blue-50 border-blue-200 text-blue-700' : 'bg-amber-50 border-amber-200 text-amber-700' }}">
                    {{ $exactCounts ? 'Row-Count: exakt' : 'Row-Count: geschätzt' }}
                </span>
            </p>

            <div class="space-y-4">
                @foreach ($tables as $table)
                    <details class="border rounded bg-white shadow-sm">
                        <summary class="cursor-pointer px-4 py-3 font-semibold flex flex-wrap gap-2 items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="text-gray-900">{{ $table['name'] }}</span>
                                <span class="ml-2 text-sm text-gray-500">
                                    ({{ count($table['columns']) }} Spalten)
                                </span>
                            </div>

                            <div class="flex items-center gap-2">
                                @php $cnt = $table['row_count'] ?? null; @endphp

                                @if(!empty($table['order_by']))
                                    <span class="text-xs px-2 py-0.5 rounded-full border bg-slate-50 text-slate-700 border-slate-200">
                                        ORDER BY {{ $table['order_by'] }} DESC
                                    </span>
                                @else
                                    <span class="text-xs px-2 py-0.5 rounded-full border bg-slate-50 text-slate-400 border-slate-200">
                                        ORDER BY — (LIMIT 1)
                                    </span>
                                @endif

                                <span class="text-xs px-2 py-0.5 rounded-full
                                    {{ ($table['row_count_type'] ?? 'estimated') === 'exact'
                                        ? 'bg-blue-50 text-blue-700 border border-blue-200'
                                        : 'bg-amber-50 text-amber-700 border border-amber-200' }}">
                                    Rows: {{ is_null($cnt) ? 'n/a' : number_format($cnt, 0, ',', '.') }}
                                    – {{ $table['row_count_type'] === 'exact' ? 'exakt' : 'geschätzt' }}
                                </span>
                            </div>
                        </summary>

                        <div class="p-4 overflow-x-auto">
                            {{-- Spaltendefinitionen --}}
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

                            {{-- Beispiel-Datensatz --}}
                            @if (!empty($table['sample']))
                                <div class="mt-4">
                                    <div class="text-xs font-semibold text-gray-600 mb-1">
                                        Beispielzeile {{ $table['order_by'] ? '(ORDER BY '.$table['order_by'].' DESC)' : '' }}
                                    </div>
                                    <pre class="text-xs bg-gray-50 border rounded p-2 overflow-x-auto">
{{ json_encode($table['sample'], JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT) }}
                                    </pre>
                                </div>
                            @else
                                <div class="mt-4 text-xs text-gray-500">
                                    Keine Beispielzeile gefunden (Tabelle leer oder nicht lesbar).
                                </div>
                            @endif
                        </div>
                    </details>
                @endforeach
            </div>
        </div>
    @endif
</div>
