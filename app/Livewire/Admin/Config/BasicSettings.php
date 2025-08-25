<?php

namespace App\Livewire\Admin\Config;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Setting;

class BasicSettings extends Component
{
    use WithFileUploads;

    public $companyName, $shopName, $currency, $timezone, $contactEmail, $vatRate, $maintenanceMode;
    public $favicon, $logoSquare, $logoHorizontal, $logoVertical;
    public $faviconPreview, $logoSquarePreview, $logoHorizontalPreview, $logoVerticalPreview;
    
    public $primaryColor, $secondaryColor, $accentColor, $backgroundColor, $textColor;

    public function mount()
    {
        $this->companyName = Setting::getValue('base', 'company_name');
        $this->shopName = Setting::getValue('base', 'shop_name');
        $this->currency = Setting::getValue('base', 'currency');
        $this->timezone = Setting::getValue('base', 'timezone');
        $this->contactEmail = Setting::getValue('base', 'contact_email');
        $this->vatRate = Setting::getValue('base', 'vat_rate');
        $this->maintenanceMode = Setting::getValue('base', 'maintenance_mode');

        // Bildvorschauen setzen
        $this->faviconPreview = Setting::getValue('base', 'favicon');
        $this->logoSquarePreview = Setting::getValue('base', 'logo_square');
        $this->logoHorizontalPreview = Setting::getValue('base', 'logo_horizontal');
        $this->logoVerticalPreview = Setting::getValue('base', 'logo_vertical');

        // Farben laden
        $this->primaryColor = Setting::getValue('base', 'primary_color');
        $this->secondaryColor = Setting::getValue('base', 'secondary_color');
        $this->accentColor = Setting::getValue('base', 'accent_color');
        $this->backgroundColor = Setting::getValue('base', 'background_color');
        $this->textColor = Setting::getValue('base', 'text_color');
    }

    public function saveSettings()
    {
        $this->validate([
            'companyName' => 'nullable|string|max:255',
            'shopName' => 'nullable|string|max:255',
            'contactEmail' => 'nullable|email',
            'vatRate' => 'nullable|numeric|min:0|max:100',
            'primaryColor' => 'nullable',
            'secondaryColor' => 'nullable',
            'accentColor' => 'nullable',
            'backgroundColor' => 'nullable',
            'textColor' => 'nullable',
            'favicon' => 'nullable|image|max:1024',
            'logoSquare' => 'nullable|image|max:2048',
            'logoHorizontal' => 'nullable|image|max:2048',
            'logoVertical' => 'nullable|image|max:2048',
        ]);

        // Grundlegende Einstellungen speichern
        Setting::setValue('base', 'company_name', $this->companyName);
        Setting::setValue('base', 'shop_name', $this->shopName);
        Setting::setValue('base', 'currency', $this->currency);
        Setting::setValue('base', 'timezone', $this->timezone);
        Setting::setValue('base', 'contact_email', $this->contactEmail);
        Setting::setValue('base', 'vat_rate', $this->vatRate);
        Setting::setValue('base', 'maintenance_mode', $this->maintenanceMode);

        // Farben speichern
        Setting::setValue('base', 'primary_color', $this->primaryColor);
        Setting::setValue('base', 'secondary_color', $this->secondaryColor);
        Setting::setValue('base', 'accent_color', $this->accentColor);
        Setting::setValue('base', 'background_color', $this->backgroundColor);
        Setting::setValue('base', 'text_color', $this->textColor);

        // Bilder speichern und URLs setzen
        $this->storeImage('favicon', 'favicon');
        $this->storeImage('logoSquare', 'logo_square');
        $this->storeImage('logoHorizontal', 'logo_horizontal');
        $this->storeImage('logoVertical', 'logo_vertical');

        session()->flash('success', 'Einstellungen erfolgreich gespeichert.');
    }

    /**
     * Speichert das Bild und setzt die URL im Setting.
     */
    private function storeImage($imageProperty, $settingKey)
    {
        if ($this->$imageProperty) {
            // Bild speichern
            $path = $this->$imageProperty->store('settings', 'public');

            // URL im Setting speichern
            Setting::setValue('base', $settingKey, asset('storage/' . $path));
        }
    }

    public function render()
    {
        return view('livewire.admin.config.basic-settings');
    }
}
