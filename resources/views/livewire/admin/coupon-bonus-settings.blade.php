<div >
    <h2 class="text-2xl font-semibold mb-10">Coupons & Boni</h2>

    <!-- Coupons Einstellungen -->
    <x-settings-collapse>
        <x-slot name="trigger">
            Coupons
        </x-slot>
        <x-slot name="content">
            <div  x-data="{ couponModalOpen: false, couponSelected: @entangle('couponSelected') }">

                <div class="bg-blue-100 text-blue-700 p-4 rounded-md border border-blue-200 mb-4">
                    <strong>Hinweis:</strong> In diesem Abschnitt kannst du alle Coupons verwalten. Du kannst neue Coupons hinzufügen, bestehende Coupons bearbeiten oder löschen. Gib bei der Erstellung eines neuen Coupons den Coupon-Code, eine Beschreibung, den Rabatttyp und den Wert an. Du kannst auch einen Mindestbestellwert und einen maximalen Rabattwert festlegen, sowie das Start- und Enddatum des Coupons bestimmen. Alle Änderungen werden gespeichert und können jederzeit angepasst werden.
                </div>
    
                <!-- Liste der Coupons -->
                <div class="space-y-4">
                    @foreach ($coupons as $coupon)
                        <div class="flex justify-between items-center">
                            <div class="flex items-center">
                                <label for="coupon_{{ $coupon->id }}" class="text-sm font-medium text-gray-700">{{ $coupon->code }}</label>
                            </div>
    
                            <!-- Dropdown für Bearbeiten und Löschen -->
                            <div x-data="{ isCouponDropdownOpen_{{ $coupon->id }}: false }" class="relative">
                                <button @click="isCouponDropdownOpen_{{ $coupon->id }} = !isCouponDropdownOpen_{{ $coupon->id }}" class="px-3 py-1 text-white bg-blue-500 rounded-md">Aktionen</button>
                                <div x-show="isCouponDropdownOpen_{{ $coupon->id }}" x-transition class="absolute right-0 mt-2 w-48 bg-white shadow-lg rounded-md">
                                    <button @click="couponSelected = {{ $coupon->id }}; couponModalOpen = true" class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">Bearbeiten</button>
                                    <button wire:click="deleteCoupon({{ $coupon->id }})" class="block w-full text-left px-4 py-2 text-red-600 hover:bg-red-100">Löschen</button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
    
                <!-- Button zum Hinzufügen eines neuen Coupons -->
                <button @click="couponSelected = false; couponModalOpen = true" class="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 mt-4">
                    Neuer Coupon
                </button>
                    <!-- Coupon Modal -->
                    <div x-show="couponModalOpen" x-transition class="fixed inset-0 flex items-center justify-center bg-gray-500 bg-opacity-50 z-50" @click.away="couponModalOpen = false">
                        <div class="bg-white p-6 rounded-lg w-full max-w-4xl">
                            <h3 class="text-xl font-semibold mb-4" x-text="couponSelected.id ? 'Coupon Bearbeiten' : 'Neuen Coupon Hinzufügen'"></h3>

                            <form wire:submit.prevent="saveCoupon">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- Coupon Code -->
                                    <div class="mb-4">
                                        <label for="coupon_code" class="block text-sm font-medium text-gray-700">Coupon Code</label>
                                        <input 
                                            type="text" 
                                            id="coupon_code" 
                                            wire:model="couponSelected.code"
                                            class="mt-1 p-2 block w-full border border-gray-300 rounded-md"
                                            required
                                        >
                                    </div>

                                    <!-- Description -->
                                    <div class="mb-4">
                                        <label for="description" class="block text-sm font-medium text-gray-700">Beschreibung</label>
                                        <textarea 
                                            id="description" 
                                            wire:model="couponSelected.description"
                                            class="mt-1 p-2 block w-full border border-gray-300 rounded-md"
                                            required
                                        ></textarea>
                                    </div>

                                    <!-- Discount Type -->
                                    <div class="mb-4">
                                        <label for="discount_type" class="block text-sm font-medium text-gray-700">Rabatt Typ</label>
                                        <select 
                                            id="discount_type" 
                                            wire:model="couponSelected.discount_type"
                                            class="mt-1 p-2 block w-full border border-gray-300 rounded-md"
                                            required
                                        >
                                            <option value="0">Prozent</option>
                                            <option value="1">Betrag</option>
                                        </select>
                                    </div>

                                    <!-- Discount Value -->
                                    <div class="mb-4">
                                        <label for="discount_value" class="block text-sm font-medium text-gray-700">Rabatt Wert</label>
                                        <input 
                                            type="number" 
                                            id="discount_value" 
                                            wire:model="couponSelected.discount_value"
                                            class="mt-1 p-2 block w-full border border-gray-300 rounded-md"
                                            step="0.01"
                                            required
                                        >
                                    </div>

                                    <!-- Min Order Value -->
                                    <div class="mb-4">
                                        <label for="min_order_value" class="block text-sm font-medium text-gray-700">Mindestbestellwert</label>
                                        <input 
                                            type="number" 
                                            id="min_order_value" 
                                            wire:model="couponSelected.min_order_value"
                                            class="mt-1 p-2 block w-full border border-gray-300 rounded-md"
                                            step="0.01"
                                        >
                                    </div>

                                    <!-- Max Discount Value -->
                                    <div class="mb-4">
                                        <label for="max_discount_value" class="block text-sm font-medium text-gray-700">Maximaler Rabattwert</label>
                                        <input 
                                            type="number" 
                                            id="max_discount_value" 
                                            wire:model="couponSelected.max_discount_value"
                                            class="mt-1 p-2 block w-full border border-gray-300 rounded-md"
                                            step="0.01"
                                        >
                                    </div>

                                    <!-- Start Date -->
                                    <div class="mb-4">
                                        <label for="start_date" class="block text-sm font-medium text-gray-700">Startdatum</label>
                                        <input 
                                            type="datetime-local" 
                                            id="start_date" 
                                            wire:model="couponSelected.start_date"
                                            class="mt-1 p-2 block w-full border border-gray-300 rounded-md"
                                            required
                                        >
                                    </div>

                                    <!-- End Date -->
                                    <div class="mb-4">
                                        <label for="end_date" class="block text-sm font-medium text-gray-700">Enddatum</label>
                                        <input 
                                            type="datetime-local" 
                                            id="end_date" 
                                            wire:model="couponSelected.end_date"
                                            class="mt-1 p-2 block w-full border border-gray-300 rounded-md"
                                            required
                                        >
                                    </div>

                                    <!-- Usage Limit -->
                                    <div class="mb-4">
                                        <label for="usage_limit" class="block text-sm font-medium text-gray-700">Nutzungsbegrenzung</label>
                                        <input 
                                            type="number" 
                                            id="usage_limit" 
                                            wire:model="couponSelected.usage_limit"
                                            class="mt-1 p-2 block w-full border border-gray-300 rounded-md"
                                        >
                                    </div>

                                    <!-- User Specific -->
                                    <div class="mb-4">
                                        <label for="user_specific" class="block text-sm font-medium text-gray-700">Benutzerspezifisch</label>
                                        <input 
                                            type="checkbox" 
                                            id="user_specific" 
                                            wire:model="couponSelected.user_specific"
                                            class="mt-1"
                                        >
                                    </div>

                                    <!-- Apply To (Optional) -->
                                    <div class="mb-4">
                                        <label for="applies_to" class="block text-sm font-medium text-gray-700">Gilt für</label>
                                        <input 
                                            type="text" 
                                            id="applies_to" 
                                            wire:model="couponSelected.applies_to"
                                            class="mt-1 p-2 block w-full border border-gray-300 rounded-md"
                                        >
                                    </div>

                                    <!-- Status -->
                                    <div class="mb-4">
                                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                        <select 
                                            id="status" 
                                            wire:model="couponSelected.status"
                                            class="mt-1 p-2 block w-full border border-gray-300 rounded-md"
                                            required
                                        >
                                            <option value="1">Aktiv</option>
                                            <option value="0">Inaktiv</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Inline Buttons (Save and Cancel) -->
                                <div class="flex justify-between items-center mt-6">
                                    <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600">
                                        <span x-text="couponSelected.id ? 'Änderungen Speichern' : 'Speichern'"></span>
                                    </button>
                                    <button @click="couponModalOpen = false" class="text-red-600 hover:text-red-800">
                                        Abbrechen
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>


            </div>
         </x-slot>
    </x-settings-collapse>

    <!-- Bonus Einstellungen -->
    <x-settings-collapse>
        <x-slot name="trigger">
            Boni
        </x-slot>
        <x-slot name="content">
            <div x-data="{ bonusModalOpen: @entangle('bonusModalOpen'), bonusSelected: @entangle('bonusSelected') }">

                <div class="bg-blue-100 text-blue-700 p-4 rounded-md border border-blue-200 mb-4">
                    <strong>Hinweis:</strong> In diesem Abschnitt kannst du alle Boni verwalten. Du kannst neue Boni hinzufügen, bestehende Boni bearbeiten oder löschen. Gib bei der Erstellung eines neuen Bonus die erforderlichen Details wie den Bonusnamen, die Beschreibung, den Wert und die Kriterien an. Darüber hinaus kannst du für jeden Bonus die Gültigkeitsdauer und den Status festlegen. Alle Änderungen werden in der Datenbank gespeichert und können jederzeit bearbeitet werden.
                </div>

