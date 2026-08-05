<div>
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

<div class="mt-8 border-t pt-6">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h3 class="text-xl font-semibold">UVS-Dateizugriff Test</h3>
            <p class="mt-1 text-sm text-gray-600">
                Prueft direkt im PHP-/IIS-Prozess, ob Angebots- und Vertrags-PDFs im UVS-Verzeichnis gelesen werden koennen.
            </p>
        </div>

        <x-button wire:click="testDocumentAccess" wire:loading.attr="disabled" wire:target="testDocumentAccess">
            <span wire:loading.remove wire:target="testDocumentAccess">PDF-Zugriff pruefen</span>
            <span wire:loading wire:target="testDocumentAccess">Pruefung laeuft...</span>
        </x-button>
    </div>

    @if ($documentAccessError)
        <div class="mt-4 rounded border border-red-400 bg-red-100 px-4 py-3 text-red-700">
            Fehler: {{ $documentAccessError }}
        </div>
    @endif

    @if ($documentRoot)
        <div class="mt-4 rounded border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-700">
            <span class="font-medium">Konfigurierter UVS-Pfad:</span>
            <code class="break-all">{{ $documentRoot }}</code>
        </div>
    @endif

    @if ($documentAccessResults)
        <div class="mt-4 rounded border px-4 py-3 {{ $documentAccessOk ? 'border-green-400 bg-green-100 text-green-800' : 'border-red-400 bg-red-100 text-red-800' }}">
            {{ $documentAccessOk
                ? 'Zugriff erfolgreich: Angebote und Vertraege sind fuer die API lesbar.'
                : 'Zugriff fehlgeschlagen: Mindestens ein PDF-Verzeichnis oder eine PDF-Datei ist nicht lesbar.' }}
        </div>

        <div class="mt-4 grid gap-4 md:grid-cols-2">
            @foreach ($documentAccessResults as $result)
                <div class="rounded border bg-white p-4 {{ $result['ok'] ? 'border-green-300' : 'border-red-300' }}" wire:key="document-access-{{ $result['type'] }}">
                    <div class="flex items-center justify-between gap-3">
                        <h4 class="font-semibold text-gray-900">{{ $result['label'] }}</h4>
                        <span class="rounded-full px-2 py-0.5 text-xs font-medium {{ $result['ok'] ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ $result['ok'] ? 'OK' : 'Fehler' }}
                        </span>
                    </div>

                    <dl class="mt-3 space-y-2 text-sm">
                        <div>
                            <dt class="font-medium text-gray-600">Ordner</dt>
                            <dd class="break-all font-mono text-xs text-gray-800">{{ $result['path'] ?: '-' }}</dd>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <dt class="font-medium text-gray-600">PDFs gefunden</dt>
                                <dd>{{ $result['pdf_count'] }}</dd>
                            </div>
                            <div>
                                <dt class="font-medium text-gray-600">Davon lesbar</dt>
                                <dd>{{ $result['readable_pdf_count'] }}</dd>
                            </div>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-600">Gepruefte Datei</dt>
                            <dd class="break-all">{{ $result['sample_file'] ?: '-' }}</dd>
                        </div>
                        @if ($result['sample_file'])
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <dt class="font-medium text-gray-600">Dateigroesse</dt>
                                    <dd>{{ number_format((int) $result['sample_size'], 0, ',', '.') }} Bytes</dd>
                                </div>
                                <div>
                                    <dt class="font-medium text-gray-600">PDF-Dateikopf</dt>
                                    <dd>{{ $result['pdf_header_valid'] ? 'Gueltig' : 'Ungueltig' }}</dd>
                                </div>
                            </div>
                        @endif
                    </dl>

                    <p class="mt-3 text-sm {{ $result['ok'] ? 'text-green-700' : 'text-red-700' }}">
                        {{ $result['message'] }}
                    </p>
                </div>
            @endforeach
        </div>
    @endif
</div>
</div>
