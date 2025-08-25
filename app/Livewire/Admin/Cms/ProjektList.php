<?php

namespace App\Livewire\Admin\Cms;

use Livewire\Component;
use App\Models\PagebuilderProject;

class ProjektList extends Component
{
    public $projects = [];

    protected $listeners = [
        'refreshProjects' => 'loadProjects',
        'orderProject' => 'handleOrderProject'
    ];

    public function mount()
    {
        $this->loadProjects();
    }

    public function loadProjects()
    {
        $this->projects = PagebuilderProject::where('type', 'module')->orderBy('order_id')->get();
    }

    public function handleOrderProject($item, $position)
    {
        if (!isset($item, $position)) {
            return;
        }
    
        $movedProject = PagebuilderProject::find($item['id']);
        if (!$movedProject) {
            return;
        }
    
        $newPosition = (int) $position;
    
        // Lade alle Projekte nach `order_id`
        $projects = PagebuilderProject::orderBy('order_id')->get();
    
        // Entferne das verschobene Projekt
        $filteredProjects = $projects->reject(fn ($p) => $p->id == $movedProject->id)->values();
    
        // **Erstelle eine NEUE Liste mit richtiger Reihenfolge**
        $newProjects = collect();
        foreach ($filteredProjects as $index => $project) {
            if ($index == $newPosition) {
                $newProjects->push($movedProject); // **Hier setzen wir es an die richtige Stelle**
            }
            $newProjects->push($project);
        }
    
        // Falls das Projekt ans **Ende** verschoben wurde
        if ($newPosition >= $filteredProjects->count()) {
            $newProjects->push($movedProject);
        }
    
        // **Setze die neue Reihenfolge in der Datenbank**
        foreach ($newProjects as $index => $project) {
            PagebuilderProject::where('id', $project->id)->update(['order_id' => $index]);
        }
    
        $this->loadProjects(); // Aktualisierte Projekte neu laden
    }
    
    public function deleteProject($id)
    {
        PagebuilderProject::findOrFail($id)->delete();
        $this->loadProjects();
    }

    public function duplicateProject($id)
    {
        $project = PagebuilderProject::findOrFail($id);

        // Generiere einen neuen Namen mit Zufallsnummer
        $newProjectName = $project->name . ' (Kopie ' . rand(1000, 9999) . ')';
        $maxOrderId = PagebuilderProject::max('order_id') ?? 0;

        // Erstelle die Kopie mit den gleichen Werten
        $newProject = $project->replicate();
        $newProject->name = $newProjectName;
        $newProject->status = 0;
        $newProject->order_id = $maxOrderId + 1;
        $newProject->save();

        $this->loadProjects();
    }

    public function toggleLockProject($id)
    {
        $project = PagebuilderProject::findOrFail($id);
        
        // Toggle: Wenn `lock` true ist, setze auf false - und umgekehrt
        $project->update(['lock' => !$project->lock]);
        $this->loadProjects();
    }

    public function render()
    {
        return view('livewire.admin.cms.projekt-list');
    }
}
