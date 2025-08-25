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
    

    public function render()
    {
        return view('livewire.admin-config')->layout('layouts.master');
    }
}
