<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Setting;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Location;
use App\Models\RetailSpace;
use App\Models\Shelve;
use App\Models\ShelfBlockedDate;
use App\Models\BlockedDate;
use App\Models\AdminShelfBlockedDate;
use App\Models\ShelfRental;
use Illuminate\Support\Str;



class AdminConfig extends Component
{
    public $locations = [];
    public $retailSpaces = [];
    public $shelves = [];
    public $AdminblockedShelves = [];
    public $newBlockedDayLocation = null;
    public $newBlockedDayRetailSpaceId; 
    public $newBlockedDayShelfId;
    public $newBlockedDayStartDate;
    public $newBlockedDayEndDate;
    public $holidays = [];
    public $blockedDays = [];
    public $blockedShelves = [];
    public $newHolidayDate;
    public $newHolidayName;
    public $newBlockedDate;
    public $selectedBlockedDays;
    public $optimizeCalendar = false;
    public $optimizeShelfSelection = false;
    public $periods= [];
    public $newPeriodSettingId;
    public $newPeriodPrice;
    public $newPeriodDuration;
    public $newPeriodDescription;
    public $periodisEditing = false;


    public $categories;
    public $categoriesData = [];
    public $newCategoryName;
    public $newCategorySlug;
    public $newCategoryParentId;
    public $tags;
    public $newTagName;

    // Neue E-Mail-Einstellungen für Admins
    public $adminEmail;
    public $adminEmailNotifications = [
        'new_booking' => false,
        'new_user' => false,
        'user_payout' => false,
        'sale_notification' => false,
    ];

    // Neue E-Mail-Einstellungen für Benutzer
    public $userEmailNotifications = [
        'booking_confirmation' => false,
        'sale_notification' => false,
        'reminder_start_3days' => false,
        'reminder_start_tomorrow' => false,
        'reminder_end_tomorrow' => false,
    ];

    public $apiSettings = [
        'base_api_url' => '',
        'base_api_key' => '',
    ];

    public $apiKeys = [];

    public $provision; 
    public $provisionSettingId;


    public function mount()
    {
        $this->loadSettings();
        $this->loadApiKeys();

    }

    public function loadSettings()
    {

        // E-Mail-Einstellungen für Admins
        $mailSettings = Setting::where('type', 'mails')->get();
        foreach ($mailSettings as $setting) {
            if ($setting->key === 'admin_email') {
                $this->adminEmail = $setting->value;
            } elseif (array_key_exists($setting->key, $this->adminEmailNotifications)) {
                $this->adminEmailNotifications[$setting->key] = json_decode($setting->value);
            } elseif (array_key_exists($setting->key, $this->userEmailNotifications)) {
                $this->userEmailNotifications[$setting->key] = json_decode($setting->value);
            }
        }
        $this->apiSettings['base_api_url'] = Setting::where('key', 'base_api_url')->value('value');
        $this->apiSettings['base_api_key'] = Setting::where('key', 'base_api_key')->value('value');

    }

    public function saveApiSettings()
    {


        // Speichern der Fluore-Kassen API-Einstellungen
        Setting::updateOrCreate(
            ['key' => 'base_api_url', 'type' => 'api'],
            ['value' => $this->apiSettings['base_api_url']]
        );
        
        Setting::updateOrCreate(
            ['key' => 'base_api_key', 'type' => 'api'],
            ['value' => $this->apiSettings['base_api_key']]
        );
    
        // Erfolgsmeldung
        $this->dispatch('showAlert', 'API-Einstellungen wurden erfolgreich gespeichert.', 'success');
    }

    public function loadApiKeys()
    {
        $this->apiKeys = Setting::where('type', 'api_keys')->pluck('value', 'key')->toArray();
    }

    public function generateApiKey()
    {
        $newKey = Str::random(40);
        Setting::create([
            'key' => 'api_key_' . now()->timestamp,
            'value' => $newKey,
            'type' => 'api_keys',
        ]);
        $this->loadApiKeys();
        $this->dispatch('showAlert', 'API-Schlüssel wurde erfolgreich erstellt.', 'success');
    }

    public function deleteApiKey($key)
    {
        Setting::where('key', $key)->delete();
        $this->loadApiKeys();
        $this->dispatch('showAlert', 'API-Schlüssel wurde erfolgreich gelöscht.', 'success');
    }


