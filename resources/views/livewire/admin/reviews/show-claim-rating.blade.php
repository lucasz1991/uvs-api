<div class="p-6 space-y-6 " @if($rating->status == 'rating') wire:poll.5s @endif x-data="{ rating: @entangle('rating').live }">
    <div class="flex items-center justify-between">
        <div>
            {{-- Kundeninformation --}}
            <h1 class="text-2xl font-bold text-gray-800">Bewertung im Detail</h1>
        </div>
        {{-- Drei-Punkte-Menü --}}
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" class="text-gray-500 bg-gray-100 hover:text-gray-800 transition duration-200 p-2 rounded-full hover:bg-gray-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75a.75.75 0 11-1.5 0 .75.75 0 011.5 0zM12 12a.75.75 0 11-1.5 0 .75.75 0 011.5 0zM12 17.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                </svg>
            </button>
            {{-- Dropdown --}}
            <div x-show="open" @click.away="open = false" x-cloak
                class="absolute right-0 mt-2 w-40 bg-white border border-gray-200 rounded shadow-lg z-50">
                <ul class="text-sm text-gray-700">
                    <li>
                        <button wire:click="reanalyse({{ $rating->id }})"
                                class="block w-full px-4 py-2 text-left hover:bg-blue-100">
                            Neu analysieren
                        </button>
                    </li>
                    <li>
                        <button wire:click="$rating->reanalyse()"
                                class="block w-full px-4 py-2 text-left hover:bg-yellow-100">
                            Deaktivieren
                        </button>
                    </li>
                    <li>
                        <button wire:click="editRating({{ $rating->id }})"
                                class="block w-full px-4 py-2 text-left hover:bg-gray-100">
                            Bearbeiten
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    {{-- Kundeninformation --}}
    <x-ratings.section title="Kundendaten">
        <x-ratings.row label="Kunde">{{ $rating->user->name ?? '–' }}</x-ratings.row>
        <x-ratings.row label="Bewertung erstellt am">{{ $rating->created_at->format('d.m.Y H:i') }}</x-ratings.row>
        <x-ratings.row label="Status">
            <x-ratings.status-badge :status="$rating->status" />
        </x-ratings.row>
    </x-ratings.section>
    {{-- Versicherungsinformationen --}}
    <x-ratings.section title="Versicherungsdaten">
        <x-ratings.row label="Versicherung">{{ $rating->insurance->name ?? '–' }}</x-ratings.row>
        <x-ratings.row label="Versicherungsart">{{ $rating->insuranceType->name ?? '–' }}</x-ratings.row>
        <x-ratings.row label="Untertyp">{{ $rating->insuranceSubtype->name ?? '–' }}</x-ratings.row>
    </x-ratings.section>
    {{-- Regulierungsstatus --}}
    <x-ratings.section title="Regulierungsstatus">
        <x-ratings.row label="Regulierungsart">{{ $rating->answers['regulationType'] ?? '–' }}</x-ratings.row>
        <x-ratings.row label="Abgeschlossen">{{ $rating->answers['is_closed'] ? 'Ja' : 'Nein' }}</x-ratings.row>
        <x-ratings.row label="Beginn">{{ $rating->answers['selectedDates']['started_at'] ?? '–' }}</x-ratings.row>
        @if($rating->answers['is_closed'])
            <x-ratings.row label="Beendet">{{ $rating->answers['selectedDates']['ended_at'] ?? '–' }}</x-ratings.row>
        @endif
        <x-ratings.row label="Details">{{ $rating->answers['regulationDetail']['selected_value'] ?? '–' }}</x-ratings.row>
        <x-ratings.row label="Detail-Kommentar">{{ $rating->answers['regulationDetail']['textarea_value'] ?? '–' }}</x-ratings.row>
    </x-ratings.section>
    {{-- Variable Fragen --}}
    <x-settings-collapse>
            <x-slot name="trigger">
                <h1 class="text-xl font-bold text-gray-800 mb-1">Antworten auf variable Fragen</h1>
                <x-ratings.rating-stars :score="$rating->attachments['scorings']['variable_questions']" />
            </x-slot>
            <x-slot name="content">
                <x-ratings.section title="">
                    @forelse($rating->attachments['scorings']['questions'] as $question)
                        <div class="bg-gray-50 border border-gray-200 rounded-md p-4 mb-4">
                            <p class="text-sm text-gray-600"><strong>Frage:</strong> {{ $question['question_title'] }}</p>
                            <p class="text-sm text-gray-600"><strong>Beschreibung:</strong> {{ $question['question_text'] }}</p>
                            <p class="text-sm text-gray-700 mt-1"><strong>Antwort:</strong> {{ $question['answer'] }}</p>
                            @if($question['type'] == 'calc')
                                <p class="text-sm text-gray-700 mt-1"><x-ratings.rating-stars :score="$question['score']" /></p>
                            @endif
                            @if($question['type'] == 'ai')
                                <p class="text-sm text-gray-700 mt-1"><x-ratings.rating-stars :score="$question['ai_score']" /></p>
                                <p class="text-sm text-gray-700 mt-1"><strong>Kommentar:</strong> {{ $question['ai_comment'] }}</p>
                            @endif
                        </div>
                    @empty
                        <div class="text-sm text-gray-500">Keine variablen Fragen gefunden.</div>
                    @endforelse
                    <div class=" mt-1 p-4">

                        <p class="text-sm text-gray-700"><strong>Gesammt Scoring variable Fragen:</strong> <x-ratings.rating-stars :score="$rating->attachments['scorings']['variable_questions']" /></p>
                    </div>
            </x-ratings.section>
        </x-slot>
    </x-settings-collapse>
    {{-- Bewertung --}}
    <x-settings-collapse>
            <x-slot name="trigger">
                <h1 class="text-xl font-bold text-gray-800 mb-1">Bewertung & AI-Auswertung</h1>
                <x-ratings.rating-stars :score="$rating->rating_score" />
            </x-slot>
            <x-slot name="content">
    <x-ratings.section  title="">
        <x-ratings.row label="Regulations Dauer">
            <div class="flex items-center space-x-2">
                <x-ratings.rating-stars :score="$rating->attachments['scorings']['regulation_speed']" />
            </div>
        </x-ratings.row>
        <x-ratings.row label="Kundenservice">
            <div class="flex items-center space-x-2">
                <x-ratings.rating-stars :score="$rating->attachments['scorings']['customer_service']" />
            </div>
        </x-ratings.row>
        <x-ratings.row label="Fairness">
            <div class="flex items-center space-x-2">
                <x-ratings.rating-stars :score="$rating->attachments['scorings']['fairness']" />
            </div>
        </x-ratings.row>
        <x-ratings.row label="Transparency">
            <div class="flex items-center space-x-2">
                <x-ratings.rating-stars :score="$rating->attachments['scorings']['transparency']" />
            </div>
        </x-ratings.row>
        <hr class="my-4"> 
        <x-ratings.row label="Gesammt Score">
            <div class="flex items-center space-x-2">
                <x-ratings.rating-stars :score="$rating->rating_score" />
            </div>
        </x-ratings.row>
        @if(isset($rating->attachments['scorings']['ai_overall_comment']))
            <x-ratings.row label="Kommentar">
                <p class="text-sm text-gray-700">{{ $rating->attachments['scorings']['ai_overall_comment'] }}</p>
            </x-ratings.row>
        @endif
    </x-ratings.section>
    </x-slot>
    </x-settings-collapse>
</div>
