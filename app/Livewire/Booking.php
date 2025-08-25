<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Storage;
use App\Models\ShelfRental;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Customer;
use App\Models\RetailSpace;
use App\Models\Shelve;
use App\Models\BlockedDate;
use App\Models\ShelfBlockedDate;
use App\Models\Invoice;
use App\Models\Setting;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ShelfBookingConfirmation;
use App\Notifications\AdminBookingCreatedNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Notifications\ResetPassword;
use App\Notifications\SetPasswordNotification;
use App\Jobs\UpdateBlockedDatesJob;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\PayPalController;


use Livewire\Attributes\On;



class Booking extends Component
{
    public $newBooking;
    public $retailSpaces;
    public $progress;
    public $showStep;
    public $period;
    public $validStartDate;
    public $validEndDate;
    public $startDate;
    public $endDate;
    public $retailSpace;
    public $retailSpaceLayout;
    public $selectedShelve;
    public $blockedDates;
    public $blockedShelves;

    public $customer;
    public $isExistingCustomer;
    public $wantLogin;

    public $apiSettings;

    public $formStep;

    public $email, $password, $password_confirmation;
    public $first_name, $last_name, $username, $phone_number, $street, $city, $state, $postal_code, $country ,$terms, $paymentMethod;

    public $totalPrice = 0;
    public $discountedPrice = 0;
    public $isDiscounted = false;
    public $finalPrice = 0;

    public $authorizationID;


    protected $listeners = [
        'refreshComponent' => '$refresh',
        'saveDates' => 'saveDates',
    ];

    public function mount()
    {
        $this->isExistingCustomer = false;
        $this->progress = 0;
        $this->showStep = 1;
        $this->retailSpace = RetailSpace::find(1);
        $this->retailSpaceLayout = $this->retailSpace->layout;
        $this->wantLogin = false;
        if (Auth::check()) {
            $this->customer = Auth::user();
            $this->isExistingCustomer = true;
        }
        $this->formStep = 1;
        // Lade die API-Einstellungen
        $this->apiSettings = Setting::where('type', 'api')
        ->pluck('value', 'key')
        ->mapWithKeys(fn ($value, $key) => [$key => $value])
        ->toArray();
    }




    

    public function checkCustomerEmail()
    {
        $this->isExistingCustomer = User::where('email', $this->customer['email'])->exists();
    }
    
    public function loginCustomer()
    {
        $credentials = [
            'email' => $this->customer['email'],
            'password' => $this->customer['password'],
        ];
    
        if (Auth::attempt($credentials)) {
            // Login erfolgreich
            session()->flash('message', 'Erfolgreich eingeloggt.');
        } else {
            $this->addError('customer.password', 'Falsches Passwort.');
        }
    }



    public function setPeriod($p)
    {   
        switch ($p) {
            case 1:
                $this->period = 7;
                break;
                
                case 2:
                    $this->period = 14;
                    break;
                    
                    case 3:
                        $this->period = 21;
                        break;
                        
                        default:
                        $this->period = 7;
                        break;
        }
        $this->progress = 1;
        $this->blockedDates = $this->getBlockedDates($this->retailSpace->id, $this->period);
        $this->showStep = 2;
        $this->startDate = null;
        $this->endDate = null;
        $this->selectedShelve= null;
        $this->dispatch('showAlert', "Du hast <strong>$this->period Tage</strong> als Mietdauer gewählt.<br>Jetzt bitte dein gewünschtes Datum wählen.", 'success');
    }

    #[On('setData')]
    public function setData($startDate , $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->blockedShelves = $this->getBlockedShelves($this->retailSpace->id, $startDate, $endDate);
        $this->isDateSelected = true;
        $this->selectedShelve= null;
        $this->progress = 2; 
        $this->showStep = 3; 
        // Start- und Enddatum formatieren
        $formattedStartDate = Carbon::parse($this->startDate)->format('d.m.Y'); // z.B. 01.07.2024
        $formattedEndDate = Carbon::parse($this->endDate)->format('d.m.Y');

        $this->dispatch('showAlert', "Auswahl gespeichert:<strong> $formattedStartDate </strong> bis <strong>$formattedEndDate</strong>.<br>Jetzt bitte dein gewünschtes Regal wählen.", 'success');
    }


    public function setShelve($shelve){
        $this->selectedShelve = Shelve::find($shelve);
        $this->progress = 3;
        $this->showStep = 4;
        $this->calculatePrice();
        $this->dispatch('showAlert', "Herzlichen Glückwunsch! Die Auswahl des Regals wurde erfolgreich gespeichert: <strong>" . $this->selectedShelve->floor_number . "</strong>. Jetzt fehlt nur noch der letzte Schritt!", 'success');
    }

