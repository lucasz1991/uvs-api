<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class DeleteDraftProductsFromCashRegister extends Command
{
    /**
     * Der Name des Artisan-Befehls.
     *
     * @var string
     */
    protected $signature = 'cashregister:delete-draft-products';

    /**
     * Beschreibung des Befehls.
     *
     * @var string
     */
    protected $description = 'Löscht alle Entwurfs-Produkte (Status 1) aus der Kassen-API, falls sie dort noch existieren.';

    /**
     * Führt den Command aus.
     */
    public function handle()
    {
        $this->info('Starte das Löschen aller Entwurfs-Produkte aus der Kassen-API...');
        Log::info('Starte das Löschen aller Entwurfs-Produkte aus der Kassen-API...');

        $twoDaysAgo = Carbon::now()->subDays(2);


        $products = Product::where('status', 1)
            ->whereNotNull('cash_register_id')
            ->where('updated_at', '>=', $twoDaysAgo)
            ->get();

        $deletedCount = 0;
        $failedCount = 0;

        foreach ($products as $product) {
            if ($product->deleteFromCashRegisterApi()) {
                Log::info("Produkt ID {$product->id} erfolgreich aus der Kassen-API gelöscht.");
                $deletedCount++;
            } else {
                Log::error("Produkt ID {$product->id} konnte nicht aus der Kassen-API gelöscht werden.");
                $failedCount++;
            }
        }

        $this->info("Löschvorgang abgeschlossen: Erfolgreich: {$deletedCount}, Fehlgeschlagen: {$failedCount}");
        Log::info("Löschvorgang abgeschlossen: Erfolgreich: {$deletedCount}, Fehlgeschlagen: {$failedCount}");
    }
}
