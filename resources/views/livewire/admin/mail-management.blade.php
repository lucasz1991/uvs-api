<div>
    <div class="mb-4 flex flex-wrap justify-between gap-4">
        <div class="mb-6 max-w-md">
            <h1 class="text-2xl font-bold text-gray-700">Mail's</h1>
            <p class="text-gray-500">Es gibt insgesamt {{ $mails->total() }} Mails.</p>
        </div>
    </div>

    <!-- Tabellenüberschrift -->
    <div class="grid grid-cols-12 bg-gray-100 p-2 font-semibold text-gray-700 border-b border-gray-300">
        <div class="col-span-1">
            <button wire:click="sortByField('id')" class="text-left flex items-center">
                ID
                @if ($sortBy === 'id')
                    <span class="ml-2 text-xl">
                        <svg class="w-4 h-4 ml-2 transition-transform transform" style="transform: rotate({{ $sortDirection === 'asc' ? '0deg' : '180deg' }});" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m19 9-7 7-7-7" />
                        </svg>
                    </span>
                @endif
            </button>
        </div>
        <div class="col-span-3">
            Datum
        </div>
        <div class="col-span-3">
            Anzahl Empfänger
        </div>
        <div class="col-span-3">
            Status
        </div>
        <div class="col-span-2">Aktionen</div>
    </div>

    <!-- Mails -->
    <div>
        @foreach ($mails as $mail)
            <div x-data="{ open: false }" class="border-b">
                <!-- Tabellenzeile -->
                <div @click="open = !open" class="cursor-pointer hover:bg-gray-100 grid grid-cols-12 items-center p-2 text-left"  @click.away="open = false">
                    <div class="col-span-1">{{ $mail->id }}</div>
                    <div class="col-span-3">{{ $mail->created_at->format('d.m.Y H:i') }}</div>
                    <div class="col-span-3">{{ count($mail->recipients) }} Empfänger</div>
                    <div class="col-span-3">
                        @if ($mail->status)
                            <span class="text-green-600 font-semibold">Gesendet</span>
                        @else
                            <span class="text-red-600 font-semibold">Nicht gesendet</span>
                        @endif
                    </div>
                    <div class="col-span-2 flex gap-2">
                        <button wire:click="resendMail({{ $mail->id }})" class="text-blue-500 fill-blue-500  hover:underline">
                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M105.1 202.6c7.7-21.8 20.2-42.3 37.8-59.8c62.5-62.5 163.8-62.5 226.3 0L386.3 160 352 160c-17.7 0-32 14.3-32 32s14.3 32 32 32l111.5 0c0 0 0 0 0 0l.4 0c17.7 0 32-14.3 32-32l0-112c0-17.7-14.3-32-32-32s-32 14.3-32 32l0 35.2L414.4 97.6c-87.5-87.5-229.3-87.5-316.8 0C73.2 122 55.6 150.7 44.8 181.4c-5.9 16.7 2.9 34.9 19.5 40.8s34.9-2.9 40.8-19.5zM39 289.3c-5 1.5-9.8 4.2-13.7 8.2c-4 4-6.7 8.8-8.1 14c-.3 1.2-.6 2.5-.8 3.8c-.3 1.7-.4 3.4-.4 5.1L16 432c0 17.7 14.3 32 32 32s32-14.3 32-32l0-35.1 17.6 17.5c0 0 0 0 0 0c87.5 87.4 229.3 87.4 316.7 0c24.4-24.4 42.1-53.1 52.9-83.8c5.9-16.7-2.9-34.9-19.5-40.8s-34.9 2.9-40.8 19.5c-7.7 21.8-20.2 42.3-37.8 59.8c-62.5 62.5-163.8 62.5-226.3 0l-.1-.1L125.6 352l34.4 0c17.7 0 32-14.3 32-32s-14.3-32-32-32L48.4 288c-1.6 0-3.2 .1-4.8 .3s-3.1 .5-4.6 1z"/></svg>
                        </button>
                    </div>
                </div>

                <!-- Collapse für Mail-Details -->
                <div x-show="open" x-collapse x-cloak class="bg-gray-50 p-4">
                    <h3 class="text-lg font-bold mb-2">Mail-Details</h3>
                    <p><strong>Betreff:</strong> {{ $mail->content['subject'] }}</p>
                    <p><strong>Nachricht:</strong> {{ $mail->content['body'] }}</p>
                    <p><strong>Link:</strong> <a href="{{ $mail->content['link'] }}" class="text-blue-500 underline">{{ $mail->content['link'] }}</a></p>
                    
                    <!-- Liste der Empfänger -->
                    <h4 class="text-lg font-bold mt-4">Empfänger</h4>
                    <ul class="list-disc pl-5 max-h-40 overflow-y-auto border-t border-gray-300 pt-2">
                        @foreach ($mail->recipients as $recipient)
                            <li class="py-1">
                                {{ $recipient['email'] }} 
                                @if ($recipient['status'])
                                    <span class="text-green-500">(Gesendet)</span>
                                @else
                                    <span class="text-red-500">(Nicht gesendet)</span>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $mails->links() }}
    </div>
</div>