<!-- Liste der Boni -->
<div class="space-y-4">
    @foreach ($bonuses as $bonus)
        <div x-data="{ open: false }" class="border border-gray-300 rounded-md bg-white shadow-md overflow-hidden">
            
            <!-- Bonus Name (Header) -->
            <button @click="open = !open" class="w-full text-left p-4 bg-gray-100 hover:bg-gray-200 flex justify-between items-center">
                <span class="font-medium text-gray-900">{{ $bonus->name }}</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600 transition-transform" :class="{'rotate-180': open}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 9l6 6 6-6"></path>
                </svg>
            </button>

            <!-- Bonus Details (Versteckt, bis geöffnet) -->
            <div x-show="open" x-collapse class="p-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    
                    <!-- Linke Spalte -->
                    <div>
                        <label class="text-sm font-medium text-gray-700">Beschreibung</label>
                        <span class="text-sm text-gray-900 block">{{ $bonus->description }}</span>

                        <label class="text-sm font-medium text-gray-700 mt-2 block">Typ</label>
                        <span class="text-sm text-gray-900">{{ ucfirst($bonus->type) }}</span>

                        <label class="text-sm font-medium text-gray-700 mt-2 block">Wert</label>
                        <span class="text-sm text-gray-900">
                            {{ $bonus->type == 'amount' ? number_format($bonus->value, 2, ',', '.') . ' €' : number_format($bonus->value, 2, ',', '.') . ' %' }}
                        </span>

                        <label class="text-sm font-medium text-gray-700 mt-2 block">Gültigkeitsdauer</label>
                        <span class="text-sm text-gray-900">{{ $bonus->validity_period ? $bonus->validity_period . ' Tage' : 'Unbegrenzt' }}</span>

                        <label class="text-sm font-medium text-gray-700 mt-2 block">Buchungsperioden</label>
                        <span class="text-sm text-gray-900">
                            @if ($bonus->periods)
                                {{ implode(', ', json_decode($bonus->periods)) }} Tage
                            @else
                                Keine Perioden definiert
                            @endif
                        </span>
                    </div>

                    <!-- Rechte Spalte -->
                    <div>
                        <label class="text-sm font-medium text-gray-700">Gültigkeitszeitraum</label>
                        <span class="text-sm text-gray-900 block">
                            {{ $bonus->valid_from ? $bonus->valid_from->format('d.m.Y') : 'Kein Startdatum' }} - 
                            {{ $bonus->valid_until ? $bonus->valid_until->format('d.m.Y') : 'Kein Enddatum' }}
                        </span>

                        <label class="text-sm font-medium text-gray-700 mt-2 block">Buchungsstartzeitraum</label>
                        <span class="text-sm text-gray-900 block">
                            {{ $bonus->booking_start_from ? $bonus->booking_start_from->format('d.m.Y') : 'Kein Startdatum' }} - 
                            {{ $bonus->booking_start_until ? $bonus->booking_start_until->format('d.m.Y') : 'Kein Enddatum' }}
                        </span>

                        <label class="text-sm font-medium text-gray-700 mt-2 block">Kundenanforderung</label>
                        <span class="text-sm text-gray-900">
                            @if ($bonus->customer_requirement === 'new') Nur neue Verkäufer
                            @elseif ($bonus->customer_requirement === 'existing') Nur bestehende Verkäufer
                            @else Alle Verkäufer
                            @endif
                        </span>

                        <label class="text-sm font-medium text-gray-700 mt-2 block">Einlösbar?</label>
                        <span class="text-sm text-gray-900">{{ $bonus->is_redeemable ? 'Ja' : 'Nein' }}</span>

                        <label class="text-sm font-medium text-gray-700 mt-2 block">Status</label>
                        <span class="text-sm text-gray-900">{{ $bonus->status == 1 ? 'Aktiv' : 'Inaktiv' }}</span>
                    </div>
                </div>

                <!-- Bearbeiten & Löschen -->
                <div class="mt-4 flex justify-end space-x-2">
                    <button @click="bonusSelected = {{ $bonus->id }}; bonusModalOpen = true"
                        class="px-3 py-2 bg-blue-500 text-white text-sm font-medium rounded-md hover:bg-blue-600">
                        Bearbeiten
                    </button>
                    <button wire:click="deleteBonus({{ $bonus->id }})"
                        class="px-3 py-2 bg-red-500 text-white text-sm font-medium rounded-md hover:bg-red-600">
                        Löschen
                    </button>
                </div>
            </div>
        </div>
    @endforeach
