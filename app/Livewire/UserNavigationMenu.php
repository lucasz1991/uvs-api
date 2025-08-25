<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Message;

class UserNavigationMenu extends Component
{

    public $likedCount;
    
    public $currentUrl;
    public $receivedMessages;
    public $unreadMessagesCount;
    public $likedProducts;


    public $message;

    protected $listeners = ['likedProductsUpdated' => 'updateLikedProducts','refreshComponent' => '$refresh',];

    public function mount()
    {
        if (auth()->check()) {
            $this->updateLikedProducts();
            $this->receivedMessages = auth()->user()
                ->receivedMessages
                ->sort(function ($a, $b) {
                    // Priorität: Status (ungelesen zuerst)
                    if ($a->status !== $b->status) {
                        return $a->status <=> $b->status; // Ungelesene zuerst
                    }
                    // Zweite Priorität: Erstellungsdatum (neueste zuerst)
                    return $b->created_at <=> $a->created_at;
                })
                ->take(3);
            $this->unreadMessagesCount= auth()->user()->receivedUnreadMessages->count();
            $this->likedProducts= auth()->user()->likedProducts;

        } else {
            $this->likedCount = 0; // Wenn der Benutzer nicht angemeldet ist, setze die geliketen Produkte auf 0
        }
        $this->currentUrl = url()->current();
    }

    public function setMessageStatus($messageId)
    {
        $this->message = auth()->user()->receivedMessages->firstWhere('id', $messageId);
        $this->message->update([
            'status' => 2, 
        ]);
        $this->message->save();

        $this->receivedMessages = auth()->user()
                ->receivedMessages
                ->sort(function ($a, $b) {
                    // Priorität: Status (ungelesen zuerst)
                    if ($a->status !== $b->status) {
                        return $a->status <=> $b->status; // Ungelesene zuerst
                    }
                    // Zweite Priorität: Erstellungsdatum (neueste zuerst)
                    return $b->created_at <=> $a->created_at;
                })
                ->take(3);
        $this->unreadMessagesCount= auth()->user()->receivedUnreadMessages->count();
        
        
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

    public function updateLikedProducts()
    {
        // Anzahl der geliketen Produkte abrufen
        $this->likedCount = auth()->user()->likedProducts()->count();
        $this->likedProducts= auth()->user()->likedProducts->take(6);
    }

    public function render()
    {
        return view('livewire.user-navigation-menu');
    }
}
