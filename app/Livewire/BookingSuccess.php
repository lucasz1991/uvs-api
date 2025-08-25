<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ShelfRental;

class BookingSuccess extends Component
{

    public $shelfRental;

    public function mount($shelfRentalId)
    {
        $this->shelfRental = ShelfRental::find($shelfRentalId);

        if (!$this->shelfRental) {
            return redirect()->route('booking');
        }
    }

    public function render()
    {
        return view('livewire.booking-success')->layout('layouts.app');
    }
}
