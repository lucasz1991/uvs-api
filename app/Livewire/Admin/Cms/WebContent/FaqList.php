<?php

namespace App\Livewire\Admin\Cms\WebContent;

use Livewire\Component;
use App\Models\WebContent;
use Illuminate\Support\Carbon;

class FaqList extends Component
{
    public $contents = [];
    public $newKey;
    public $newValue;
    public $newType = 'faq';
    public $faqModalOpen = false;

    public function mount()
    {
        $this->loadContents();
    }

    public function loadContents()
    {
        $this->contents = WebContent::where('type', 'faq')->get();
    }

    public function addContent()
    {
        $this->validate([
            'newKey' => 'required|string|max:255|unique:web_contents,key',
            'newValue' => 'required|string',
            'newType' => 'required|in:text,html,faq',
        ]);

        WebContent::create([
            'key' => $this->newKey,
            'value' => $this->newValue,
            'type' => $this->newType,
        ]);

        $this->reset(['newKey', 'newValue', 'newType']);
        $this->loadContents();
        session()->flash('success', 'WebContent hinzugefügt!');
    }

    public function deleteContent($id)
    {
        WebContent::findOrFail($id)->delete();
        $this->loadContents();
        session()->flash('success', 'WebContent gelöscht!');
    }

    public function render()
    {
        return view('livewire.admin.cms.web-content.faq-list');
    }
}
