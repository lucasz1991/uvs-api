<?php

namespace App\Livewire\Admin\Charts;

use Livewire\Component;
use Carbon\Carbon;
use App\Models\Sale;

class MonthlySales extends Component
{
    public $data = [];       // Summierte Verkäufe (€) für jeden Monat
    public $months = [];     // X-Werte (Monate)
    public string $chartId = 'monthlySalesChart';
    public $height;

    public function mount($height)
    {
        $this->height = $height;
        $this->chartId = 'monthlySalesChart-' . uniqid(); // Eindeutige ID für das Chart
        $this->initializeChartData();
    }

    public function initializeChartData()
    {
        // Berechne die letzten 12 Monate
        $currentMonth = Carbon::now()->startOfMonth();
        for ($i = 11; $i >= 0; $i--) {
            $month = $currentMonth->copy()->subMonths($i); // Monat zurückrechnen
            $this->months[] = $month->format('M'); // Monat und Jahr für X-Achse
            $this->data[] = $this->getMonthlySalesTotal($month);
        }
    }

    private function getMonthlySalesTotal(Carbon $month)
    {
        $startOfMonth = $month->copy()->startOfMonth(); 
        $endOfMonth = $month->copy()->endOfMonth();

        return Sale::whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->sum('sale_price');
    }

    public function render()
    {
        return view('livewire.admin.charts.monthly-sales', [
            'data' => $this->data,
            'months' => $this->months,
            'chartId' => $this->chartId,
            'height' => $this->height,
        ]);
    }
}
