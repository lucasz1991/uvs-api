<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Activitylog\Models\Activity;
use App\Models\User;

class Safety extends Component
{
    use WithPagination;

    public $search = '';
    public $filterMode = 'all'; 
    public $perPage = 10;

    protected $queryString = ['search', 'perPage'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $activities = Activity::query()
            ->leftJoin('users', 'users.id', '=', 'activity_log.causer_id')
            ->select('activity_log.*', 'users.name')
            ->when($this->filterMode === 'user', function ($query) {
                $query->whereNotNull('activity_log.causer_id');
            })
            ->when($this->filterMode === 'guest', function ($query) {
                $query->whereNull('activity_log.causer_id');
            })
            ->when($this->search, function ($query) {
                $query->where('activity_log.description', 'like', '%' . $this->search . '%')
                ->orWhere('activity_log.causer_type', 'like', '%' . $this->search . '%')
                ->orWhere('users.name', 'like', '%' . $this->search . '%'); 
            })
            ->orderBy('activity_log.created_at', 'desc')
            ->paginate($this->perPage);

        return view('livewire.admin.safety', [
            'activities' => $activities,
        ])->layout('layouts.master');
    }
}
