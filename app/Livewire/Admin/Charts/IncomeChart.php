<?php

namespace App\Livewire\Admin\Charts;

use Livewire\Component;
use Carbon\Carbon;
use App\Models\ShelfRental; // Für Mieten
use App\Models\Sale;        // Für Verkäufe

class IncomeChart extends Component
{
    public $rentalData = []; // Einkünfte aus Mieten (€)
    public $salesData = [];  // Einkünfte aus Verkäufen (€)
    public $months = [];     // Abgekürzte Monatsnamen für X-Achse
    public string $chartId = 'incomeChart';
    public $height;

    public function mount($height)
    {
        $this->height = $height;
        $this->chartId = 'incomeChart-' . uniqid(); // Eindeutige ID für das Chart
        $this->initializeChartData();
    }

    public function initializeChartData()
    {
        // Berechne die letzten 12 Monate
        $currentMonth = Carbon::now()->startOfMonth();
        for ($i = 11; $i >= 0; $i--) {
            $month = $currentMonth->copy()->subMonths($i); // Monat zurückrechnen
            $this->months[] = $month->format('M'); // Abgekürzte Monatsnamen (z. B. "Jan", "Feb")
            $this->rentalData[] = $this->getMonthlyRentalIncome($month); // Einkünfte aus Mieten
            $this->salesData[] = $this->getMonthlySalesIncome($month);  // Einkünfte aus Verkäufen (16 %)
        }
    }

    private function getMonthlyRentalIncome(Carbon $month)
    {
        $startOfMonth = $month->copy()->startOfMonth(); 
        $endOfMonth = $month->copy()->endOfMonth();
        $sum = ShelfRental::whereBetween('created_at', [$startOfMonth, $endOfMonth])->sum('total_price');
        return round($sum, 2); // Summe der Mieten
    }

    private function getMonthlySalesIncome(Carbon $month)
    {
        $startOfMonth = $month->copy()->startOfMonth(); 
        $endOfMonth = $month->copy()->endOfMonth();
    
        $totalSales = Sale::whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->sum('sale_price'); // Summe der Verkaufspreise
    
        $income = $totalSales * 0.16; // 16 % der Verkäufe
    
        return round($income, 2); // Kaufmännisch runden auf 2 Nachkommastellen
    }

    public function render()
    {
        return view('livewire.admin.charts.income-chart', [
            'rentalData' => $this->rentalData,
            'salesData' => $this->salesData,
            'months' => $this->months,
            'chartId' => $this->chartId,
            'height' => $this->height,
        ]);
    }
}
