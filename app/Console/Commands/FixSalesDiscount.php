<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Sale;
use App\Models\Product;
use App\Models\ShelfRental;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class FixSalesDiscount extends Command
{
    protected $signature = 'sales:fix-discounts';
    protected $description = 'Überprüft vergangene Verkäufe und korrigiert den Preis, falls ein Rabatt nachträglich gesetzt wurde.';

    public function handle()
    {
        $this->info('Überprüfung der letzten Verkäufe gestartet...');

        // Letzte 6 Tage prüfen, inklusive heute
        $sales = Sale::where('created_at', '>=', now()->subDays(6)->startOfDay())->get();

        $updatedCount = 0;

        foreach ($sales as $sale) {
            $product = Product::find($sale->product_id);

            if ($product && $product->shelf_rental_id) {
                $shelfRental = ShelfRental::find($product->shelf_rental_id);

                if ($shelfRental) {
                    // Sicherstellen, dass die Datumswerte als Carbon-Instanzen behandelt werden
                    $shelfRentalUpdatedAt = Carbon::parse($shelfRental->updated_at);
                    $shelfRentalCreatedAt = Carbon::parse($shelfRental->created_at);
                    $saleCreatedAt = Carbon::parse($sale->created_at);

                    // Prüfen, ob das ShelfRental nachträglich aktualisiert wurde
                    if ($shelfRentalUpdatedAt->gt($shelfRentalCreatedAt) && $saleCreatedAt->gt($shelfRentalUpdatedAt)) {
                        // Prüfen, ob der Verkaufspreis falsch gespeichert wurde
                        if ($product->discount > 0 && $sale->sale_price != $product->discount_price) {
                            $oldPrice = $sale->sale_price;
                            $salePrice = $product->discount_price;

                            // Netto-Preis berechnen (abzüglich 16%)
                            $netSalePrice = round($salePrice * 0.84, 2); // 100% - 16% = 84%

                            // Verkauf aktualisieren
                            $sale->sale_price = $salePrice;
                            $sale->net_sale_price = $netSalePrice;
                            $sale->save();

                            Log::info('Verkaufspreis korrigiert', [
                                'sale_id' => $sale->id,
                                'product_id' => $product->id,
                                'old_price' => $oldPrice,
                                'new_price' => $sale->sale_price,
                                'net_sale_price' => $sale->net_sale_price,
                                'shelf_rental_updated_at' => $shelfRentalUpdatedAt->toDateTimeString(),
                                'shelf_rental_created_at' => $shelfRentalCreatedAt->toDateTimeString(),
                                'sale_created_at' => $saleCreatedAt->toDateTimeString(),
                            ]);

                            $this->info("Verkaufspreis korrigiert: Sale ID {$sale->id}, Product ID {$product->id}, Neuer Preis: {$sale->sale_price}, Netto: {$sale->net_sale_price}");

                            $updatedCount++;
                        }
                    }
                }
            }
        }

        $this->info("Anpassungen abgeschlossen. {$updatedCount} Verkäufe korrigiert.");
    }
}
