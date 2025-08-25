<?php

namespace App\Livewire\Admin\RatingStructure;

use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        return view('livewire.admin.rating-structure.index')->layout('layouts.master');
    }
}
