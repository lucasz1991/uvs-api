<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Locked;

class ProductShow extends Component
{
    #[Locked] 
    public $productId;
    #[Locked] 
    public $product;

    public $similarProducts = [];
    public $shelfProducts = []; 

    public $isFollowing = false;

    protected $listeners = ['redirectLoginWishlist'];

    public function mount($id)
    {
        $this->productId = $id;
        $this->loadProduct();
        $this->dispatch('refreshComponent');
    }

    public function toggleFollow()
    {
        // Benutzer, der dem Regalbesitzer folgt oder entfolgen möchte
        $userToToggle = $this->product->shelfRental->customer->user;
    
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
            $this->dispatch('showAlert', 'Du wirst ab sofort bei Aktionen des Verkäufers nicht mehr benachrichtigt.', 'info');
        } else {
            // Folgen
            auth()->user()->followedCustomers()->attach($userToToggle->id);
            $this->isFollowing = true;
            $this->dispatch('showAlert', 'Du wirst bei Aktionen des Verkäufers benachrichtigt.', 'success');
        }

    
    }

    private function incrementProductViews($productId)
    {
        if (!session()->has("viewed_products.{$productId}")) {
            Product::find($productId)->increment('views');
            session()->put("viewed_products.{$productId}", true);
        }
    }
    
    public function loadProduct()
    {
        try {
            $this->product = Product::with(['category', 'shelfRental.shelf', 'customer'])->findOrFail($this->productId);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            session()->flash('message', 'Ups, das angeforderte Produkt konnten wir leider nicht finden. Schau dich doch in unserer Produktliste um vielleicht entdeckst du etwas anderes, das dir gefällt!');
            return redirect()->route('products');
        }
        if (!$this->product || $this->product->status != 2) {
            session()->flash('message', 'Leider ist das Produkt nicht mehr verfügbar. Aber keine Sorge in unserer Produktliste findest du sicher noch viele andere tolle Dinge! Viel Spaß beim Stöbern!');
            return redirect()->route('products');
        }
        if (auth()->check()) {
            $this->isFollowing = auth()->user()->followedCustomers()->where('customer_id', $this->product->shelfRental->customer->user->id)->exists();
        }
        $this->loadAdditionalProducts();
        $this->incrementProductViews($this->productId);
    }

    public function loadAdditionalProducts()
    {
        // Produkte aus demselben Regal (basierend auf `location_id`)
        $this->shelfProducts = Product::where('shelf_rental_id', $this->product->shelf_rental_id)
            ->where('id', '!=', $this->product->id) // Das aktuelle Produkt ausschließen
            ->where('status', 2)
            ->take(8) // Begrenze auf 8 Produkte
            ->get();

        // Ähnliche Produkte (basierend auf Kategorie)
        $this->similarProducts = Product::where('category', $this->product->category)
            ->where('id', '!=', $this->product->id) // Das aktuelle Produkt ausschließen
            ->where('status', 2)
            ->take(8) // Begrenze auf 8 Produkte
            ->get();
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
        return view('livewire.product-show')->layout('layouts.app'); // Optionale Layout-Datei
    }
}
