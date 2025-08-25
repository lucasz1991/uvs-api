<?php

namespace App\Livewire\Admin\Cms\Tools;

use Livewire\Component;
use App\Models\Setting;

class AiAssistantConfig extends Component
{
    public $status, $assistantName, $apiUrl, $apiKey, $aiModel, $modelTitle, $refererUrl, $trainContent;

    public function mount()
    {
        $this->status = Setting::getValue('ai_assistant', 'status', 0);
        $this->assistantName = Setting::getValue('ai_assistant', 'assistant_name', '');
        $this->apiUrl = Setting::getValue('ai_assistant', 'api_url', '');
        $this->apiKey = Setting::getValue('ai_assistant', 'api_key', '');
        $this->aiModel = Setting::getValue('ai_assistant', 'ai_model', '');
        $this->modelTitle = Setting::getValue('ai_assistant', 'model_title', '');
        $this->refererUrl = Setting::getValue('ai_assistant', 'referer_url', '');
        $this->trainContent = Setting::getValue('ai_assistant', 'train_content', '');
    }

    public function saveSettings()
    {
        $this->validate([
            'status' => 'boolean',
            'assistantName' => 'nullable|string|max:255',
            'apiUrl' => 'nullable|url',
            'apiKey' => 'nullable|string|max:255',
            'aiModel' => 'nullable|string|max:255',
            'modelTitle' => 'nullable|string|max:255',
            'refererUrl' => 'nullable|url',
            'trainContent' => 'nullable|string',
        ]);

        Setting::setValue('ai_assistant', 'status', $this->status);
        Setting::setValue('ai_assistant', 'assistant_name', $this->assistantName);
        Setting::setValue('ai_assistant', 'api_url', $this->apiUrl);
        Setting::setValue('ai_assistant', 'api_key', $this->apiKey);
        Setting::setValue('ai_assistant', 'ai_model', $this->aiModel);
        Setting::setValue('ai_assistant', 'model_title', $this->modelTitle);
        Setting::setValue('ai_assistant', 'referer_url', $this->refererUrl);
        Setting::setValue('ai_assistant', 'train_content', $this->trainContent);

        session()->flash('success', 'AI-Assistent-Einstellungen erfolgreich gespeichert.');
    }

    public function render()
    {
        return view('livewire.admin.cms.tools.ai-assistant-config');
    }
}
