<?php

namespace App\Livewire;

use Livewire\Component;

class AdminNavigation extends Component
{
    public $unreadMessagesCount;
    
    protected $listeners = [
        'refreshComponent' => '$refresh',
    ];

    public function render()
    {
        return view('livewire.admin-navigation');
    }
}
