<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Wishlist;

class WishlistShow extends Component
{
    public $wishlistItems;
    public $user;

    public function mount($userId)
    {
        $this->user = User::findOrFail($userId);
        $this->wishlistItems = $this->user->likedProducts;
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
        return view('livewire.wishlist-show')->layout('layouts.app');
    }
}
