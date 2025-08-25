<?php

namespace App\Livewire\Admin\RatingStructure\Settings;

use Livewire\Component;
use App\Models\Setting;

class AiScoringSettings extends Component
{

    public $status, $apiUrl, $apiKey, $aiModel, $modelTitle, $refererUrl;

    public function mount()
    {
        $this->status = Setting::getValue('ai-scoring-settings', 'status', 0);
        $this->apiUrl = Setting::getValue('ai-scoring-settings', 'api_url', '');
        $this->apiKey = Setting::getValue('ai-scoring-settings', 'api_key', '');
        $this->aiModel = Setting::getValue('ai-scoring-settings', 'ai_model', '');
        $this->modelTitle = Setting::getValue('ai-scoring-settings', 'model_title', '');
        $this->refererUrl = Setting::getValue('ai-scoring-settings', 'referer_url', '');
    }

    public function saveSettings()
    {
        $this->validate([
            'status' => 'boolean',
            'apiUrl' => 'nullable|url',
            'apiKey' => 'nullable|string|max:255',
            'aiModel' => 'nullable|string|max:255',
            'modelTitle' => 'nullable|string|max:255',
            'refererUrl' => 'nullable|url',
        ]);

        Setting::setValue('ai-scoring-settings', 'status', $this->status);
        Setting::setValue('ai-scoring-settings', 'api_url', $this->apiUrl);
        Setting::setValue('ai-scoring-settings', 'api_key', $this->apiKey);
        Setting::setValue('ai-scoring-settings', 'ai_model', $this->aiModel);
        Setting::setValue('ai-scoring-settings', 'model_title', $this->modelTitle);
        Setting::setValue('ai-scoring-settings', 'referer_url', $this->refererUrl);

        session()->flash('success', 'Einstellungen erfolgreich gespeichert.');
    }

    public function render()
    {
        return view('livewire.admin.rating-structure.settings.ai-scoring-settings');
    }
}
