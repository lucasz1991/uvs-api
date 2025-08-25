<?php

namespace App\Livewire\Customer;

use Livewire\Component;
use Carbon\Carbon;
use App\Models\ShelfRental;
use App\Models\ShelfBlockedDate;
use App\Models\Setting;
use App\Jobs\UpdateBlockedDatesJob;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ShelfBookingConfirmation;
use App\Notifications\AdminBookingCreatedNotification;

class ExtendShelfRental extends Component
{
    public $rentalId;
    public $shelfRental;
    public $newEndDate;
    public $availableExtensions = [7, 14, 21];
    public $validExtensions = [];
    public $publicHolidays = [];
    public $authorizationID = null;
    public $finalPrice = 0;
    public $period;
    public $terms;
    public $apiSettings;



    public function mount($rentalId)
    {
        $this->rentalId = $rentalId;
        $this->loadHolidays();
        $this->loadRental();
        $this->checkAvailableExtensions();
        $this->apiSettings = Setting::where('type', 'api')
        ->pluck('value', 'key')
        ->mapWithKeys(fn ($value, $key) => [$key => $value])
        ->toArray();
    }

    /**
     * Feiertage aus der Datenbank laden
     */
    public function loadHolidays()
    {
        $this->publicHolidays = Setting::where('type', 'holiday')
            ->pluck('value')
            ->toArray();
    }

    /**
     * Lädt die Regalmiete und setzt die erste Verlängerungsoption
     */
    public function loadRental()
    {
        $this->shelfRental = ShelfRental::where('id', $this->rentalId)
            ->where('customer_id', Auth::user()->customer->id)
            ->firstOrFail();
    }

    /**
     * Berechnet ein gültiges Enddatum ohne Sonntage & Feiertage
     */
    private function calculateValidEndDate($daysToAdd)
    {
        $date = Carbon::parse($this->shelfRental->rental_end);
        $addedDays = 0;
        while ($addedDays < $daysToAdd) {
            $date->addDay();
            if (!$this->isBlockedOrHoliday($date)) {
                $addedDays++;
            }
        }
        return $date->format('Y-m-d');
    }

    /**
     * Prüft verfügbare Verlängerungen ohne Blockierungen
     */
    public function checkAvailableExtensions()
    {
        $this->validExtensions = [];
        foreach ($this->availableExtensions as $days) {
            $startDate = Carbon::parse($this->shelfRental->rental_end)->addDay();
            $newEndDate = $this->calculateValidEndDate($days);
            $isBlocked = ShelfBlockedDate::where('shelf_id', $this->shelfRental->shelf_id)
                ->whereBetween('blocked_date', [$startDate, $newEndDate])
                ->exists();
            if (!$isBlocked) {
                $this->validExtensions[] = $days;
            }
        }
    }

    /**
     * Prüft, ob ein Datum ein Sonntag oder Feiertag ist
     */
    private function isBlockedOrHoliday(Carbon $date)
    {
        return $date->isSunday() || in_array($date->format('Y-m-d'), $this->publicHolidays);
    }

    public function setPeriod($days, $newEndDate)
    {
        if (!in_array($days, $this->availableExtensions)) {
            $this->dispatch('showAlert', 'Die gewählte Verlängerung ist nicht verfügbar.', 'error');
            return;
        }
    
        $this->period = $days;
        $this->newEndDate = $newEndDate;
    
        // Preisberechnung
        $priceMapping = [
            7 => 26,
            14 => 46,
            21 => 66,
        ];
    
        $this->finalPrice = $priceMapping[$days] ?? 0;
    
        // Benutzerfreundlichere Nachricht
        $this->dispatch('showAlert', "Du hast eine Verlängerung um {$days} Tage gewählt. Der Preis beträgt {$this->finalPrice} €. Bitte schließe die Zahlung ab, um die Verlängerung zu bestätigen.", 'success');
    }



