<?php

namespace App\Livewire\Admin\Cms;

use Livewire\Component;
use App\Models\PagebuilderProject;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Carbon;
use App\Models\WebPage;

class ProjectSettingsManager extends Component
{
    public $showModal = false;
    public $projectId;
    public $name;
    public $page = [];
    public $position = [];
    public $status;
    public $published_from;
    public $published_until;
    public $order_id;
    public $lang;
    public $lock;
    public $webpages = [];

    protected $rules = [
        'name' => 'required|string|max:255',
        'page' => 'array',
        'position' => 'array',
        'status' => 'integer',
        'published_from' => 'nullable|date',
        'published_until' => 'nullable|date',
        'order_id' => 'integer',
        'lang' => 'nullable|string|max:10',
        'lock' => 'boolean',
    ];


    protected $listeners = ['open-project-settings' => 'loadProject'];



    public function loadProject($projectId)
    {
        $project = PagebuilderProject::findOrFail($projectId);
        $this->projectId = $project->id;
        $this->name = $project->name;
        $this->page = $project->page ?? [];
        $this->position = $project->position ?? [];
        $this->status = $project->status;
        $this->published_from = $project->published_from;
        $this->published_until = $project->published_until;
        $this->order_id = $project->order_id;
        $this->lang = $project->lang;
        $this->lock = $project->lock;
        $this->showModal = true;
    }

    public function saveProject()
    {
        $this->validate();
        $cleanedPages = array_values(array_filter($this->page, fn($item) => !is_null($item) && $item !== ''));
        $cleanedPositions = array_values(array_filter($this->position, fn($item) => !is_null($item) && $item !== ''));
        PagebuilderProject::updateOrCreate(
            ['id' => $this->projectId],
            [
                'name' => $this->name,
                'page' => $cleanedPages,
                'position' => $cleanedPositions,
                'status' => $this->status,
                'published_from' => $this->published_from ? $this->published_from : null,
                'published_until' => $this->published_until ? $this->published_until : null,
                'order_id' => $this->order_id ?? (PagebuilderProject::max('order_id') + 1),
                'lang' => $this->lang,
                'lock' => $this->lock,
            ]
        );

        session()->flash('message', 'Projekt erfolgreich gespeichert.');
        $this->showModal = false;
        $this->dispatch('refreshProjects');
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function render()
    {
        $this->webpages = WebPage::all();

        return view('livewire.admin.cms.project-settings-manager', [
            'webpages' => $this->webpages,
        ]);
    }
}
