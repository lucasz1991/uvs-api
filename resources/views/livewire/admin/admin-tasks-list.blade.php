<div class="">
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
                <div class="text-sm">
                    <h2 class="text-lg font-semibold mb-2">Hinweis zur Aufgabenverwaltung</h2>
                    <p class="text-sm">
                        Hier findest du alle offenen, in Bearbeitung befindlichen und abgeschlossenen Aufgaben.  
                        Je nach Status können Aufgaben unterschiedlich behandelt werden:
                    </p>
                    <ul class="mt-2 text-sm list-disc list-inside">
                        <li><strong class="text-red-400">Offene Aufgaben</strong>  – Können übernommen und bearbeitet werden.</li>
                        <li><strong class="text-yellow-400">In Bearbeitung</strong>  – Bereits einem Admin zugewiesen.</li>
                        <li><strong class="text-green-400">Abgeschlossene Aufgaben</strong>  – Erledigt, keine weiteren Aktionen nötig.</li>
                    </ul>
                    <p class="mt-2 text-sm">
                        Falls du eine Aufgabe übernehmen möchtest, klicke auf <strong class="text-blue-600">"Übernehmen"</strong>.  
                        Sobald du sie erledigt hast, kannst du sie mit <strong class="text-blue-600">"Abschließen"</strong> als erledigt markieren. ✅
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div class="mb-4 flex flex-wrap justify-between gap-4">
        <div class="mb-6 max-w-md">
            <h1 class="text-2xl font-bold text-gray-700">ToDo's</h1>
            <p class="text-gray-500">
                Es gibt insgesamt {{ $tasks->where('status', 0)->count() }} offene Aufgaben.
            </p>
        </div>
    </div>
    <!-- Tabellenüberschrift -->
    <div class="grid grid-cols-12 bg-gray-100 p-2 font-semibold text-gray-700 border-b border-gray-300">
        <div class="col-span-1">ID</div>
        <div class="col-span-3">Aufgabentyp</div>
        <div class="col-span-3">Regalmiete</div>
        <div class="col-span-3">Zugewiesen an</div>
        <div class="col-span-2 text-right">Status</div>
    </div>
    <!-- Aufgaben -->
    <div>
        @foreach ($tasks as $task)
            <div x-data="{ open: false }" class="border-b"  @click.away="open = false">
                <!-- Tabellenzeile -->
                <div @click="open = !open" class="cursor-pointer hover:bg-gray-50 grid grid-cols-12 items-center p-2 text-left" x-bind:class="{ 'bg-blue-50': open }">
                    <div class="col-span-1">{{ $task->id }}</div>
                    <div class="col-span-3">{{ $task->task_type }}</div>
                    <div class="col-span-3">
                        @if ($task->shelfRental)
                            <a href="{{ route('admin.shelf-rental', $task->shelf_rental_id) }}" class="text-blue-500 underline">#{{ $task->shelf_rental_id }}</a>
                        @endif
                    </div>
                    <div class="col-span-3">{{ $task->assignedAdmin ? $task->assignedAdmin->name : 'Nicht zugewiesen' }}</div>
                    <div class="col-span-2 text-right">
                        <span class="">
                            {{ $task->getStatusTextAttribute() }}
                        </span>
                    </div>
                </div>
                <!-- Karte mit Footer für Optionen -->
                <div x-show="open" x-collapse x-cloak class=" bg-blue-50 p-4 border-t">
                    <h3 class="text-lg font-bold mb-2">📝 Aufgaben-Details</h3>
                    <p><strong>Beschreibung:</strong> {{ $task->description }}</p>
                    <p><strong>Erstellt am:</strong> {{ $task->created_at->format('d.m.Y H:i') }}</p>
                    @if ($task->shelfRental)
                        <p><strong>Regalbuchung:</strong> <a href="{{ route('admin.shelf-rental', $task->shelf_rental_id) }}" class="text-blue-500">#{{ $task->shelf_rental_id }}</a></p>
                    @endif
                    @if ($task->task_type === 'Auszahlung' && $task->shelfRental && $task->shelfRental->payouts->isNotEmpty())
                        @php
                            $latestPayout = $task->shelfRental->payouts->sortByDesc('created_at')->first();
                        @endphp
                        <div class="mt-4 p-4 bg-white rounded-lg border">
                            <h4 class="font-semibold">💰 Payout-Details</h4>
                            
                            <p class="group/payoutdetail"><strong>Betrag:</strong> 
                                <span x-data="{ text: '{{ number_format($latestPayout->amount, 2, ',', '.') }}' }" class="relative group">
                                    {{ number_format($latestPayout->amount, 2, ',', '.') }} €
                                    <button @click="navigator.clipboard.writeText(text)" 
                                            class="ml-2 hidden group-hover/payoutdetail:inline-block text-gray-500 hover:text-gray-800"
                                            title="Kopieren">
                                            <svg class="h-4 w-4 text-blue-500" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M208 0L332.1 0c12.7 0 24.9 5.1 33.9 14.1l67.9 67.9c9 9 14.1 21.2 14.1 33.9L448 336c0 26.5-21.5 48-48 48l-192 0c-26.5 0-48-21.5-48-48l0-288c0-26.5 21.5-48 48-48zM48 128l80 0 0 64-64 0 0 256 192 0 0-32 64 0 0 48c0 26.5-21.5 48-48 48L48 512c-26.5 0-48-21.5-48-48L0 176c0-26.5 21.5-48 48-48z"/></svg>
                                    </button>
                                </span>
                            </p>

                            @if (!empty($latestPayout->payout_details['account_holder']))
                                <p class="group/payoutdetail"><strong>Kontoinhaber:</strong> 
                                    <span x-data="{ text: '{{ $latestPayout->payout_details['account_holder'] }}' }" class="relative group">
                                        {{ $latestPayout->payout_details['account_holder'] }}
                                        <button @click="navigator.clipboard.writeText(text)" 
                                                class="ml-2 hidden group-hover/payoutdetail:inline-block text-gray-500 hover:text-gray-800"
                                                title="Kopieren">
                                                <svg class="h-4 w-4 text-blue-500" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M208 0L332.1 0c12.7 0 24.9 5.1 33.9 14.1l67.9 67.9c9 9 14.1 21.2 14.1 33.9L448 336c0 26.5-21.5 48-48 48l-192 0c-26.5 0-48-21.5-48-48l0-288c0-26.5 21.5-48 48-48zM48 128l80 0 0 64-64 0 0 256 192 0 0-32 64 0 0 48c0 26.5-21.5 48-48 48L48 512c-26.5 0-48-21.5-48-48L0 176c0-26.5 21.5-48 48-48z"/></svg>
                                        </button>
                                    </span>
                                </p>
                            @endif

                            @if (!empty($latestPayout->payout_details['iban']) && !empty($latestPayout->payout_details['bic']))
                                <p class="group/payoutdetail"><strong>IBAN:</strong>  
                                    <span x-data="{ text: '{{ $latestPayout->payout_details['iban'] }}' }" class="relative group">
                                        {{ wordwrap($latestPayout->payout_details['iban'], 4, ' ', true) }}
                                        <button @click="navigator.clipboard.writeText(text)" 
                                                class="ml-2 hidden group-hover/payoutdetail:inline-block text-gray-500 hover:text-gray-800"
                                                title="Kopieren">
                                                <svg class="h-4 w-4 text-blue-500" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M208 0L332.1 0c12.7 0 24.9 5.1 33.9 14.1l67.9 67.9c9 9 14.1 21.2 14.1 33.9L448 336c0 26.5-21.5 48-48 48l-192 0c-26.5 0-48-21.5-48-48l0-288c0-26.5 21.5-48 48-48zM48 128l80 0 0 64-64 0 0 256 192 0 0-32 64 0 0 48c0 26.5-21.5 48-48 48L48 512c-26.5 0-48-21.5-48-48L0 176c0-26.5 21.5-48 48-48z"/></svg>
                                        </button>
                                    </span>
                                </p>
                                <p class="group/payoutdetail"><strong>BIC:</strong>  
                                    <span x-data="{ text: '{{ $latestPayout->payout_details['bic'] }}' }" class="relative group">
                                        {{ wordwrap($latestPayout->payout_details['bic'], 4, ' ', true) }}
                                        <button @click="navigator.clipboard.writeText(text)" 
                                                class="ml-2 hidden group-hover/payoutdetail:inline-block text-gray-500 hover:text-gray-800"
                                                title="Kopieren">
                                                <svg class="h-4 w-4 text-blue-500" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M208 0L332.1 0c12.7 0 24.9 5.1 33.9 14.1l67.9 67.9c9 9 14.1 21.2 14.1 33.9L448 336c0 26.5-21.5 48-48 48l-192 0c-26.5 0-48-21.5-48-48l0-288c0-26.5 21.5-48 48-48zM48 128l80 0 0 64-64 0 0 256 192 0 0-32 64 0 0 48c0 26.5-21.5 48-48 48L48 512c-26.5 0-48-21.5-48-48L0 176c0-26.5 21.5-48 48-48z"/></svg>
                                        </button>
                                    </span>
                                </p>
                            @elseif (!empty($latestPayout->payout_details['paypal_email']))
                                <p class="group/payoutdetail"><strong>PayPal:</strong> 
                                    <span x-data="{ text: '{{ $latestPayout->payout_details['paypal_email'] }}' }" class="relative group">
                                        {{ $latestPayout->payout_details['paypal_email'] }}
                                        <button @click="navigator.clipboard.writeText(text)" 
                                                class="ml-2 hidden group-hover/payoutdetail:inline-block text-gray-500 hover:text-gray-800"
                                                title="Kopieren">
                                                <svg class="h-4 w-4 text-blue-500" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M208 0L332.1 0c12.7 0 24.9 5.1 33.9 14.1l67.9 67.9c9 9 14.1 21.2 14.1 33.9L448 336c0 26.5-21.5 48-48 48l-192 0c-26.5 0-48-21.5-48-48l0-288c0-26.5 21.5-48 48-48zM48 128l80 0 0 64-64 0 0 256 192 0 0-32 64 0 0 48c0 26.5-21.5 48-48 48L48 512c-26.5 0-48-21.5-48-48L0 176c0-26.5 21.5-48 48-48z"/></svg>
                                        </button>
                                    </span>
                                </p>
                            @endif
                        </div>

                    @endif
                    <!-- Footer mit Buttons -->
                    <div class="mt-4 flex justify-end space-x-2 border-t pt-3">
                        @if(!$task->assigned_to)
                            <x-button wire:click="assignToMe({{ $task->id }})" class="btn-xs text-sm">
                                ➕ Übernehmen
                            </x-button>
                        @endif
                        @if($task->status == 1)
                            <x-button wire:click="markAsCompleted({{ $task->id }})" class="btn-xs text-sm text-green-500">
                                ✅ Abschließen
                            </x-button>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <!-- Pagination -->
    <div class="mt-4">
        {{ $tasks->links() }}
    </div>
</div>