    public function calculatePrice()
    {
        // Preis basierend auf der Periode bestimmen
        $pricePerPeriod = match ($this->period) {
            7 => 26,
            14 => 46,
            21 => 66,
            default => 0,
        };
    
        // Standardwerte setzen
        $this->totalPrice = $pricePerPeriod;
        $this->isDiscounted = false;
        $this->discountedPrice = 0;
        $this->finalPrice = $this->totalPrice;
    
        // Aktuelles Datum abrufen
        $currentDate = \Carbon\Carbon::now();
    
        // Startdatum der Miete abrufen
        $rentalStart = isset($this->startDate) ? \Carbon\Carbon::parse($this->startDate) : null;
    
        // Rabattzeitraum definieren
        $discountStart = \Carbon\Carbon::create(2025, 2, 14, 0, 0, 0);
        $discountEnd = \Carbon\Carbon::create(2025, 2, 28, 23, 59, 59);
    
        // Prüfen, ob Rabatt für 14 Tage Periode gewährt wird 
        // UND sowohl das aktuelle Datum als auch rental_start in diesem Zeitraum liegen
        if (
            $this->period == 14 &&
            $currentDate->between($discountStart, $discountEnd) &&
            $rentalStart &&
            $rentalStart->between($discountStart, $discountEnd)
        ) {
            $this->discountedPrice = $this->totalPrice * 0.86; // 14% Rabatt
            $this->isDiscounted = true;
            $this->finalPrice = $this->discountedPrice;
        }
    }


    public function setShowStep($i){
        $step = $this->progress+1;
        if($step != 1){
            if($i <= $step){
                $this->showStep = $i;
            }
        }
    }

    public function getBlockedDates($retailPlaceId, $period)
    {
        // Alle blockierten Tage für die Verkaufsfläche im angegebenen Zeitraum aus der Tabelle abfragen
        $blockedDates = BlockedDate::where('retail_space_id', $retailPlaceId)
            ->where('blocked_period',  $period)
            ->pluck('blocked_date'); // Nur die blockierten Tage (Datum)
    
        return $blockedDates;
    }

    public function getBlockedShelves($retailPlaceId, $startDate, $endDate)
    {
        // Alle Regale abrufen, deren Blockiertage im angegebenen Zeitraum liegen
        $blockedShelves = ShelfBlockedDate::where('retail_space_id', $retailPlaceId)
            ->whereBetween('blocked_date', [$startDate, $endDate])
            ->pluck('shelf_id') // Pluck nur die shelf_id
            ->unique() // Doppelte IDs entfernen
            ->toArray(); // Umwandeln in ein Array
      
        return $blockedShelves;
    }


    public function checkFormStep1()
    {
        $this->validate([
            'email' => 'required|email|unique:users,email',
            'first_name' => 'required|string|max:255|regex:/^[\p{L}\s\-]+$/u',
            'last_name' => 'required|string|max:255|regex:/^[\p{L}\s\-]+$/u',
        ], [
            'email.required' => 'Bitte geben Sie eine E-Mail-Adresse ein.',
            'email.email' => 'Bitte geben Sie eine gültige E-Mail-Adresse ein.',
            'email.unique' => 'Diese E-Mail-Adresse ist bereits registriert. Bitte loggen Sie sich zuerst ein.',
            'first_name.required' => 'Bitte geben Sie Ihren Vornamen ein.',
            'first_name.string' => 'Der Vorname muss ein gültiger Text sein.',
            'first_name.max' => 'Der Vorname darf nicht länger als 255 Zeichen sein.',
            'first_name.regex' => 'Der Vorname darf nur Buchstaben, Leerzeichen und Bindestriche enthalten.',
            'last_name.required' => 'Bitte geben Sie Ihren Nachnamen ein.',
            'last_name.string' => 'Der Nachname muss ein gültiger Text sein.',
            'last_name.max' => 'Der Nachname darf nicht länger als 255 Zeichen sein.',
            'last_name.regex' => 'Der Nachname darf nur Buchstaben, Leerzeichen und Bindestriche enthalten.',
        ]);
    
        $this->formStep = 2;
    }

