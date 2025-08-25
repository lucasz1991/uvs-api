<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Sale;
use App\Models\ShelfRental;

class Dashboard extends Component
{
    use WithPagination;

    public $userData;
    public $shelfRentalsCount;
    public $productsCount;
    public $salesCount;
    public $deposit;

    protected $listeners = ['refreshParent' => '$refresh'];

    
    public function render()
    {
        // Benutzerinformationen laden
        $this->userData = Auth::user();

        // Produkte mit Status 2 laden und zählen
        $products = Product::where('customer_id', $this->userData->customer->id)
        ->where('status', 2) // Nur Produkte mit Status 2
        ->get();
        $this->productsCount = $products->count();

        // Verkäufe für diese Produkte zählen
        $sales = $this->userData->customer->sales;
        $this->salesCount = $sales->count();
            // Finde Regalbuchungen, die nicht abgerechnet wurden (Status != 5)
            $activeShelfRentals = ShelfRental::where('customer_id', $this->userData->customer->id)
            ->where('status', '!=', 7)
            ->pluck('id'); // Hole die IDs der Regalbuchungen

        // Filtere Verkäufe, die zu aktiven Regalbuchungen gehören
        $filteredSales = $sales->whereIn('rental_id', $activeShelfRentals);

        // Addiere die Preise der gefilterten Verkäufe
        $this->deposit = $filteredSales->sum('net_sale_price');

        // Aktive Buchungen zählen (Status 1 oder 2)
        $this->shelfRentalsCount = $activeShelfRentals->count();



        // Buchungen mit Pagination laden
        $shelfRentals = ShelfRental::where('customer_id', $this->userData->customer->id)
            ->orderBy('rental_start', 'desc')
            ->paginate(3);

        return view('livewire.dashboard', [
            'shelfRentals' => $shelfRentals,
            'products' => $products,
            'sales' => $sales,
        ])->layout("layouts.app");
    }
}
