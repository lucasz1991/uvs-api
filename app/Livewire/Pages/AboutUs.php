<?php

namespace App\Livewire\Pages;

use Livewire\Component;

class AboutUs extends Component
{
    public function render()
    {
        return view('livewire.pages.about-us')
            ->layout('layouts.app'); 
    }
}
