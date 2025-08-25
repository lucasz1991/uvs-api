<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product;

class AdminProductList extends Component
{
    use WithPagination;

    public $sortBy = 'id'; // Standard-Sortierspalte
    public $sortDirection = 'asc'; // Standard-Sortierorder
    public $search = ''; // Suchfeld


    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function sortByField($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function render()
    {
        $allProducts = Product::all();

        $products = Product::query()
        ->where(function ($query) {
            $query->where('name', 'like', '%' . $this->search . '%')
                ->orWhere('id', 'like', '%' . $this->search . '%');
        })
        ->orderBy($this->sortBy, $this->sortDirection)
        ->paginate(10);

        return view('livewire.admin-product-list', [
            'products' => $products,
            'allProducts' => $allProducts,
        ])->layout('layouts.master');
    }
}
