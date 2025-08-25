<?php

namespace App\Livewire\Admin\Config;

use Livewire\Component;
use App\Models\Setting;

class BasicSettings extends Component
{

    public $maintenanceMode = false;

    public $hostname, $database, $username, $password;

    public function mount()
    {
        $this->hostname = Setting::getValue('database', 'hostname');
        $this->database = Setting::getValue('database', 'database');
        $this->username = Setting::getValue('database', 'username');
        $this->password = Setting::getValue('database', 'password');
        $this->maintenanceMode = Setting::getValue('base', 'maintenance_mode');
    }

    public function saveSettings()
    {
        $this->validate([
            'hostname' => 'nullable|string|max:255',
            'database' => 'nullable|string|max:255',
            'username' => 'nullable|string|max:255',
            'password' => 'nullable|string|max:255',
        ]);

        // Grundlegende Einstellungen speichern
        Setting::setValue('database', 'hostname', $this->hostname);
        Setting::setValue('database', 'database', $this->database);
        Setting::setValue('database', 'username', $this->username);
        Setting::setValue('database', 'password', $this->password);
        Setting::setValue('base', 'maintenance_mode', $this->maintenanceMode);

        session()->flash('success', 'Einstellungen erfolgreich gespeichert.');
    }

    public function updatedMaintenanceMode($value)
    {
        Setting::setValue('base', 'maintenance_mode', $value);
    }

    public function render()
    {
        return view('livewire.admin.config.basic-settings');
    }
}
