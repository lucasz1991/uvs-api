<?php

namespace App\Livewire\Shelfrentalcomponents;

use Livewire\Component;
use App\Models\Payout;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;


class PayoutDetails extends Component
{
    public $shelfRentalId;
    public $showModal = false;
    public $payout;

    public function mount($shelfRentalId)
    {
        $this->shelfRentalId = $shelfRentalId;
        $this->loadPayoutDetails();
    }

    public function loadPayoutDetails()
    {
        $this->payout = Payout::where('shelf_rental_id', $this->shelfRentalId)->latest()->first();
    }

    public function openModal()
    {
        $this->loadPayoutDetails(); // Sicherstellen, dass aktuelle Daten geladen werden
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function downloadPayoutPdf()
    {
        if (!$this->payout) {
            return;
        }
    
        $fileName = "payout_{$this->payout->id}_" . Carbon::now()->format('Y_m_d') . ".pdf";
        
        // PDF-Daten vorbereiten
        $data = [
            'payout' => $this->payout,
            'user' => $this->payout->user,
        ];
    
        // Dompdf Optionen setzen, um externe Ressourcen (z. B. Logos) zu laden
        $options = new \Dompdf\Options();
        $options->set('isRemoteEnabled', true);
    
        $pdf = new \Dompdf\Dompdf($options);
        $pdf->loadHtml(view('pdf.bill.payout-receipt', $data)->render());
        $pdf->setPaper('A4', 'portrait');
        $pdf->render();
    
        $filePath = "private/payouts/{$fileName}";
    
        Storage::disk('local')->put($filePath, $pdf->output());
    
        return response()->download(storage_path("app/{$filePath}"));
    }

    public function render()
    {
        return view('livewire.shelfrentalcomponents.payout-details');
    }
}
