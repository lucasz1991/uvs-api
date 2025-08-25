<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;


class LikedProducts extends Component
{

    public $productId;
    public $likedProducts;

    public function mount()
    {
        $this->fetchLikedProducts();
    }

    public function fetchLikedProducts()
    {
        $this->likedProducts = Auth::user()->likedProducts()->with('category')->get();
    }

    public function removeFromLiked($productId)
    {
        $user = Auth::user();
        $user->likedProducts()->detach($productId);

        $this->fetchLikedProducts(); // Liste aktualisieren
        session()->flash('success', 'Produkt erfolgreich aus der Wunschliste entfernt.');
        // Event auslösen
        $this->dispatch('likedProductsUpdated');
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

        $this->dispatch('likedProductsUpdated');
    }

    public function render()
    {
        return view('livewire.liked-products')->layout('layouts.app');
    }
}
