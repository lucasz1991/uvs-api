<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ShelfRental;
use Livewire\WithPagination;

class AdminShelfRentalList extends Component
{
    use WithPagination;

    public $shelfRentalsCount;
    public $search = '';
    public $sortBy = 'status';
    public $sortDirection = 'asc';
    protected $paginationTheme = 'tailwind';


    protected $queryString = ['search', 'sortBy', 'sortDirection'];
    public function mount()
    {
        $this->shelfRentalsCount = ShelfRental::count();
    }

    /**
     * Aktualisiert das Sortierfeld und die Sortierrichtung.
     *
     * @param string $field
     */
    public function sortByField($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function render()
    {
        $shelfRentals = ShelfRental::with(['customer.user', 'shelf'])
            ->where(function ($query) {
                // Suche im Namen des Benutzers
                $query->whereHas('customer.user', function ($subQuery) {
                    $subQuery->where('name', 'like', '%' . $this->search . '%');
                })
                // Oder Suche in der Regalnummer (floor_number)
                ->orWhereHas('shelf', function ($subQuery) {
                    $subQuery->where('floor_number', 'like', '%' . $this->search . '%');
                })
                // Oder Suche direkt in den Feldern id und rental_start
                ->orWhere('id', 'like', '%' . $this->search . '%')
                ->orWhere('rental_start', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(10);
    
        return view('livewire.admin-shelf-rental-list', [
            'shelfRentals' => $shelfRentals,
        ])->layout('layouts.master');
    }
    
}