</div>



                <!-- Button zum Hinzufügen eines neuen Bonus -->
                <button @click="bonusSelected = false; bonusModalOpen = true" class="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 mt-4">
                    Neuer Bonus
                </button>

                <!-- Bonus Modal -->
                <x-dialog-modal wire:model="bonusModalOpen">
                    <x-slot name="title">
                        <h3 class="text-xl font-semibold mb-4" x-text="bonusSelected.id ? 'Bonus Bearbeiten' : 'Neuen Bonus Hinzufügen'"></h3>
                    </x-slot>
                    <x-slot name="content">
                        <div x-data="{ open: false }" class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded-md mb-6">
                            <div @click="open = !open" class="flex justify-between items-center">
                                <div class="font-semibold">ℹ️ Informationen</div>
                                <button class="text-blue-600 hover:text-blue-800 focus:outline-none">
                                    <span x-show="!open">Mehr anzeigen ▼</span>
                                    <span x-show="open">Weniger anzeigen ▲</span>
                                </button>
                            </div>
                            
                            <div x-show="open" class="mt-2">
                                <ul class="list-disc pl-5 text-sm">
                                    <li><strong>Gültigkeitszeitraum:</strong> Zeitraum, in dem der Bonus genutzt werden kann.</li>
                                    <li><strong>Buchungsstart von/bis:</strong> Der Bonus gilt nur, wenn die Regalmiete innerhalb dieses Zeitraums startet.</li>
                                    <li><strong>Buchungsperioden:</strong> Der Bonus gilt für:
                                        <ul class="list-none pl-3">
                                            <li>7 Tage (1 Woche)</li>
                                            <li>14 Tage (2 Wochen)</li>
                                            <li>21 Tage (3 Wochen)</li>
                                        </ul>
                                    </li>
                                    <li><strong>Kundenanforderung:</strong> Bestimme, wer den Bonus nutzen kann:
                                        <ul class="list-none pl-3">
                                            <li>Nur neue Verkäufer</li>
                                            <li>Nur bestehende Verkäufer</li>
                                        </ul>
                                    </li>
                                    <li><strong>Bonus-Typ & Wert:</strong> Prozentsatz oder fixer Rabattbetrag auf die Regalmiete.</li>
                                    <li><strong>Status:</strong> Der Bonus kann aktiv oder inaktiv sein.</li>
                                </ul>

                                <p class="mt-3 text-xs text-gray-600">
                                    💡 <strong>Hinweis:</strong> Der Bonus wird nur angewendet, wenn die Regalmiete für 7, 14 oder 21 Tage gebucht wird und innerhalb des gültigen Buchungszeitraums beginnt.
                                </p>
                            </div>
                        </div>

                        <form wire:submit.prevent="saveBonus">
                            <!-- Bonus Name -->
                            <div class="mb-4">
                                <label for="bonusName" class="block text-sm font-medium text-gray-700">Bonus Name</label>
                                <input type="text" id="bonusName" wire:model="bonusName"
                                    class="mt-1 p-2 block w-full border border-gray-300 rounded-md" required>
                                    <x-input-error for="bonusName" class="mt-2" />
                            </div>

                            <!-- Beschreibung -->
                            <div class="mb-4">
                                <label for="bonusDescription" class="block text-sm font-medium text-gray-700">Beschreibung</label>
                                <textarea id="bonusDescription" wire:model="bonusDescription"
                                    class="mt-1 p-2 block w-full border border-gray-300 rounded-md" required></textarea>
                                    <x-input-error for="bonusDescription" class="mt-2" />
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                                <!-- Bonus Typ -->
                                <div class="mb-4">
                                    <label for="type" class="block text-sm font-medium text-gray-700">Bonus Typ</label>
                                    <select id="type" wire:model="bonusType"
                                        class="mt-1 p-2 block w-full border border-gray-300 rounded-md" required>
                                        <option value="">bitte auswählen...</option>
                                        <option value="percentage">Prozent</option>
                                        <option value="amount">Betrag</option>
                                    </select>
                                    <x-input-error for="bonusType" class="mt-2" />
                                </div>

                                <!-- Bonus Wert -->
                                <div class="mb-4">
                                    <label for="value" class="block text-sm font-medium text-gray-700">Bonus Wert</label>
                                    <input type="number" id="value" wire:model="bonusValue"
                                        class="mt-1 p-2 block w-full border border-gray-300 rounded-md" step="0.01" required>
                                    <x-input-error for="bonusValue" class="mt-2" />
                                </div>

                                <!-- Gültig von / Gültig bis -->
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700">Gültig von - Gültig bis</label>
                                    <div class="flex gap-2">
                                        <input type="date" wire:model="bonusValidFrom"
                                            class="mt-1 p-2 block w-full border border-gray-300 rounded-md">
                                        <input type="date" wire:model="bonusValidUntil"
                                            class="mt-1 p-2 block w-full border border-gray-300 rounded-md">
                                    </div>
                                    <x-input-error for="bonusValidFrom" class="mt-2" />
                                    <x-input-error for="bonusValidUntil" class="mt-2" />
                                </div>

                                <!-- Buchungsstart von / bis -->
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700">Buchungsstart von - Buchungsstart bis</label>
                                    <div class="flex gap-2">
                                        <input type="date" wire:model="bonusBookingStartFrom"
                                            class="mt-1 p-2 block w-full border border-gray-300 rounded-md">
                                        <input type="date" wire:model="bonusBookingStartUntil"
                                            class="mt-1 p-2 block w-full border border-gray-300 rounded-md">
                                    </div>
                                    <x-input-error for="bonusBookingStartFrom" class="mt-2" />
                                    <x-input-error for="bonusBookingStartUntil" class="mt-2" />
                                </div>

                                <!-- Buchungsperioden (Checkboxen) -->
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700">Gilt für folgende Zeiträume:</label>
                                    <div class="flex flex-col space-y-1">
                                        <label class="flex items-center">
                                            <input type="checkbox" wire:model="bonusPeriodWeekly" class="mr-2"> 7 Tage
                                        </label>
                                        <label class="flex items-center">
                                            <input type="checkbox" wire:model="bonusPeriodBiweekly" class="mr-2"> 14 Tage
                                        </label>
                                        <label class="flex items-center">
                                            <input type="checkbox" wire:model="bonusPeriodThreeWeeks" class="mr-2"> 21 Tage
                                        </label>
                                    </div>
                                    <x-input-error for="bonusPeriodWeekly" class="mt-2" />
                                    <x-input-error for="bonusPeriodBiweekly" class="mt-2" />
                                    <x-input-error for="bonusPeriodThreeWeeks" class="mt-2" />
                                </div>

                                <!-- Kundenanforderung -->
                                <div class="mb-4">
                                    <label for="bonusCustomerRequirement" class="block text-sm font-medium text-gray-700">Kundenanforderung</label>
                                    <select id="bonusCustomerRequirement" wire:model="bonusCustomerRequirement"
                                        class="mt-1 p-2 block w-full border border-gray-300 rounded-md" required>
                                        <option value="">bitte auswählen...</option>
                                        <option value="new">Nur neue Kunden</option>
                                        <option value="existing">Nur bestehende Verkäufer</option>
                                        <option value="all">Für alle Kunden</option>
                                    </select>
                                    <x-input-error for="bonusCustomerRequirement" class="mt-2" />
                                </div>

                                <!-- Benutzer ID -->
                                <div class="mb-4">
                                    <label for="bonusUserId" class="block text-sm font-medium text-gray-700">Benutzer ID</label>
                                    <input type="number" id="bonusUserId" wire:model="bonusUserId"
                                        class="mt-1 p-2 block w-full border border-gray-300 rounded-md">
                                        <x-input-error for="bonusUserId" class="mt-2" />
                                </div>

                                <!-- Status -->
                                <div class="mb-4">
                                    <label for="bonusStatus" class="block text-sm font-medium text-gray-700">Status</label>
                                    <select id="bonusStatus" wire:model="bonusStatus"
                                        class="mt-1 p-2 block w-full border border-gray-300 rounded-md" required>
                                        <option value="">bitte auswählen...</option>
                                        <option value="1">Aktiv</option>
                                        <option value="0">Inaktiv</option>
                                    </select>
                                    <x-input-error for="bonusStatus" class="mt-2" />
                                </div>
                            </div>
                        </form>
                    </x-slot>
                    <x-slot name="footer">
                        <!-- Inline Buttons (Speichern und Abbrechen) -->
                        <div class="flex justify-between items-center space-x-3">
                            <x-button wire:click="saveBonus()" class="">
                                <span x-text="bonusSelected.id ? 'Änderungen Speichern' : 'Speichern'"></span>
                            </x-button>
                            <x-button @click="bonusModalOpen = false" class="">
                                Abbrechen
                            </x-button>
                        </div>
                    </x-slot>
                </x-dialog-modal>
            </div>
        </x-slot>
    </x-settings-collapse>
</div>
