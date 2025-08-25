<?php

namespace App\Livewire;

use Livewire\Component;


class WebContentManager extends Component
{
    public function render()
    {
        return view('livewire.web-content-manager')->layout('layouts.master');
    }
}
