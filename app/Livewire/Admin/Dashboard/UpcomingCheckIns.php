<?php

namespace App\Livewire\Admin\Dashboard;

use Livewire\Component;
use Carbon\Carbon;
use App\Models\ShelfRental;

class UpcomingCheckIns extends Component
{
    public $filterDate = 'today'; // Standard: Heute
    public $checkIns;

    public function mount()
    {
        $this->filterCheckIns();
    }

    public function updatedFilterDate()
    {
        $this->filterCheckIns();
    }

    public function filterCheckIns()
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

        $this->checkIns = ShelfRental::whereBetween('rental_start', [$start, $end])
            ->orderBy('rental_start', 'asc')
            ->whereDoesntHave('products', function ($query) {
                $query->where('status', 2);
            })
            ->get();
    }

    public function render()
    {
        return view('livewire.admin.dashboard.upcoming-check-ins');
    }
}
