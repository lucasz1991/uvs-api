<?php

namespace App\Livewire;

use Livewire\Component;

class AdminNavigationTopBar extends Component
{   
    public $unreadMessagesCount;
    protected $listeners = [
        'refreshComponent' => '$refresh',
    ];

    public function mount()
    {
        $this->unreadMessagesCount = auth()->user()->receivedUnreadMessages;
    }

    public function render()
    {
        return view('livewire.admin-navigation-top-bar');
    }
}