    public function extendRental()
    {
        if (!$this->newEndDate) {
            $this->dispatch('showAlert', 'Bitte wähle ein neues Enddatum.', 'error');
            return;
        }

        if (!$this->authorizationID) {
            $this->dispatch('showAlert', 'Bitte autorisiere zuerst die Zahlung über PayPal.', 'error');
            return;
        }

        // Mietverlängerung durchführen
        $newEndDate = Carbon::parse($this->newEndDate);
        $this->shelfRental->update(['rental_end' => $newEndDate]);
        UpdateBlockedDatesJob::dispatch($this->shelfRental);
        $productAddUrl = url('/shelf-rental/'.$shelfRental->id); 
        $bookingDetailsUrl = url('/admin/shelf-rentals/' . $shelfRental->id); 
        $adminEmail = Setting::where('key', 'admin_email')->pluck('value')->first();
        Notification::route('mail', $adminEmail)->notify(new AdminBookingCreatedNotification( $shelfRental, $bookingDetailsUrl));

        try {
               $user->notify(new ShelfBookingConfirmation($user, $shelfRental, $productAddUrl));
           } catch (\Swift_TransportException $e) {
               session()->flash('error', 'Die Bestätigungs-E-Mail konnte nicht gesendet werden. Bitte überprüfen Sie Ihre E-Mail-Adresse oder versuchen Sie es später erneut.');
           } catch (\Exception $e) {        
               session()->flash('error', 'Ein Fehler ist aufgetreten. Bitte versuchen Sie es später erneut.');
           }
        $message = 
            'Hallo ' . $user->name . '!<br>' .
            'Deine Regalmiete wurde erfolgreich verlängert! Hier sind die Details:<br><br>' .
            '<table>
                <tr><td><strong>Regalnummer:</strong></td><td>' . $shelfRental->shelf->floor_number . '</td></tr>
                <tr><td><strong>Neuer Mietzeitraum:</strong></td><td>' . 
                Carbon::parse($shelfRental->rental_start)->format('d.m.Y') . ' bis ' . 
                Carbon::parse($shelfRental->rental_end)->format('d.m.Y') . '</td></tr>
                <tr><td><strong>Verlängerungsdauer:</strong></td><td>' . $this->period . ' Tage</td></tr>
                <tr><td><strong>Gesamtpreis der Verlängerung:</strong></td><td>' . 
                number_format($this->finalPrice, 2, ',', '.') . ' €</td></tr>
            </table><br><br>' .
            'Falls du Fragen hast, kannst du uns jederzeit kontaktieren.<br><br>' .
            'Mit freundlichen Grüßen,<br>' .
            'Dein MiniFinds-Team';
        
        // Nachricht an den Benutzer senden
        $user->sendMessage($user->id, 'Deine Regalmiete bei MiniFinds wurde verlängert!', $message);

        // Erfolgsmeldung für den Benutzer
        $this->dispatch('showAlert', 'Die Miete wurde erfolgreich verlängert!', 'success');

        // Aktualisierte Regalmiete erneut laden
        $this->loadRental();
        $this->checkAvailableExtensions();
        Log::error('Eine Miete wurde erfolgreich verlängert!');
    }


    public function finalizePayment()
    {
        $this->validate([
            'terms' => 'accepted',
        ], [
            'terms.accepted' => 'Bitte bestätigen.',
        ]);
        try {
            if (empty($this->authorizationID)) {
                throw new \Exception('Authorization ID fehlt.');
            }
            // Überprüfung, ob das Regal für den gewünschten Zeitraum gesperrt ist
            $newEndDate = Carbon::parse($this->newEndDate);
            $isBlocked = ShelfBlockedDate::where('shelf_id', $this->shelfRental->shelf_id)
                ->whereBetween('blocked_date', [$this->shelfRental->rental_end, $newEndDate])
                ->exists();
            if ($isBlocked) {
                throw new \Exception('Das Regal ist im gewählten Zeitraum bereits belegt. Zahlung nicht möglich.');
            }
            // PayPal-Zahlung über PayPalController verarbeiten
            $paypalController = app()->make(PayPalController::class);
            $response = $paypalController->captureAuthorize($this->authorizationID);
            $responseData = $response->getData(true);
            // Überprüfung, ob die Zahlung erfolgreich war
            if (!isset($responseData['status']) || $responseData['status'] !== 'COMPLETED') {
                throw new \Exception('PayPal-Zahlung konnte nicht abgeschlossen werden.');
            }
            // Zahlung erfolgreich -> Mietverlängerung durchführen
            $this->extendRental();
        } catch (\Exception $e) {
            Log::error('Fehler beim Abschließen der Zahlung:', ['error' => $e->getMessage()]);
            $this->dispatch('showAlert', 'Fehler beim Abschließen der Zahlung', 'error');
        }
    }
    

    public function render()
    {
        return view('livewire.customer.extend-shelf-rental', [
            'validExtensions' => $this->validExtensions,
            'publicHolidays' => $this->publicHolidays
        ])->layout('layouts.app');
    }
}
