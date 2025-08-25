<?php

namespace App\Livewire\Admin\Dashboard;

use Livewire\Component;
use Carbon\Carbon;
use App\Models\ShelfRental;

class UpcomingCheckOuts extends Component
{

    public $filterDate = 'today'; // Standard: Heute
    public $checkOuts;

    public function mount()
    {
        $this->filterCheckOuts();
    }

    public function updatedFilterDate()
    {
        $this->filterCheckOuts();
    }

    public function filterCheckOuts()
    {
        $now = Carbon::now();

        switch ($this->filterDate) {
            case 'yesterday':
                $start = $now->copy()->subDay()->startOfDay();
                $end = $now->copy()->subDay()->endOfDay();
                break;
            case 'tomorrow':
                $start = $now->copy()->addDay()->startOfDay();
                $end = $now->copy()->addDay()->endOfDay();
                break;
            case 'week':
                $start = $now->copy()->startOfWeek();
                $end = $now->copy()->endOfWeek();
                break;
            default: // today
                $start = $now->copy()->startOfDay();
                $end = $now->copy()->endOfDay();
        }

        $this->checkOuts = ShelfRental::whereBetween('rental_end', [$start, $end])
            ->orderBy('rental_end', 'asc')
            ->get();
    }

    public function render()
    {
        return view('livewire.admin.dashboard.upcoming-check-outs');
    }
}
