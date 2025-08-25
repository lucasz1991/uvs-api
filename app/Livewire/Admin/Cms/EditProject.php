<?php

namespace App\Livewire\Admin\Cms;

use Livewire\Component;
use App\Models\PagebuilderProject;
use App\Models\Setting;
use Illuminate\Support\Carbon;

class EditProject extends Component
{
    public $projectId;
    public $project;
    public $grapejsSetting;

    public $editModalOpen = false;
    public $projectName;
    public $position;
    public $status;
    public $publishedFrom;
    public $publishedUntil;
    public $orderId;

    // Lädt das Projekt, wenn die Komponente initialisiert wird
    public function mount($projectId = null)
    {
        $this->grapejsSetting = Setting::where('type', 'api-grapejs')->first()->value ?? false;
        if ($projectId){
            $this->projectId = $projectId;
            $this->project = PagebuilderProject::findOrFail($this->projectId);
        }
        if (!$this->project){
            // Generiere einen einzigartigen Projektnamen mit einer 4-stelligen Zufallsnummer
            $randomNumber = rand(1000, 9999);
            $projectName = "Neues Projekt {$randomNumber}";
            $projectData = '{"assets":[],"styles":[],"pages":[{"frames":[{"component":{"type":"wrapper","attributes":{"id":"itix"},"components":[{"tagName":"section","classes":["text-gray-600","body-font","relative"],"attributes":{"id":"iyduu"},"components":[{"classes":["container","px-5","py-24","mx-auto"],"attributes":{"id":"i91ng"},"components":[{"classes":["flex","flex-col","text-center","w-full","mb-12"],"attributes":{"id":"in4uu"},"components":[{"type":"heading","classes":["sm:text-3xl","text-2xl","font-medium","title-font","mb-4","text-gray-900"],"attributes":{"id":"igmy6"},"components":[{"type":"textnode","content":"Neues Pagebuilder Project"}]},{"tagName":"p","type":"text","classes":["lg:w-2/3","mx-auto","leading-relaxed","text-base"],"attributes":{"id":"i0w6e"},"components":[{"type":"textnode","content":"Hier kannst du kreativ werden und deine Träume verwirklichen!"}]}]}]}]}],"doctype":"<!DOCTYPE html>","head":{"type":"head","components":[{"tagName":"meta","void":true,"attributes":{"charset":"utf-8"}},{"tagName":"meta","void":true,"attributes":{"name":"viewport","content":"width=device-width,initial-scale=1"}},{"tagName":"meta","void":true,"attributes":{"name":"robots","content":"index,follow"}},{"tagName":"meta","void":true,"attributes":{"name":"generator","content":"LMZ Studio Project"}},{"tagName":"link","type":"link","attributes":{"href":"https://admin850.regulierungs-check.de/adminresources/css/tailwind.min.css","rel":"stylesheet"}}]},"docEl":{"tagName":"html"}},"id":"8uKM3pEMmO8ZbWvE"}],"type":"main","id":"BGeRYNcKhJpNIMjv"}],"symbols":[],"dataSources":[],"custom":{"projectType":"web","id":""}}';
              
            // Neues Projekt initialisieren
            $maxOrderId = PagebuilderProject::max('order_id') ?? 0;
            $maxOrderIdIterated = $maxOrderId + 1;
            $this->project = PagebuilderProject::create([
                'name' => $projectName,
                'data' => $projectData,
                'status' => 0, 
                'order_id' => $maxOrderIdIterated,
            ]);
            $this->projectId = $this->project->id;
        }
    }

    public function render()
    {
        return view('livewire.admin.cms.edit-project', [
            'project' => $this->project,
            'grapejsSetting' => $this->grapejsSetting,
        ])->layout('layouts.master');
    }
}