    public function checkFormStep2()
    {
        $this->validate([
            'street' => 'required|string|max:255|regex:/^[\p{L}\s\.\-]+ \d+[a-zA-Z]?$/u',
            'city' => 'required|string|max:255|regex:/^[\p{L}\s\-]+$/u',
            'postal_code' => 'required|numeric|digits:5',
            'state' => 'required|string|max:255|regex:/^[\p{L}\s\-]+$/u',
        ], [
            'street.required' => 'Bitte geben Sie Ihre Straße und Hausnummer ein.',
            'street.string' => 'Die Straße muss ein gültiger Text sein.',
            'street.max' => 'Die Straße darf nicht länger als 255 Zeichen sein.',
            'street.regex' => 'Bitte geben Sie eine gültige Straße im Format „Straßenname Hausnummer“ ein.',
            'city.required' => 'Bitte geben Sie Ihre Stadt ein.',
            'city.string' => 'Die Stadt muss ein gültiger Text sein.',
            'city.regex' => 'Der Nachname darf nur Buchstaben, Leerzeichen und Bindestriche enthalten.',
            'city.max' => 'Die Stadt darf nicht länger als 255 Zeichen sein.',
            'postal_code.required' => 'Bitte geben Sie Ihre Postleitzahl ein.',
            'postal_code.numeric' => 'Die Postleitzahl muss eine Zahl sein.',
            'postal_code.digits' => 'Die Postleitzahl muss genau 5 Ziffern haben.',
            'state.required' => 'Bitte geben Sie Ihr Bundesland ein.',
            'state.string' => 'Das Bundesland muss ein gültiger Text sein.',
            'state.max' => 'Das Bundesland darf nicht länger als 255 Zeichen sein.',
            'state.regex' => 'Der Nachname darf nur Buchstaben, Leerzeichen und Bindestriche enthalten.',
        ]);
    
        $this->formStep = 3;
    }



    public function finalizePayment()
    {

        $this->validate([
            'paymentMethod' => '',
            'terms' => 'accepted',
        ], [
            'terms.accepted' => 'Bitte bestätigen.',
        ]);
        try {
            if (empty($this->authorizationID)) {
                throw new \Exception('Authorization ID fehlt.');
            }
            $paypalController = app()->make(PayPalController::class);
            $response = $paypalController->captureAuthorize($this->authorizationID);
            $responseData = $response->getData(true);
            $this->completeBooking();
        } catch (\Exception $e) {
            \Log::error('Fehler beim Abschließen der Zahlung:', ['error' => $e->getMessage()]);
            $this->addError('payment', $e->getMessage());
        }
    }

