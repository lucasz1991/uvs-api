<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ShelfRental;
use App\Models\Product;
use App\Models\ShelfBlockedDate;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Locked;


class ShelfRentalShow extends Component
{
    #[Locked] 
    public $shelfRentalId;
    #[Locked] 
    public $shelfRental;
    public $retailSpace;


    protected $listeners = ['discount-applied' => 'refreshComponent'];

    public function refreshComponent()
    {
        $this->shelfRental = ShelfRental::findOrFail($this->shelfRentalId);
    }

    public function mount($shelfRentalId)
    {
        $this->shelfRentalId = $shelfRentalId;

        $this->shelfRental = ShelfRental::findOrFail($this->shelfRentalId);

        $this->retailSpace = $this->shelfRental->shelf->retailSpace->layout;

        if ($this->shelfRental->customer->user->id !== Auth::id()) {
            abort(403, 'Zugriff verweigert. Du bist nicht der Besitzer dieser Regalmiete.');
        }
    }

    public function deleteProduct($productId)
    {
        $product = Product::findOrFail($productId);
        if ($product->customer->user->id !== Auth::id()) {
            $this->dispatch('showAlert', 'Zugriff verweigert. Du bist nicht der Besitzer dieses Produkts.', 'error');
        }else{
            $product->delete();
            $this->shelfRental->refresh();
            $this->dispatch('showAlert', 'Produkt erfolgreich gelöscht.', 'success');
        }
    }


    public function cancelRental()
    {
        try {
            if (!$this->shelfRental) {
                $this->dispatch('showAlert', 'Regalmiete nicht gefunden.', 'error');
                return;
            }
    
            // Aktive Produkte deaktivieren
            $activeProducts = $this->shelfRental->products->where('status', 2);
            if ($activeProducts->isNotEmpty()) {
                foreach ($activeProducts as $product) {
                    $this->deleteProductFromApi($product);
                    $product->update(['status' => 1]); // Zurück auf Entwurf setzen
                }
            }
    
            // Status der Regalmiete auf 7 (Storniert) setzen
            $this->shelfRental->update(['status' => 7]);
    
            // Blockierte Daten für die Mietzeit löschen
            $deletedBlockedDates = ShelfBlockedDate::where('shelf_id', $this->shelfRental->shelf_id)
                ->where('retail_space_id', $this->shelfRental->shelf->retail_space_id)
                ->whereBetween('blocked_date', [
                    $this->shelfRental->rental_start,
                    $this->shelfRental->rental_end
                ])
                ->delete();
    
            // Erfolgsmeldung
            $this->dispatch('showAlert', 'Regalmiete erfolgreich storniert.', 'success');
    
            return redirect()->route('dashboard'); // Route zum Dashboard

        } catch (\Exception $e) {
            Log::error('Fehler beim Stornieren der Regalmiete:', ['message' => $e->getMessage()]);
            $this->dispatch('showAlert', 'Fehler beim Stornieren der Regalmiete.', 'error');
        }
    }


    

    public function render()
    {
        return view('livewire.shelf-rental-show', [
            'shelfRental' => $this->shelfRental, 
            'retailSpace' => $this->retailSpace, 
        ])->layout('layouts.app');
    }
}