<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\AdminTask;
use App\Models\ShelfRental;
use App\Models\Payout;
use App\Models\Sale;
use Illuminate\Support\Facades\Auth;



class AdminTasksList extends Component
{
    use WithPagination;

    public function assignToMe($taskId)
    {
        $task = AdminTask::findOrFail($taskId);
        if (!$task->assigned_to) {
            $task->assigned_to = Auth::id();
            $task->status = 1;
            $task->save();
            $this->resetPage();
            $this->dispatch('showAlert', 'Aufgabe erfolgreich übernommen.', 'success');
        }
    }

    public function markAsCompleted($taskId)
    {
        $task = AdminTask::findOrFail($taskId);
        
        if ($task->status == 1) {
            $task->status = 2; 
            $task->save();
            
            if ($task->task_type === 'Auszahlung' && $task->shelf_rental_id) {
                $this->processPayoutCompletion($task->shelf_rental_id);
            }
            
            $this->resetPage();
            $this->dispatch('showAlert', 'Aufgabe erfolgreich abgeschlossen.', 'success');
        }
    }

    private function processPayoutCompletion($shelfRentalId)
    {
        $shelfRental = ShelfRental::find($shelfRentalId);
        if ($shelfRental) {
            $shelfRental->status = 4;
            $shelfRental->save();
        }

        $payout = Payout::where('shelf_rental_id', $shelfRentalId)->latest()->first();
        if ($payout) {
            $payout->status = true;
            $payout->save();
        }

        Sale::where('rental_id', $shelfRentalId)
            ->where('status', 2)
            ->update(['status' => 3]);
    }

    public function render()
    {
        $tasks = AdminTask::orderBy('status', 'asc')
        ->orderBy('created_at', 'desc')
        ->paginate(20);
        return view('livewire.admin.admin-tasks-list', [
            'tasks' => $tasks
        ])->layout('layouts.master');
    }
}
