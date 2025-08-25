<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ShelfRental;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Notifications\ShelfBookingActive;
use App\Notifications\ShelfBookingExpired;


class CheckShelfRentals extends Command
{
    /**
     * Der Name des Befehls für Artisan.
     *
     * @var string
     */
    protected $signature = 'shelf:check-rentals';

    /**
     * Die Beschreibung des Befehls.
     *
     * @var string
     */
    protected $description = 'Überprüft die Regalmieten und aktualisiert deren Status basierend auf dem aktuellen Datum.';

    /**
     * Ausführungslogik des Befehls.
     */
    public function handle()
    {
        $this->info('Überprüfung der Regalmieten gestartet...');
        Log::info('Überprüfung der Regalmieten gestartet...');

        $now = Carbon::now();
        $hour = (int) $now->format('H'); // Aktuelle Stunde (0-23)
        $dayOfWeek = $now->dayOfWeek; // 0 = Sonntag, 1 = Montag, ..., 6 = Samstag

        if ($hour < 12) {
            // Morgens: Starte Regalmieten prüfen
            $this->info('Morgens erkannt: Prüfung der startenden Regalmieten.');
            Log::info('Morgens erkannt: Prüfung der startenden Regalmieten.');
            $this->updateToActive($now);
        } elseif (
            ($hour == 17 && $dayOfWeek >= 1 && $dayOfWeek <= 5) || // Montag - Freitag um 17:00
            ($hour == 15 && $dayOfWeek == 6) // Samstag um 15:00
        ) {
            // Nachmittags: Ablaufende Regalmieten prüfen
            $this->info('Nachmittags erkannt: Prüfung der ablaufenden Regalmieten.');
            Log::info('Nachmittags erkannt: Prüfung der ablaufenden Regalmieten.');
            $this->updateToExpired($now);
        }

        $this->info('Überprüfung der Regalmieten abgeschlossen.');
        Log::info('Überprüfung der Regalmieten abgeschlossen.');
    }

    private function updateToActive($now)
    {
        $upcomingRentals = ShelfRental::whereDate('rental_start', '=', $now->toDateString())
            ->whereIn('status', [1, 5]) 
            ->get();
    
        foreach ($upcomingRentals as $shelfRental) {
            $newStatus = 2; 
            $shelfRental->update(['status' => $newStatus]);
    
            try {
                // Notification senden
                $shelfRental->customer->user->notify(new ShelfBookingActive($shelfRental));
                Log::info("Notification für Regalmiete ID {$shelfRental->id} wurde erfolgreich gesendet.");
            } catch (\Exception $e) {
                Log::error("Fehler beim Senden der Notification für Regalmiete ID {$shelfRental->id}: " . $e->getMessage());
            }
            Log::info("Regalmiete ID {$shelfRental->id} wurde auf Status {$newStatus} (Aktiv) gesetzt.");
        }
    }

    /**
     * Aktualisiert aktive Regalmieten (Status 2, 6) auf abgelaufen.
     */
    private function updateToExpired($now)
    {
        $expiredRentals = ShelfRental::whereDate('rental_end', '=', $now->toDateString())
            ->whereIn('status', [2, 6])
            ->get();
        foreach ($expiredRentals as $rental) {
            $rental->update(['status' => 3]);
            Log::info("Regalmiete ID {$rental->id} wurde auf Status 3 (Abgelaufen) gesetzt.");
            foreach ($rental->products as $product) {
                if ($product->status == 2) { 
                    if ($product->deleteFromCashRegisterApi()) { 
                        $product->update(['status' => 1]); 
                        Log::info("Produkt ID {$product->id} wurde erfolgreich aus der Kasse gelöscht und auf 'Entwurf' zurückgesetzt.");
                    } else {
                        Log::error("Produkt ID {$product->id} konnte nicht aus der Kasse gelöscht werden. Status bleibt unverändert.");
                    }
                }
            }
            try {
                $rental->customer->user->notify(new ShelfBookingExpired($rental));
                Log::info("Notification für Regalmiete ID {$rental->id} wurde erfolgreich gesendet.");
            } catch (\Exception $e) {
                Log::error("Fehler beim Senden der Notification für Regalmiete ID {$rental->id}: " . $e->getMessage());
            }
        }
    }

}
