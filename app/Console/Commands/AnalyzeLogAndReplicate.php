<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use App\Models\ShelfRental;
use App\Models\Product;
use App\Models\Sale;
use Carbon\Carbon;

class AnalyzeLogAndReplicate extends Command
{
    protected $signature = 'log:analyze-replicate {logfile?}';
    protected $description = 'Analysiert eine spezifische Log-Datei und führt ähnliche Aktionen aus.';

    public function handle()
    {
        $logFile = $this->argument('logfile') 
            ? storage_path('logs/' . $this->argument('logfile')) 
            : storage_path('logs/laravel.log');

        if (!file_exists($logFile)) {
            $this->error('Log-Datei nicht gefunden.');
            return;
        }

        $logContent = file_get_contents($logFile);
        $this->processLogEntries($logContent);
    }

    protected function processLogEntries($logContent)
    {
        $lines = explode("\n", $logContent);

        foreach ($lines as $line) {
            if (strpos($line, 'wurde auf Status 2 (Aktiv) gesetzt') !== false) {
                $this->activateShelfRental($line);
            }
            
            if (strpos($line, 'wurde auf Status 3 (Abgelaufen) gesetzt') !== false) {
                $this->deactivateShelfRental($line);
            }
            
            if (strpos($line, 'API-Daten erfolgreich gesendet') !== false) {
                $this->processProductApi($line);
            }
            
            if (strpos($line, 'Sende Rabatt-Daten an API') !== false) {
                $this->processDiscount($line);
            }
            
            if (strpos($line, 'Produktstatus aktualisiert: {"product_id":') !== false) {
                $this->processSale($line);
            }
        }
    }

    protected function extractTimestamp($line)
    {
        preg_match('/\[(.*?)\]/', $line, $matches);
        return $matches[1] ?? 'Unbekannte Zeit';
    }

    protected function activateShelfRental($line)
    {
        preg_match('/Regalmiete ID (\d+)/', $line, $matches);
        if (isset($matches[1])) {
            $shelfRentalId = $matches[1];
            $timestamp = $this->extractTimestamp($line);
    
            // Hole die Regalmiete basierend auf der ID
            $shelfRental = ShelfRental::find($shelfRentalId);
    
            if ($shelfRental) {
                $shelfRental->activate(); // Methode aus dem Modell aufrufen
                $this->info("[{$timestamp}] Regalmiete {$shelfRentalId} aktiviert.");
            } else {
                $this->warn("[{$timestamp}] Regalmiete {$shelfRentalId} nicht gefunden.");
            }
        }
    }

    protected function deactivateShelfRental($line)
    {
        preg_match('/Regalmiete ID (\d+)/', $line, $matches);
        if (isset($matches[1])) {
            $shelfRentalId = $matches[1];
            $timestamp = $this->extractTimestamp($line);
            $shelfRental = ShelfRental::find($shelfRentalId);
            if ($shelfRental) {
                $shelfRental->deactivate(); 
                $this->info("[{$timestamp}] Regalmiete {$shelfRentalId} abgelaufen und deaktiviert. Zugehörige Produkte wurden auf Status 3 gesetzt.");
            } else {
                $this->warn("[{$timestamp}] Regalmiete {$shelfRentalId} nicht gefunden.");
            }
        }
    }

    protected function processProductApi($line)
    {
        preg_match('/number":"(\d+)"/', $line, $matches);
        if (isset($matches[1])) {
            $productNumber = $matches[1];
            $timestamp = $this->extractTimestamp($line);
    
            // Produkt anhand der Artikelnummer suchen, falls 'number' nicht die ID ist
            $product = Product::find($productNumber);
    
            if ($product) {
                if (method_exists($product, 'publish')) {
                    $product->publish(); // Produkt erneut an API senden (sofern Methode existiert)
                    $this->info("[{$timestamp}] Produkt mit Nummer {$productNumber} erfolgreich an API gesendet.");
                } else {
                    $this->warn("[{$timestamp}] Methode 'publish()' für Produkt {$productNumber} nicht gefunden.");
                }
            } else {
                $this->warn("[{$timestamp}] Produkt zum aktivieren mit Nummer {$productNumber} nicht gefunden.: {$line}");
            }
        }
    }
    
    protected function processDiscount($line)
    {
        preg_match('/product_id":(\d+),"finalPrice":"([0-9.]+)"/', $line, $matches);
        if (isset($matches[1], $matches[2])) {
            $productId = $matches[1];
            $finalPrice = (float) $matches[2];
            $timestamp = $this->extractTimestamp($line);
    
            // Produkt aus der Datenbank abrufen
            $product = Product::find($productId);
    
            if (!$product) {
                $this->warn("[{$timestamp}] Produkt {$productId} nicht gefunden.");
                return;
            }
    
            $originalPrice = (float) $product->price ?? 0; // Annahme: original_price existiert
            if ($originalPrice <= 0) {
                $this->warn("[{$timestamp}] Originalpreis für Produkt {$productId} ungültig oder nicht gesetzt.");
                return;
            }
    
                // Rabatt berechnen (25% oder 50%)
            $calculatedDiscount = (1 - ($finalPrice / $originalPrice)) * 100;

            if ($calculatedDiscount >= 37.5) {
                $discountPercentage = 50;
            } else {
                $discountPercentage = 25;
            }
            
            $product->update(['discount' => $discountPercentage]);
            $product->update(['discount_price' => $finalPrice]);

            // Zugehörige Regalmiete abrufen
            $shelfRental = $product->shelfRental;
    
            if (!$shelfRental) {
                $this->warn("[{$timestamp}] Keine Regalmiete für Produkt {$productId} gefunden.");
                return;
            }
    
            // Prüfen, ob der Rabatt bereits gespeichert ist
            if ($shelfRental->discount != $discountPercentage) {
                $shelfRental->update(['discount' => $discountPercentage]);
                $this->info("[{$timestamp}] Rabatt von {$discountPercentage}% für Produkt {$productId} gesetzt und Regalmiete {$shelfRental->id} aktualisiert.");
            } else {
                $this->info("[{$timestamp}] Rabatt für Produkt {$productId} bereits in Regalmiete {$shelfRental->id} gespeichert.");
            }
        }
    }
    
    protected function processSale($line)
    {
        preg_match('/Produktstatus aktualisiert: {"product_id":(\d+),"status":4}/', $line, $matches);
        if (isset($matches[1])) {
            $productId = $matches[1];
            $timestamp = $this->extractTimestamp($line);
    
            // Produkt aus der Datenbank abrufen
            $product = Product::find($productId);
    
            if (!$product) {
                $this->warn("[{$timestamp}] Verkauftes Produkt {$productId} nicht gefunden. !!!!!!");
                return;
            }
            $product->sold();
    
            // Verkaufspreis berechnen (Rabattpreis oder Normalpreis)
            $salePrice = ($product->discount > 0) ? $product->discount_price : $product->price;
    
            $existingSale = Sale::where('product_id', $productId)->exists();
            if (!$existingSale) {
                // Sale in der Datenbank erstellen
                Sale::create([
                    'product_id'  => $product->id,
                    'customer_id' => $product->customer_id,
                    'rental_id'   => $product->shelf_rental_id,
                    'date'        => Carbon::now(),
                    'sale_price'  => $salePrice,
                ]);
    
                $this->info("[{$timestamp}] Verkauf für Produkt {$productId} zum Preis von {$salePrice}€ erfolgreich eingetragen.");
            } else {
                $this->warn("[{$timestamp}] Produkt {$productId} wurde bereits verkauft. Kein doppelter Eintrag.");
            }
        }
    }
    
}