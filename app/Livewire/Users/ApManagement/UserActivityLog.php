<?php

namespace App\Livewire\Users\ApManagement;

use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Activitylog\Models\Activity;

class UserActivityLog extends Component
{
    use WithPagination;

    public int $userId;

    public function mount(int $userId): void
    {
        $this->userId = $userId;
    }

    public function render()
    {
        $activities = Activity::query()
            ->where('causer_type', \App\Models\User::class)
            ->where('causer_id', $this->userId)
            ->latest()
            ->paginate(10);

        return view('livewire.users.ap-management.user-activity-log', [
            'activities' => $activities,
        ]);
    }
}
