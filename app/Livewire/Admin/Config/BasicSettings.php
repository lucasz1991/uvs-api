<?php

namespace App\Livewire\Admin\Config;

use Livewire\Component;
use App\Models\Setting;

class BasicSettings extends Component
{

    public $maintenanceMode = false;

    public $hostname, $database, $username, $password;
    public $hostname_dev, $database_dev, $username_dev, $password_dev;

    public function mount()
    {
        $this->hostname = Setting::getValue('database', 'hostname');
        $this->database = Setting::getValue('database', 'database');
        $this->username = Setting::getValue('database', 'username');
        $this->password = Setting::getValue('database', 'password');
        $this->maintenanceMode = Setting::getValue('base', 'maintenance_mode');
        // dev Settings
        $this->hostname_dev = Setting::getValue('database', 'hostname_dev');
        $this->database_dev = Setting::getValue('database', 'database_dev');
        $this->username_dev = Setting::getValue('database', 'username_dev');
        $this->password_dev = Setting::getValue('database', 'password_dev');
    }

    public function saveSettings()
    {
        $this->validate([
            'hostname' => 'nullable|string|max:255',
            'database' => 'nullable|string|max:255',
            'username' => 'nullable|string|max:255',
            'password' => 'nullable|string|max:255',
// dev Settings
            'hostname_dev' => 'nullable|string|max:255',
            'database_dev' => 'nullable|string|max:255',
            'username_dev' => 'nullable|string|max:255',
            'password_dev' => 'nullable|string|max:255',
        ]);

        // Grundlegende Einstellungen speichern
        Setting::setValue('database', 'hostname', $this->hostname);
        Setting::setValue('database', 'database', $this->database);
        Setting::setValue('database', 'username', $this->username);
        Setting::setValue('database', 'password', $this->password);
// dev Settings
        Setting::setValue('database', 'hostname_dev', $this->hostname_dev);
        Setting::setValue('database', 'database_dev', $this->database_dev);
        Setting::setValue('database', 'username_dev', $this->username_dev);
        Setting::setValue('database', 'password_dev', $this->password_dev);

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
