<?php

namespace App\Livewire\Admin\Charts;

use Livewire\Component;
use Carbon\Carbon;
use App\Models\ShelfRental; // Modell für Mieten

class ShelfRentals extends Component
{
    public $data = [];       // Y-Werte für monatliche Mieten
    public $months = [];     // X-Werte (Monate)
    public string $chartId = 'shelfRentalsChart';
    public $height;

    public function mount($height)
    {
        $this->chartId = 'shelfRentalsChart-' . uniqid(); // Eindeutige ID für das Chart
        $this->initializeChartData();
    }

    public function initializeChartData()
    {
    // Berechne die letzten 12 Monate
    $currentMonth = Carbon::now()->startOfMonth();
    for ($i = 11; $i >= 0; $i--) {
        $month = $currentMonth->copy()->subMonths($i);
        \Log::info('Processing month: ' . $month->format('Y-m'));

        $this->months[] = $month->format('M');
        $this->data[] = $this->getMonthlyRentals($month);
    }
    }

    private function getMonthlyRentals(Carbon $month)
    {
        $startOfMonth = $month->copy()->startOfMonth(); // Beginn des Monats
        $endOfMonth = $month->copy()->endOfMonth(); // Ende des Monats
    
        \Log::info('Querying rentals between: ' . $startOfMonth . ' and ' . $endOfMonth);
    
        $count = ShelfRental::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();
    
        \Log::info('Count for ' . $month->format('Y-m') . ': ' . $count);
    
        return $count;
    }

    public function render()
    {
        return view('livewire.admin.charts.shelf-rentals', [
            'data' => $this->data,
            'months' => $this->months,
            'chartId' => $this->chartId,
            'height' => $this->height,
        ]);
    }
}
