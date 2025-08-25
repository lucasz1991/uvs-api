<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ShelfRental; 
use App\Models\Product;
use App\Models\RetailSpace;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Locked;

class ShelfShow extends Component
{
    #[Locked] 
    public $shelfRentalId; 
    #[Locked] 
    public $shelfRental; 
    public $products = []; 
    public $retailSpace; 
    public $followers; 

    public $isFollowing = false;    

    protected $listeners = ['redirectLoginWishlist'];

    public function mount($shelfRentalId)
    {
        $this->shelfRentalId = $shelfRentalId;
        $this->shelfRental = ShelfRental::findOrFail($this->shelfRentalId);
         // Überprüfung, ob die Mietzeit abgelaufen ist
        if ($this->shelfRental->status != 2 ) {
            abort(403, 'Diese Regalmiete ist abgelaufen und nicht mehr verfügbar.');
        }
        $this->products = $this->shelfRental->products;

        $this->retailSpace = $this->shelfRental->shelf->retailSpace->layout;

        if (auth()->check()) {
            
            $this->isFollowing = auth()->user()->followedCustomers()->where('customer_id', $this->shelfRental->customer->user->id)->exists();
        }
    }

    public function toggleFollow()
    {
        // Benutzer, der dem Regalbesitzer folgt oder entfolgen möchte
        $userToToggle = $this->shelfRental->customer->user;
    
        // Wenn kein Benutzer eingeloggt oder der Regalbesitzer nicht vorhanden ist, abbrechen
        if (!$userToToggle || !auth()->check()) {
            $this->dispatch('showAlert', 'Um einem Verkäufer zu folgen musst du dich erst anmelden.', 'info');
            return;
        }
        $isFollowing = auth()->user()
            ->followedCustomers()
            ->where('customer_id', $userToToggle->id) // Prüfen, ob der Regalbesitzer in der Liste der gefolgten Benutzer ist
            ->exists();
        // Prüfen, ob der aktuell eingeloggte Benutzer bereits folgt
        if ($isFollowing) {
            // Entfolgen
            auth()->user()->followedCustomers()->detach($userToToggle->id);
            $this->isFollowing = false;
            $this->dispatch('showAlert', 'Du wirst nicht mehr bei Aktionen des Verkäufers benachrichtigt.', 'info');
        } else {
            // Folgen
            auth()->user()->followedCustomers()->attach($userToToggle->id);
            $this->isFollowing = true;
            $this->dispatch('showAlert', 'Du wirst bei Aktionen des Verkäufers benachrichtigt.', 'success');
        }
    
    }

    public function redirectLoginWishlist()
    {
        session()->flash('message', 'Bitte melde dich an, um dieses Produkt zu deiner Wunschliste hinzuzufügen. Wenn du noch kein Konto hast, kannst du dich registrieren und alle Funktionen unserer Seite nutzen.');
        session()->flash('messageType', 'warning');
        $this->redirect('/login', navigate: true);
    }

    public function toggleLikedProduct($productId)
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login');
        }

        if ($user->likedProducts()->where('product_id', $productId)->exists()) {
            // Produkt aus LikedProducts entfernen
            $user->likedProducts()->detach($productId);
        } else {
            // Produkt zu LikedProducts hinzufügen   
            $user->likedProducts()->attach($productId);
        }
        // Event auslösen
        $this->dispatch('likedProductsUpdated');
    }

    public function render()
    {
        return view('livewire.shelf-show', [
            'retailSpace' => $this->retailSpace, 
        ])->layout('layouts.app');
    }
}
