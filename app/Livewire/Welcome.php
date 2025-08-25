<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class Welcome extends Component
{

    public $mostViewedProducts;


    protected $listeners = ['redirectLoginWishlist'];

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



    public function mount()
    {
        $this->mostViewedProducts = Product::where('status', 2)
        ->orderBy('views', 'desc')
        ->take(8)
        ->get();
    }
    public function render()
    {   
        if(auth()->user() && auth()->user()->role === 'admin'){
            $this->redirect('/admin/admindashboard', navigate: true);
        }
        
        return view('livewire.welcome')->layout('layouts/app');
    }
}