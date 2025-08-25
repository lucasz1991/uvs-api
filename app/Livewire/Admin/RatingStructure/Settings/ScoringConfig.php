<?php

namespace App\Livewire\Admin\RatingStructure\Settings;

use Livewire\Component;
use App\Models\Setting;

class ScoringConfig extends Component
{
    public  $regulation_speed, $customer_service, $fairness, $transparency, $overall_satisfaction;

    public function mount()
    {
        $this->regulation_speed = Setting::getValue('scoring-config', 'regulation_speed');
        $this->customer_service = Setting::getValue('scoring-config', 'customer_service');
        $this->fairness = Setting::getValue('scoring-config', 'fairness');
        $this->transparency = Setting::getValue('scoring-config', 'transparency');
        $this->overall_satisfaction = Setting::getValue('scoring-config', 'overall_satisfaction');
    }

    public function saveSettings()
    {
        $this->validate([
            'regulation_speed' => 'required|numeric|min:0|max:100',
            'customer_service' => 'required|numeric|min:0|max:100',
            'fairness' => 'required|numeric|min:0|max:100',
            'transparency' => 'required|numeric|min:0|max:100',
            'overall_satisfaction' => 'required|numeric|min:0|max:100',
        ]);

        Setting::setValue('scoring-config', 'regulation_speed', $this->regulation_speed);
        Setting::setValue('scoring-config', 'customer_service', $this->customer_service);
        Setting::setValue('scoring-config', 'fairness', $this->fairness);
        Setting::setValue('scoring-config', 'transparency', $this->transparency);
        Setting::setValue('scoring-config', 'overall_satisfaction', $this->overall_satisfaction);

        session()->flash('success', 'Scoring-Konfiguration erfolgreich gespeichert.');
    }

    public function render()
    {
        return view('livewire.admin.rating-structure.settings.scoring-config');
    }
}
