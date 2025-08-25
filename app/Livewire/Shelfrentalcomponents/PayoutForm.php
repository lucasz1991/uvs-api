<?php

namespace App\Livewire\Shelfrentalcomponents;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\ShelfRental;
use App\Models\Payout;
use App\Models\Sale; 
use App\Models\AdminTask; 
use App\Livewire\Dashboard; 

class PayoutForm extends Component
{
    public $showModal = false;
    public $shelfRentalId;

    public $accountHolder; 
    public $payoutMethod;
    public $iban;
    public $bic;
    public $paypalEmail;
    public $amount; 

    protected $rules = [
        'accountHolder' => 'required|string|max:255',
        'payoutMethod' => 'required|in:bank_transfer,paypal',
		'iban' => [
			'nullable',
			'required_if:payoutMethod,bank_transfer',
			'regex:/^DE\d{20}$/'
		],
		'bic' => [
            'nullable',
            'required_if:payoutMethod,bank_transfer',
            'regex:/^[A-Z]{6}[A-Z0-9]{2}([A-Z0-9]{3})?$/'
        ],
        'paypalEmail' => 'nullable|required_if:payoutMethod,paypal|email',
    ];
    
    protected $messages = [
        'accountHolder.required' => 'Bitte geben Sie den Namen des Kontoinhabers an.',
        'accountHolder.string' => 'Der Name des Kontoinhabers muss ein gültiger Text sein.',
        'accountHolder.max' => 'Der Name darf maximal 255 Zeichen lang sein.',
    
        'payoutMethod.required' => 'Bitte wählen Sie eine Auszahlungsmethode.',
        'payoutMethod.in' => 'Die gewählte Auszahlungsmethode ist ungültig.',
    
        'iban.required_if' => 'Bitte geben Sie eine IBAN an, wenn Sie Banküberweisung als Methode wählen.',
		'iban.regex' => 'Bitte geben Sie eine gültige deutsche IBAN ein (beginnend mit DE, gefolgt von 20 Ziffern).',
		
        'bic.required_if' => 'Bitte geben Sie eine BIC an, wenn Sie Banküberweisung als Methode wählen.',
        'bic.regex' => 'Bitte geben Sie eine gültige BIC ein (8 oder 11 Zeichen, nur Großbuchstaben und Zahlen).',
    
        'paypalEmail.required_if' => 'Bitte geben Sie eine PayPal-E-Mail-Adresse an, wenn Sie PayPal als Methode wählen.',
        'paypalEmail.email' => 'Bitte geben Sie eine gültige E-Mail-Adresse für PayPal an.',
    ];

    public function mount($shelfRentalId)
    {
        $this->shelfRentalId = $shelfRentalId;

        // Automatische Berechnung des Gesamtbetrags aus Verkäufen
        $this->calculatePayoutAmount();

        // Falls bereits gespeicherte Payout-Daten existieren, vorausfüllen
        $latestPayout = Payout::where('customer_id', Auth::id())
            ->where('shelf_rental_id', $shelfRentalId)
            ->latest()
            ->first();

        if ($latestPayout) {
            $details = $latestPayout->payout_details;
            $this->accountHolder = $details['account_holder'] ?? null;
            $this->payoutMethod = $latestPayout->payout_method;
            $this->iban = $details['iban'] ?? null;
            $this->bic = $details['bic'] ?? null;
            $this->paypalEmail = $details['paypal_email'] ?? null;
        }
    }

    /**
     * Berechnet den gesamten Betrag für die Auszahlung basierend auf den Verkäufen.
     */
    public function calculatePayoutAmount()
    {
        $this->amount = Sale::where('rental_id', $this->shelfRentalId)
            ->where('status', 1) // Nur Verkäufe mit Status 1
            ->sum('net_sale_price');
    }

    public function openModal()
    {
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

public function submitPayout()
{
    $this->validate();

    // ShelfRental-Status auf 8 setzen
    $shelfRental = ShelfRental::find($this->shelfRentalId);
    if ($shelfRental) {

        // Auszahlung Details je nach Methode vorbereiten
        $payoutDetails = [
            'account_holder' => $this->accountHolder,
        ];

		if ($this->payoutMethod === 'bank_transfer') {
			$payoutDetails['iban'] = $this->iban;
			$payoutDetails['bic'] = $this->bic;
			$payoutDetails['paypal_email'] = null;
		} elseif ($this->payoutMethod === 'paypal') {
			$payoutDetails['iban'] = null;
			$payoutDetails['bic'] = null;
			$payoutDetails['paypal_email'] = $this->paypalEmail;
		}

        // Payout erstellen
        Payout::create([
            'customer_id' => $shelfRental->customer->id,
            'shelf_rental_id' => $this->shelfRentalId,
            'amount' => $this->amount,
            'status' => false, // Standardmäßig "offen"
            'payout_details' => $payoutDetails,
        ]);

        // ShelfRental-Status aktualisieren
        $shelfRental->status = 8;
        $shelfRental->save();
    }

    // Verkäufe als "Ausgezahlt" markieren
    Sale::where('rental_id', $this->shelfRentalId)
        ->where('status', 1)
        ->update(['status' => 2]); 

    // Admin-Task erstellen
    AdminTask::create([
        'task_type' => 'Auszahlung',
        'description' => "Auszahlung für Regalbuchung #{$this->shelfRentalId} in Höhe von " . number_format($this->amount, 2, ',', '.') . " € angefordert",
        'status' => 0, // 0 = offen
        'assigned_to' => null, // Noch kein Admin zugewiesen
        'shelf_rental_id' => $this->shelfRentalId,
    ]);

    // Dashboard aktualisieren
    $this->dispatch('refreshParent')->to(Dashboard::class);

    // Erfolgsnachricht anzeigen
    $this->dispatch('showAlert', 'Auszahlungsantrag erfolgreich eingereicht.', 'success');

    $this->closeModal();
}


    public function render()
    {
        return view('livewire.shelfrentalcomponents.payout-form');
    }
}
