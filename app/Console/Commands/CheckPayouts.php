<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Payout;
use App\Models\ShelfRental;
use Illuminate\Support\Facades\Log;

class CheckPayouts extends Command
{
    /**
     * Der Name des Artisan-Befehls.
     *
     * @var string
     */
    protected $signature = 'payout:check';

    /**
     * Die Beschreibung des Artisan-Befehls.
     *
     * @var string
     */
    protected $description = 'Prüft alle Payouts, berechnet die Verkäufe neu und speichert den neuen Betrag.';

    /**
     * Ausführungslogik des Befehls.
     */
    public function handle()
    {
        $this->info('Starte Prüfung der Payouts...');
        Log::info('Starte Prüfung der Payouts...');

        // Alle Payouts laden
        $payouts = Payout::all();
        $updatedCount = 0;

        foreach ($payouts as $payout) {
            $shelfRental = ShelfRental::find($payout->shelf_rental_id);

            if (!$shelfRental) {
                Log::warning("Regalmiete für Payout ID {$payout->id} nicht gefunden.");
                continue;
            }

         
            $totalSales = $shelfRental->sales()
            ->where('status', 2) 
            ->sum('net_sale_price');

            // Neuen Betrag in der Payout speichern
            $payout->update(['amount' => $totalSales]);

            Log::info("Payout ID {$payout->id} wurde aktualisiert. Neuer Betrag: {$totalSales}");
            $updatedCount++;
        }

        $this->info("Prüfung abgeschlossen. {$updatedCount} Payouts wurden aktualisiert.");
        Log::info("Prüfung abgeschlossen. {$updatedCount} Payouts wurden aktualisiert.");
    }
}
