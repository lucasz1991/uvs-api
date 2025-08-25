<?php

namespace App\Livewire\Admin\UserProfile;

use Livewire\Component;
use App\Models\ShelfRental;
use App\Models\User;

class ShelfRentals extends Component
{
    public $shelfRentals; // Variable für Daten
    public $userId;

    public function mount($userId)
    {
        $this->userId = $userId;

        // Lade die Regalbuchungen des Benutzers anhand der $userId
        $user = User::findOrFail($this->userId); // Benutzer abrufen
        $this->shelfRentals = ShelfRental::where('customer_id', $user->customer->id)->get();
    }

    public function render()
    {
        return view('livewire.admin.user-profile.shelf-rentals', [
            'shelfRentals' => $this->shelfRentals
        ]);
    }
}
