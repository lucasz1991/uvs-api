<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Support\Facades\Log;

class CheckAndUpdateExpiredProducts extends Command
{
    /**
     * Der Name und die Signatur des Konsolenbefehls.
     */
    protected $signature = 'products:check-expired';

    /**
     * Die Beschreibung des Konsolenbefehls.
     */
    protected $description = 'Prüft alle abgelaufenen Produkte und aktualisiert den Status entsprechend';

    /**
     * Führe den Konsolenbefehl aus.
     */
    public function handle()
    {
        $this->info("Starte Prüfung abgelaufener Produkte...");

        $expiredProducts = Product::where('status', 'expired')->get();

        foreach ($expiredProducts as $product) {
            // Prüfen, ob es einen Sale für das Produkt gibt
            $hasSale = Sale::where('product_id', $product->id)->exists();

            if ($hasSale) {
                $product->update(['status' => 4]); // Falls verkauft, auf Status 4 setzen
                Log::info("Produkt ID {$product->id} hat einen Verkauf und wurde auf Status 4 gesetzt.");
            } else {
                $product->update(['status' => 2]); // Falls kein Verkauf, auf Status 2 setzen
                Log::info("Produkt ID {$product->id} wurde auf Status 2 gesetzt.");
            }
        }

        $this->info("Prüfung abgeschlossen. Alle abgelaufenen Produkte wurden aktualisiert.");
    }
}
