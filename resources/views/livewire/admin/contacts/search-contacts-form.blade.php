<div>
    <!-- Button zum Öffnen des Modals -->
    <x-button wire:click="openModal" class="btn-xs">
        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M234.7 42.7L197 56.8c-3 1.1-5 4-5 7.2s2 6.1 5 7.2l37.7 14.1L248.8 123c1.1 3 4 5 7.2 5s6.1-2 7.2-5l14.1-37.7L315 71.2c3-1.1 5-4 5-7.2s-2-6.1-5-7.2L277.3 42.7 263.2 5c-1.1-3-4-5-7.2-5s-6.1 2-7.2 5L234.7 42.7zM46.1 395.4c-18.7 18.7-18.7 49.1 0 67.9l34.6 34.6c18.7 18.7 49.1 18.7 67.9 0L529.9 116.5c18.7-18.7 18.7-49.1 0-67.9L495.3 14.1c-18.7-18.7-49.1-18.7-67.9 0L46.1 395.4zM484.6 82.6l-105 105-23.3-23.3 105-105 23.3 23.3zM7.5 117.2C3 118.9 0 123.2 0 128s3 9.1 7.5 10.8L64 160l21.2 56.5c1.7 4.5 6 7.5 10.8 7.5s9.1-3 10.8-7.5L128 160l56.5-21.2c4.5-1.7 7.5-6 7.5-10.8s-3-9.1-7.5-10.8L128 96 106.8 39.5C105.1 35 100.8 32 96 32s-9.1 3-10.8 7.5L64 96 7.5 117.2zm352 256c-4.5 1.7-7.5 6-7.5 10.8s3 9.1 7.5 10.8L416 416l21.2 56.5c1.7 4.5 6 7.5 10.8 7.5s9.1-3 10.8-7.5L480 416l56.5-21.2c4.5-1.7 7.5-6 7.5-10.8s-3-9.1-7.5-10.8L480 352l-21.2-56.5c-1.7-4.5-6-7.5-10.8-7.5s-9.1 3-10.8 7.5L416 352l-56.5 21.2z"/></svg>    
    </x-button>

    <x-dialog-modal wire:model="showModal">
        <x-slot name="title">
            Datei hochladen & analysieren
        </x-slot>

        <x-slot name="content">
        <details class="mt-2 bg-sky-50 border-l-2 border-sky-500 rounded p-4 mb-8">
            <summary class="cursor-pointer text-sky-700 font-semibold">
                Hinweis zur Datei-Analyse
            </summary>
            <div class="mt-2 text-sky-700">
                <p>So funktioniert die Analyse von Kontakten über <b>GelbeSeiten.de</b>:</p>
                <ol class="list-decimal pl-6">
                    <li>Öffne die Webseite <a href="https://www.gelbeseiten.de" target="_blank" class="text-blue-600 underline">GelbeSeiten.de</a> und starte eine Suche (z. B. „Bestatter in Hamburg“).</li>
                    <li>Lasse dir die vollständige Ergebnisliste anzeigen („Mehr Ergebnisse laden“).</li>
                    <li>Speichere den HTML-Code der Seite:
                        <ul class="list-disc pl-6">
                            <li><b>Chrome/Edge:</b> F12 → „Elements“ → Rechtsklick auf `<html>` → „Speichern unter...“</li>
                            <li><b>Firefox:</b> F12 → „Inspector“ → Rechtsklick auf `<html>` → „HTML speichern“</li>
                        </ul>
                    </li>
                    <li>Lade die gespeicherte <b>.txt</b>-Datei hier hoch.</li>
                    <li>Das System analysiert die Datei und extrahiert die Kontaktdaten.</li>
                </ol>
                <p class="mt-2 font-semibold">Wichtige Hinweise:</p>
                <ul class="list-disc pl-6">
                    <li>Die Datei muss den vollständigen HTML-Code enthalten.</li>
                    <li>Maximale Dateigröße: <b>10 MB</b>.</li>
                    <li>Falls keine Kontakte gefunden werden, überprüfe die gespeicherte Datei.</li>
                </ul>
            </div>
        </details>

            <div class="flex flex-col space-y-3 items-center justify-center">
                @if(!count($articles))
                    <!-- Datei-Upload -->
                    <input type="file" wire:model="file" accept=".txt"
                        class="w-full h-10 border rounded p-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                @endif
                <div wire:loading class="text-blue-300">
                    <div class="lds-roller"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
                </div>
                <!-- Ladeanzeige für Datei-Upload -->
                <div wire:loading wire:target="file" class="text-blue-600">
                    Datei wird hochgeladen...
                </div>

                <!-- Ladeanzeige für Datei-Analyse -->
                <div wire:loading wire:target="analyzeFile" class="text-yellow-600">
                    Datei wird analysiert...
                </div>

                <!-- Ladeanzeige für Umwandlung in Kontakte -->
                <div wire:loading wire:target="convertToContacts" class="text-green-600">
                    Artikel werden in Kontakte umgewandelt...
                </div>


                <!-- Fehleranzeige -->
                @error('file') 
                    <p class="text-red-600 font-semibold">{{ $message }}</p> 
                @enderror
                @section('scripts')
                    <script src="{{ URL::asset('build/libs/dropzone/min/dropzone.min.js') }}"></script>
                @endsection
            </div>
            @if($articles && count($tempContacts) < 1)
                <div class="mt-4 p-2 border rounded bg-gray-100"  wire:loading.class="hidden">
                    <h3 class="font-semibold">Extrahierte Artikel:</h3>
                    <h3 class="font-semibold">
                        Gefundene Artikel: <span class="text-blue-600">{{ count($articles) }}</span>
                    </h3>
                    <div class="overflow-y-auto max-h-96">
                        @foreach($articles as $article)
                            <div class="p-2 my-2 border-b">
                                <p><strong>Text:</strong> {{ Str::limit($article['text'], 150) }}</p>
                                @if($article['link'])
                                    <p><strong>Link:</strong> <a href="{{ $article['link'] }}" class="text-blue-500 underline" target="_blank">{{ $article['link'] }}</a></p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
            <!-- Extrahierte Kontakte anzeigen -->
            @if($tempContacts && count($tempContacts) > 0)
                <div class="mt-4 p-2 border rounded bg-green-100" wire:loading.class="hidden">
                    <h3 class="font-semibold">Extrahierte Kontakte:</h3>
                    <h3 class="font-semibold">
                        Gefundene Kontakte: <span class="text-green-600">{{ count($tempContacts) }}</span>
                    </h3>
                    <div class="overflow-y-auto max-h-96">
                        @foreach($tempContacts as $contact)
                            <div class="p-2 my-2 border-b">
                                <p><strong>Branche:</strong> {{ $contact['Branche'] }}</p>
                                <p><strong>Name:</strong> {{ $contact['Name'] }}</p>
                                <p><strong>Anschrift:</strong> {{ $contact['Anschrift'] }}</p>
                                <p><strong>Telefonnummer:</strong> {{ $contact['Tel_Nummer'] }}</p>
                                <p><strong>E-Mail:</strong> {{ $contact['mail'] }}</p>
                                <p><strong>Website:</strong> 
                                    @if($contact['website'] !== 'Keine Website')
                                        <a href="{{ $contact['website'] }}" class="text-blue-500 underline" target="_blank">{{ $contact['website'] }}</a>
                                    @else
                                        Keine Website
                                    @endif
                                </p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </x-slot>

        <x-slot name="footer">
            <div class="flex space-x-3">
                @if($file && count($articles) === 0)
                    <!-- Datei analysieren Button (Nur sichtbar, wenn eine Datei hochgeladen wurde, aber noch keine Artikel gefunden wurden) -->
                    <x-button wire:click="analyzeFile" class="btn-sm">
                        Datei analysieren
                    </x-button>
                @elseif(count($articles) > 0 && count($tempContacts) < 1)
                    <!-- In Kontakte umwandeln Button (Nur sichtbar, wenn Artikel gefunden wurden) -->
                    <x-button wire:click="convertToContacts" class="btn-sm bg-green-600 hover:bg-green-700">
                        In Kontakte umwandeln
                    </x-button>
                @endif

                @if(count($tempContacts) > 0)
                    <!-- Speichern & Schließen Button (Nur sichtbar, wenn Kontakte extrahiert wurden) -->
                    <x-button wire:click="saveContacts" class="btn-sm">
                        Speichern & Schließen
                    </x-button>
                @endif

                <!-- Abbrechen Button (Immer sichtbar) -->
                <x-button wire:click="closeModal" class="btn-sm">
                    Abbrechen
                </x-button>
            </div>
        </x-slot>


    </x-dialog-modal>
</div>
