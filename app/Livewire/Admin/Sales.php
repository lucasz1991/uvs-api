<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Sale;

class Sales extends Component
{
    use WithPagination;
    public function render()
    {
        $sales = Sale::with(['product', 'customer', 'rental'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('livewire.admin.sales', [
            'sales' => $sales,
        ])->layout('layouts.master');
    }
}