    public function saveAdminMailSettings()
    {
        foreach ($this->adminEmailNotifications as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key, 'type' => 'mails'],
                ['value' => $value]
            );
        }
                 // E-Mail-Einstellungen für Admins
                 $mailSettings = Setting::where('type', 'mails')->get();
                 foreach ($mailSettings as $setting) {
                     if ($setting->key === 'admin_email') {
                         $this->adminEmail = $setting->value;
                     } elseif (array_key_exists($setting->key, $this->adminEmailNotifications)) {
                         $this->adminEmailNotifications[$setting->key] = json_decode($setting->value);
                     } elseif (array_key_exists($setting->key, $this->userEmailNotifications)) {
                         $this->userEmailNotifications[$setting->key] = json_decode($setting->value);
                     }
                 }
        $this->dispatch('showAlert',"Admin E-Mail Einstellungen wurden gespeichert.", 'success');
    }
    
    public function saveUserMailSettings()
    {
        foreach ($this->userEmailNotifications as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key, 'type' => 'mails'],
                ['value' => json_encode($value)]
            );
        }
                 // E-Mail-Einstellungen für Admins
                 $mailSettings = Setting::where('type', 'mails')->get();
                 foreach ($mailSettings as $setting) {
                     if ($setting->key === 'admin_email') {
                         $this->adminEmail = json_decode($setting->value);
                     } elseif (array_key_exists($setting->key, $this->adminEmailNotifications)) {
                         $this->adminEmailNotifications[$setting->key] = json_decode($setting->value);
                     } elseif (array_key_exists($setting->key, $this->userEmailNotifications)) {
                         $this->userEmailNotifications[$setting->key] = json_decode($setting->value);
                     }
                 }
                 $this->dispatch('showAlert',"Benutzer E-Mail Einstellungen wurden gespeichert.", 'success');

    }
    
    public function saveAdminEmail()
    {
        Setting::updateOrCreate(
            ['key' => 'admin_email', 'type' => 'mails'],
            ['value' => $this->adminEmail]
        );
        $mailSettings = Setting::where('type', 'mails')->get();
        foreach ($mailSettings as $setting) {
            if ($setting->key === 'admin_email') {
                $this->adminEmail = $setting->value;
            } elseif (array_key_exists($setting->key, $this->adminEmailNotifications)) {
                $this->adminEmailNotifications[$setting->key] = json_decode($setting->value);
            } elseif (array_key_exists($setting->key, $this->userEmailNotifications)) {
                $this->userEmailNotifications[$setting->key] = json_decode($setting->value);
            }
        }
        $this->dispatch('showAlert','Admin E-Mail Adresse wurde gespeichert.', 'success');
    }
    // Funktion zum Laden der Verkaufsflächen für die ausgewählte Location
    public function updatedNewBlockedDayLocation($locationId)
    {
        // Laden der Verkaufsflächen für die ausgewählte Location
        $this->retailSpaces = RetailSpace::where('location_id', $locationId)->get();

        // Regale zurücksetzen, wenn Location geändert wird
        $this->newBlockedDayRetailSpaceId = null; 
        $this->newBlockedDayShelfId = null; // Regal zurücksetzen
    }

    // Funktion zum Laden der Regale für die ausgewählte Verkaufsfläche
    public function updatedNewBlockedDayRetailSpaceId($retailSpaceId)
    {
        // Regale für die ausgewählte Verkaufsfläche laden
        $this->shelves = Shelve::where('retail_space_id', $retailSpaceId)->get();
        
        // Regal zurücksetzen, falls eine Verkaufsfläche ausgewählt wird
        $this->newBlockedDayShelfId = null; 
    }

    public function addBlockedDay()
    {
        $this->validate([
            'newBlockedDayLocation' => 'required|exists:locations,id',
            'newBlockedDayRetailSpaceId' => 'required|exists:retail_spaces,id',
            'newBlockedDayShelfId' => 'required|exists:shelves,id', 
            'newBlockedDayStartDate' => 'required|date|after_or_equal:today', 
            'newBlockedDayEndDate' => 'required|date|after_or_equal:newBlockedDayStartDate', 
        ], [
            'newBlockedDayLocation.required' => 'Bitte wählen Sie einen Standort aus.',
            'newBlockedDayLocation.exists' => 'Der ausgewählte Standort existiert nicht.',
            'newBlockedDayRetailSpaceId.required' => 'Bitte wählen Sie eine Verkaufsfläche aus.',
            'newBlockedDayRetailSpaceId.exists' => 'Die ausgewählte Verkaufsfläche existiert nicht.',
            'newBlockedDayShelfId.required' => 'Bitte wählen Sie ein Regal aus.',
            'newBlockedDayShelfId.exists' => 'Das ausgewählte Regal existiert nicht.',
            'newBlockedDayStartDate.required' => 'Bitte geben Sie ein Startdatum an.',
            'newBlockedDayStartDate.date' => 'Das Startdatum muss ein gültiges Datum sein.',
            'newBlockedDayStartDate.after_or_equal' => 'Das Startdatum muss heute oder in der Zukunft liegen.',
            'newBlockedDayEndDate.required' => 'Bitte geben Sie ein Enddatum an.',
            'newBlockedDayEndDate.date' => 'Das Enddatum muss ein gültiges Datum sein.',
            'newBlockedDayEndDate.after_or_equal' => 'Das Enddatum muss nach dem Startdatum liegen.',
        ]);

        // Datumbereich von Start- bis Enddatum
        $startDate = \Carbon\Carbon::parse($this->newBlockedDayStartDate);
        $endDate = \Carbon\Carbon::parse($this->newBlockedDayEndDate);
        
        // Iteration über den Zeitraum, um jedes Datum zu prüfen
        $dateRange = \Carbon\CarbonPeriod::create($startDate, '1 day', $endDate);

        foreach ($dateRange as $date) {
            // Überprüfen, ob der Tag bereits für das Regal blockiert ist
            $existingBlockedDay = ShelfBlockedDate::where('shelf_id', $this->newBlockedDayShelfId)
                ->where('blocked_date', $date->format('Y-m-d'))
                ->exists();

            if ($existingBlockedDay) {
                // Fehler, wenn ein Datum bereits blockiert ist
                $this->dispatch('showAlert', "Der Tag {$date->format('d.m.Y')} ist bereits für das Regal blockiert.", 'warning');
                return;
            }

            // Überprüfen, ob das Regal an diesem Tag bereits gebucht ist
            $existingBooking = ShelfRental::where('shelf_id', $this->newBlockedDayShelfId)
                ->where(function ($query) use ($date) {
                    $query->whereBetween('rental_start', [$date->startOfDay(), $date->endOfDay()])
                        ->orWhereBetween('rental_end', [$date->startOfDay(), $date->endOfDay()])
                        ->orWhere(function ($query) use ($date) {
                            $query->where('rental_start', '<=', $date->startOfDay())
                                    ->where('rental_end', '>=', $date->endOfDay());
                        });
                })
                ->exists();

            if ($existingBooking) {
                // Fehler, wenn das Regal an diesem Tag bereits gebucht ist
                $this->dispatch('showAlert',"Das Regal ist am {$date->format('d.m.Y')} bereits gebucht.", 'warning');
                return;
            }
        }

        AdminShelfBlockedDate::create([
            'shelve_id' => $this->newBlockedDayShelfId,
            'retail_space_id' => $this->newBlockedDayRetailSpaceId,
            'start_date' => $startDate,
            'end_date' => $endDate,
        ]);
        // Wenn kein Konflikt besteht, blockierte Tage speichern
        foreach ($dateRange as $date) {
            ShelfBlockedDate::create([
                'shelf_id' => $this->newBlockedDayShelfId,
                'retail_space_id' => $this->newBlockedDayRetailSpaceId,
                'blocked_date' => $date->format('Y-m-d'),
            ]);
        }
        

        $this->AdminblockedShelves = AdminShelfBlockedDate::all();
        // Feedback für den Benutzer, dass die Daten gespeichert wurden
        $this->dispatch('showAlert','Die gesperrten Tage wurden erfolgreich gespeichert.', 'success');


        // Zurücksetzen der Formularwerte
        $this->reset([
            'newBlockedDayLocation',
            'newBlockedDayRetailSpaceId',
            'newBlockedDayShelfId',
            'newBlockedDayStartDate',
            'newBlockedDayEndDate'
        ]);
    }

    public function deleteBlockedShelf($blockedShelfId)
    {
        // Das blockierte Regal finden, das gelöscht werden soll
        $blockedShelf = AdminShelfBlockedDate::find($blockedShelfId);
        
        if (!$blockedShelf) {
            // Wenn das Regal nicht gefunden wurde, eine Fehlermeldung senden
            $this->dispatch('showAlert', 'Das angegebene Regal wurde nicht gefunden.', 'warning');
            return;
        }
    
        // Alle blockierten Tage für das Regal löschen
        $ShelfBlockedDates = ShelfBlockedDate::where('shelf_id', $blockedShelf->shelve_id)
            ->where('retail_space_id', $blockedShelf->retail_space_id)
            ->whereBetween('blocked_date', [$blockedShelf->start_date, $blockedShelf->end_date])->get();
         foreach ($ShelfBlockedDates as $ShelfBlockedDate) {
            $deleted =  $ShelfBlockedDate->delete();
         }
         

        // Überprüfen, ob es blockierte Gesamttage für den Retail Space gibt und löschen
        $blockedDates = BlockedDate::where('retail_space_id', $blockedShelf->retail_space_id)
        ->whereBetween('blocked_date', [$blockedShelf->start_date, $blockedShelf->end_date])
        ->get();

        // Löschen der blockierten Gesamttage, falls vorhanden
        foreach ($blockedDates as $blockedDate) {
            $deleted =  $blockedDate->delete();
        }
    
        // Löschen der Blockierung im Admin-Modell
        $deleted = $blockedShelf->delete();
        $this->AdminblockedShelves = AdminShelfBlockedDate::all();

        if ($deleted) {
            // Erfolgsnachricht senden, wenn die blockierten Tage gelöscht wurden
            $this->dispatch('showAlert', 'Die Sperre für das Regal wurde erfolgreich entfernt.', 'success');
        } else {
            // Wenn keine geblockten Tage gefunden wurden, die gelöscht werden konnten
            $this->dispatch('showAlert', 'Es wurden keine blockierten Tage zum Löschen gefunden.', 'warning');
        }
    }

    private function getCategoryChildrenData($children)
    {
        $data = [];
        foreach ($children as $child) {
            $data[$child->id] = [
                'id' => $child->id, // ID des Childs
                'name' => $child->name,
                'slug' => $child->slug,
                'children' => $this->getCategoryChildrenData($child->children),
            ];
        }
        return $data;
    }

    public function addHoliday()
    {
        $this->validate([
            'newHolidayDate' => 'required|date',
            'newHolidayName' => 'required|string|max:255',
        ]);

        Setting::create([
            'key' => $this->newHolidayName,
            'value' => $this->newHolidayDate,
            'type' => 'holiday',
        ]);

        $this->reset(['newHolidayDate', 'newHolidayName']);
        $this->loadSettings();
        $this->dispatch('showAlert', 'Feiertag hinzugefügt!', 'success');
        session()->flash('success', 'Feiertag hinzugefügt!');
    }

    public function deleteHoliday($id)
    {
        Setting::findOrFail($id)->delete();
        $this->loadSettings();
        
        $this->dispatch('showAlert', 'Feiertag gelöscht!', 'success');
        session()->flash('success', 'Feiertag gelöscht!');
    }
    public function saveOrUpdatePeriod()
    {
        if ($this->newPeriodSettingId) {
            // Update für eine bestehende Periode
            $period = Setting::find($this->newPeriodSettingId);
            $period->update([
                'value' => json_encode([
                    'duration' => $this->newPeriodDuration,
                    'price' => $this->newPeriodPrice,
                    'description' => $this->newPeriodDescription,
                ])
            ]);
            $this->dispatch('showAlert', 'Periode erfolgreich aktualisiert!', 'success');
        } else {
            // Speichern für eine neue Periode (mit deaktiviertem Status)
            Setting::create([
                'key' => 'period_' . time(),
                'value' => json_encode([
                    'duration' => $this->newPeriodDuration,
                    'price' => $this->newPeriodPrice,
                    'description' => $this->newPeriodDescription,
                    'is_active' => 0,  // Status auf "deaktiviert" setzen
                ]),
                'type' => 'period',
            ]);
            $this->periods = Setting::where('type', 'period')->get();
            $this->dispatch('showAlert', 'Neue Periode erfolgreich gespeichert!', 'success');
        }
    
        // Reset der Eingabefelder und Zurücksetzen des Bearbeitungsmodus
        $this->reset(['newPeriodDuration', 'newPeriodPrice', 'newPeriodDescription', 'newPeriodSettingId']);
    }

    public function saveOrUpdateProvision()
    {
        // Neue Provision speichern (keine Updates, damit alte Werte rückwirkend abrufbar bleiben)
        Setting::create([
            'key' => 'provision_' . now()->timestamp, // Eindeutiger Schlüssel mit Timestamp
            'value' => json_encode([
                'percentage' => $this->provision,
            ]),
            'type' => 'general',
        ]);
    
        $this->dispatch('showAlert', 'Neue Provision erfolgreich gespeichert!', 'success');
    }

    public function editPeriod($id)
    {
        // Holen der bestehenden Periode
        $period = Setting::find($id);

        if ($period) {
            $periodData = json_decode($period->value, true);

            // Setzen der Werte für das Formular
            $this->newPeriodSettingId = $id;
            $this->newPeriodDuration = $periodData['duration'];
            $this->newPeriodPrice = $periodData['price'];
            $this->newPeriodDescription = $periodData['description'];
            $this->newPeriodStatus = $periodData['is_active']; // Status der Periode

            // Setze den Bearbeitungsmodus
            $this->periodisEditing = true;
        } else {
            $this->dispatch('showAlert', 'Periode nicht gefunden.', 'error');
        }
    }
    // Löschen einer Periode
    public function deletePeriod($id)
    {
        $period = Setting::find($id);
    
        if ($period) {
            $period->delete();
            $this->periods = Setting::where('type', 'period')->get();
            $this->dispatch('showAlert', 'Periode erfolgreich gelöscht!', 'success');
        } else {
            $this->dispatch('showAlert', 'Periode nicht gefunden.', 'error');
        }
    }
    public function toggleActivation($id)
    {
        $period = Setting::find($id);
        $periodData = json_decode($period->value, true);
        if ($period) {
            // Toggle des Aktivierungsstatus
            $newStatus = $periodData['is_active'] ? false : true;
            $periodData['is_active'] = $newStatus;
            $period->update(['value' => json_encode($periodData)]);
            // Benachrichtigung je nach neuem Status
            if ($newStatus) {
                $this->dispatch('showAlert', 'Periode erfolgreich aktiviert.', 'success');
            } else {
                
                $this->dispatch('showAlert', 'Periode erfolgreich deaktiviert.', 'warning');
            }
            $this->periods = Setting::where('type', 'period')->get();
        } else {
            $this->dispatch('showAlert', 'Periode nicht gefunden.', 'error');
        }
    }
    public function periodReset(){    
        $this->reset(['newPeriodDuration', 'newPeriodPrice', 'newPeriodDescription', 'newPeriodSettingId', 'periodisEditing']);
        $this->dispatch('showAlert', 'Formular wurde zurückgesetzt.', 'info');
    }
    public function saveBlockedDays()
    {
        // Entferne alle bisherigen Einträge
        Setting::where('type', 'disabled_day')->delete();
    
        // Speichere die neuen ausgewählten Tage
        foreach ($this->selectedBlockedDays as $day) {
            Setting::updateOrCreate(
                ['key' => 'disabled_day_' . $day],
                [
                    'value' => $day,
                    'type' => 'disabled_day',
                ]
            );
        }
    
        $this->loadSettings(); // Aktualisiere die Einstellungen
        $this->dispatch('showAlert', 'Gesperrte Wochentage erfolgreich gespeichert.', 'success');
        session()->flash('success', 'Gesperrte Wochentage erfolgreich gespeichert.');
    }

    public function deleteBlockedDay($id)
    {
        Setting::findOrFail($id)->delete();
        $this->loadSettings();
        $this->dispatch('showAlert', 'Gesperrter Tag gelöscht!', 'success');

    }

    public function saveBookingOptimization()
    {
        // Speichere die Optimierungen der Buchung
    }


    public function saveCategory($categoryId)
    {
        $category = Category::find($categoryId);
        if ($category) {
            $category->update([
                'name' => $this->categoriesData[$categoryId]['name'],
                'slug' => $this->categoriesData[$categoryId]['slug'],
            ]);

            $this->loadCategories();
            $this->dispatch('showAlert', 'Kategorie aktualisiert.', 'success');
            session()->flash('success', 'Kategorie aktualisiert.');
        }
    }

    public function deleteCategory($categoryId)
    {
        $category = Category::find($categoryId);
        if ($category) {
            $category->delete();
            $this->loadCategories();
            $this->dispatch('showAlert', 'Kategorie gelöscht.', 'success');
            session()->flash('success', 'Kategorie gelöscht.');
        }
    }

    public function addCategory()
    {
        $this->validate([
            'newCategoryName' => 'required|string|max:255',
            'newCategorySlug' => 'required|string|max:255|unique:categories,slug',
        ]);

        Category::create([
            'name' => $this->newCategoryName,
            'slug' => $this->newCategorySlug,
            'parent_id' => $this->newCategoryParentId,
        ]);

        $this->reset(['newCategoryName', 'newCategorySlug', 'newCategoryParentId']);
        $this->loadCategories();
        $this->dispatch('showAlert', 'Kategorie hinzugefügt.', 'success');
        session()->flash('success', 'Kategorie hinzugefügt.');
    }

    public function render()
    {
        return view('livewire.admin-config')->layout('layouts.master');
    }
}