    public function completeBooking()
    {
        \Log::info('completeBooking()');

        if (ShelfRental::where('shelf_id', '=', $this->selectedShelve->id)
        ->where('rental_start', '=', $this->startDate)
        ->where('rental_end', '=', $this->endDate)
        ->exists()) {
            \Log::info('Das Regal wurde bereits gebucht. Error');
            $this->dispatch('showAlert', 'Das von dir ausgewählte Regal ist im gewünschten Zeitraum leider bereits vergeben. Bitte wähle ein anderes Regal oder einen anderen Zeitraum.', 'warning');
            return;
        }

        if (auth()->check()) {

            $user = auth()->user();
        } else {
            

            // Benutzer mit zufälligem Passwort erstellen
            $randomPassword = Str::random(12);
            $username = $this->first_name." ".$this->last_name;
            $user = User::create([
                'name' => $username,
                'email' => $this->email,
                'password' => bcrypt($randomPassword),
                'current_team_id' => 4,
                'role' => 'guest', 
            ]);
                // Token generieren
                $token = Password::createToken($user);

                // Sende die Passwort-Reset-Mail
                //$user->notify(new ResetPassword($token));

            

            // Customer-Datensatz erstellen
            Customer::create([
                'user_id' => $user->id,
                'username' => $username,
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'street' => $this->street,
                'city' => $this->city,
                'postal_code' => $this->postal_code,
                'state' => $this->state,
                'phone_number' => $this->phone_number ?? null,
            ]);

            
             try {
                $token = Password::getRepository()->create($user);
                $user->notify(new SetPasswordNotification($user, $token));
            } catch (\Swift_TransportException $e) {
                session()->flash('error', 'Die Bestätigungs-E-Mail konnte nicht gesendet werden. Bitte überprüfen Sie Ihre E-Mail-Adresse oder versuchen Sie es später erneut.');
            } catch (\Exception $e) {        
                session()->flash('error', 'Ein Fehler ist aufgetreten. Bitte versuchen Sie es später erneut.');
            }
        
            $this->dispatch('showAlert', 'Herzlich willkommen bei MiniFinds!', 'success');
        }
        // vor der erstellung der buchung nochmal prüfen ob das regal wirklcih noch zu haben ist
        


        // Buchung erstellen
        $shelfRental = ShelfRental::create([
            'customer_id' => $user->customer->id,
            'period' => $this->period,
            'rental_start' => $this->startDate,
            'rental_end' => $this->endDate,
            'shelf_id' => $this->selectedShelve->id,
            'total_price' => $this->finalPrice,
            'payment_method' => 'PayPal',
        ]);
        UpdateBlockedDatesJob::dispatch($shelfRental);

        $rentalBillPath = $this->generateRentalBill($shelfRental);

        $shelfRental->rental_bill_url = $rentalBillPath;
        $shelfRental->save();
        
         $productAddUrl = url('/shelf-rental/'.$shelfRental->id); 
         $bookingDetailsUrl = url('/admin/shelf-rentals/' . $shelfRental->id); 
         $adminEmail = Setting::where('key', 'admin_email')->pluck('value')->first();
         \Log::info($adminEmail);
         Notification::route('mail', $adminEmail)->notify(new AdminBookingCreatedNotification( $shelfRental, $bookingDetailsUrl));

         try {
                $user->notify(new ShelfBookingConfirmation($user, $shelfRental, $productAddUrl));
            } catch (\Swift_TransportException $e) {
                \Log::info('Die Bestätigungs-E-Mail konnte nicht gesendet werden. Bitte überprüfen Sie Ihre E-Mail-Adresse oder versuchen Sie es später erneut.');
                session()->flash('error', 'Die Bestätigungs-E-Mail konnte nicht gesendet werden. Bitte überprüfen Sie Ihre E-Mail-Adresse oder versuchen Sie es später erneut.');
            } catch (\Exception $e) {        
                \Log::info('Ein Fehler ist aufgetreten. Bitte versuchen Sie es später erneut.');
                session()->flash('error', 'Ein Fehler ist aufgetreten. Bitte versuchen Sie es später erneut.');
            }
         $message = 
            'Hallo ' . $user->name . '!  ' . "<br>" .
            'Vielen Dank, dass du dich für MiniFinds entschieden hast!' . "<br><br>" .
            'Deine Buchung wurde erfolgreich bestätigt. Hier sind die Details:' . "<br><br>" .
            '<table><tr><td><strong>Buchungsnummer:   </strong></td><td>' . $shelfRental->id ."</td></tr>" .
            '<tr><td><strong>Regalnummer:   </strong></td><td>' . $shelfRental->shelf->floor_number ."</td></tr>" .
            '<tr><td><strong>Buchungszeitraum:   </strong></td><td>' . Carbon::parse($shelfRental->rental_start)->format('d.m.Y') . ' bis ' . Carbon::parse($shelfRental->rental_end)->format('d.m.Y') . "</td></tr>" .
            '<tr><td><strong>Gesamtpreis:   </strong></td><td>' . number_format($shelfRental->total_price, 2, ',', '.') . ' €' . "</td></tr></table><br><br>" .
            'Falls du Fragen hast, kannst du uns jederzeit kontaktieren.' . "<br><br>" .
            'Mit freundlichen Grüßen,' . "<br>" .
            'Dein MiniFinds-Team';

        $user->sendMessage($user->id, 'Deine Regalbuchung bei MiniFinds wurde bestätigt!', $message);
        
        return $this->redirect('/booking/success/'.$shelfRental->id, navigate: true);
    }

    private function generateRentalBill(ShelfRental $shelfRental)
    {
        $invoice = Invoice::create([
            'shelf_rental_id' => $shelfRental->id, 
            'invoice_identifier' => ' '
        ]);
        $fileName = "{$shelfRental->customer->user->id}_{$shelfRental->customer->last_name}_ID{$invoice->id}_rental_bill_{$shelfRental->id}_date_" . Carbon::now()->format('Y_m_d') . ".pdf";
        $invoice->update([
            'invoice_identifier' => $fileName,
        ]);
        $data = [
            'customer' => $shelfRental->customer,
            'user' => $shelfRental->customer->user,
            'shelf' => $shelfRental->shelf,
            'location' => $shelfRental->shelf->retailSpace->location,
            'products' => $shelfRental->products,
            'shelfRental' => $shelfRental,
            'invoice' => $invoice,
        ];
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $pdf = new \Dompdf\Dompdf($options);
        $pdf->loadHtml(view('pdf.bill.rental_bill', $data)->render());
        $pdf->setPaper('A4', 'portrait');
        $pdf->render();
        $filePath = "private/bills/{$fileName}";
        Storage::disk('local')->put($filePath, $pdf->output());
        return $fileName;
    }

    public function render()
    {    
        return view('livewire.booking')->layout('layouts.app');
    }

}
