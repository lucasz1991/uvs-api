<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\ShelfRental;
use App\Models\Product;
use App\Models\Label;
use App\Models\ShelfBlockedDate;
use Dompdf\Dompdf;
use Dompdf\Options;
use Picqer\Barcode\BarcodeGeneratorSVG;
use Illuminate\Support\Facades\Storage;
use App\Jobs\SendProductToCashRegisterJob;
use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;




class ShowShelfRental extends Component
{
    public $rentalId; // ID der Regalmiete
    public $shelfRental;
    public $selectedProducts = []; // Array für ausgewählte Produkte
    public $action = null; // Aktion, die ausgewählt wurde

    public $downloadUrl = null;

    public $products = [];

    public $progress;

    public $moveProductsModalOpen = false;
    public $availableRentals = [];
    public $selectedRental = null;
    
    public function mount($rentalId)
    {
        $this->progress = 0;
        $this->rentalId = $rentalId;
        $this->loadRental();
    }

    public function loadRental()
    {
        $this->shelfRental = ShelfRental::with(['customer.user', 'shelf', 'products'])->findOrFail($this->rentalId);
        $this->products = $this->shelfRental->products;
    }

    public function printLabels()
    {
        $dompdfOptions = new Options();
        $dompdfOptions->set('isHtml5ParserEnabled', true);
        $dompdfOptions->set('isPhpEnabled', true);
        $dompdfOptions->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($dompdfOptions);

        $labels = Label::whereIn('product_id', $this->selectedProducts)->get();
        $data = [
            'labels' => $labels,
        ];
        $dompdf->loadHtml(view('pdf.labels', $data)->render());
        
        // Setze die Papiergröße mit Umrechnung von mm nach pt (Querformat)
        $width = 50.8 * 2.83465; // 144 pt
        $height = 25.4 * 2.83465; // 72 pt
        $dompdf->setPaper([0, 0, $width, $height], 'landscape'); 
        
        // Render das PDF
        $dompdf->render();
        
        $output = $dompdf->output();
        $fileName = 'labels_' . now()->timestamp . '.pdf';
        $filePath = "site-images/labels/{$fileName}";

        // Datei im Storage ablegen
        $fileStorageResponse = Storage::disk('public')->put($filePath, $output);

        if($fileStorageResponse){
            $file = asset('storage/' .$filePath);
            \Log::info('abels wurden erfolgreich zum Drucken hinzugefügt:', ['Filename' => $filePath]);
            $this->dispatch('showAlert', 'Labels wurden erfolgreich zum Drucken erstellt und als Download bereitgestellt.', 'success');
            $this->downloadUrl = $file;
            
        }else{
            $this->dispatch('showAlert', 'Labels konnten nicht erstellt werden..', 'error');

        }
    }



    public function deactivateProducts()
    {
        // Prüfen, ob Produkte mit Status 2 existieren
        $noActiveProducts = true;
        $anyFailed = false; // Flag für fehlerhafte API-Löschvorgänge
    
        foreach ($this->selectedProducts as $productId) {
            $product = $this->products->find($productId);
            if ($product->status == 2) {
                $noActiveProducts = false;
                $deleted = $this->deleteProductFromApi($product);
    
                if ($deleted) {
                    $product->update(['status' => '1']);
                } else {
                    $anyFailed = true;
                }
            }
        }
    
        if ($noActiveProducts) {
            $this->dispatch('showAlert', 'Alle ausgewählten Produkte sind bereits deaktiv.', 'info');
        } elseif ($anyFailed) {
            $this->dispatch('showAlert', 'Einige Produkte konnten nicht deaktiviert werden.', 'error');
        } else {
            $this->dispatch('showAlert', 'Produkte erfolgreich deaktiviert.', 'success');
        }
    
        $this->loadRental(); // Daten neu laden
    }

    public function deleteProducts()
    {
        $anyFailed = false; // Flag für fehlerhafte API-Löschvorgänge
    
        foreach ($this->selectedProducts as $productId) {
            $product = $this->products->find($productId);
            if ($product->status == 1) {
                $deleted = $this->deleteProductFromApi($product);
    
                if ($deleted) {
                    $product->delete();
                } else {
                    $anyFailed = true;
                }
            }
        }
    
        if ($anyFailed) {
            $this->dispatch('showAlert', 'Einige Produkte konnten nicht gelöscht werden.', 'error');
        } else {
            $this->dispatch('showAlert', 'Produkte erfolgreich gelöscht.', 'success');
        }
    
        $this->selectedProducts = [];
        $this->loadRental(); // Daten neu laden
    }

    public function selectAll()
    {
        $this->selectedProducts = $this->shelfRental->products->pluck('id')->toArray();
    }

    public function deselectAll()
    {
        $this->selectedProducts = [];
    }

    public function executeAction()
    {   
        if (!$this->action || empty($this->selectedProducts)) {
            $this->dispatch('showAlert', 'Keine Aktion oder keine Produkte ausgewählt.', 'info');
            return;
        }
        switch ($this->action) {
            case 'printLabels':
                $this->printLabels();
                break;
            case 'activateProducts':
                $this->activateProducts();
                break;
            case 'deactivateProducts':
                $this->deactivateProducts();
                break;
            case 'deleteProducts':
                $this->deleteProducts();
                break;
            case 'showMoveProductsModal':
                $this->showMoveProductsModal();
                break;
            default:
                $this->dispatch('showAlert', 'Ungültige Aktion ausgewählt.', 'error');
        } 
    }

    public function showMoveProductsModal()
    {
        // Suche nach weiteren verfügbaren Regalmieten des Kunden
        $this->availableRentals = ShelfRental::where('customer_id', $this->shelfRental->customer_id)
        ->where('id', '!=', $this->shelfRental->id) // Die aktuelle Regalmiete ausschließen
        ->where('status', '!=', 7) // Stornierte Regalmieten ausschließen
        ->get();

        if ($this->availableRentals->isNotEmpty()) {
            $this->moveProductsModalOpen = true;
        }else{
            $this->dispatch('showAlert', 'Keine verfügbaren Regalmieten gefunden.', 'info');
        }
    }

    public function moveProducts()
    {
        if (!$this->selectedRental) {
            $this->dispatch('showAlert', 'Bitte wähle eine Ziel-Regalmiete aus.', 'error');
            return;
        }
    
        if (empty($this->selectedProducts)) {
            $this->dispatch('showAlert', 'Keine Produkte ausgewählt.', 'error');
            return;
        }
    
        // Finde nur die ausgewählten Produkte mit Status "Entwurf" (status = 1)
        $draftProducts = Product::whereIn('id', $this->selectedProducts)
            ->where('status', 1)
            ->get();
    
        if ($draftProducts->isEmpty()) {
            $this->dispatch('showAlert', 'Keines der ausgewählten Produkte ist ein Entwurf. Nur Entwürfe können umgezogen werden.', 'error');
            return;
        }
    
        // Produkte umziehen
        Product::whereIn('id', $draftProducts->pluck('id'))->update([
            'shelf_rental_id' => $this->selectedRental,
        ]);
    
        $this->dispatch('showAlert', count($draftProducts) . ' Entwürfe wurden erfolgreich umgezogen.', 'success');
    
        // Zurücksetzen
        $this->moveProductsModalOpen = false;
        $this->selectedRental = null;
        $this->selectedProducts = [];
        $this->loadRental();
    }   

    public function activateProducts()
    {
        $totalProducts = count($this->selectedProducts);
        $allGood = false; 
        $allActive = true; 
    
        foreach ($this->selectedProducts as $index => $productId) {
            $product = $this->products->find($productId);
            if ($product->status == 1) {
                $allActive = false; 
                $attempts = 0;
                while ($attempts < 3) { 
                    $response = $this->sendProductDataToApi($product);
                    if ($response) {
                        $product->update(['status' => 2]);
                        $allGood = true;
                        break;
                    } else {
                        $product->deleteFromCashRegisterApi();
                        $allGood = false;
                        $attempts++;
                    }
                }
            }
        }
        
        if ($allActive) {
            // Alle Produkte sind bereits aktiv
            $this->dispatch('showAlert', 'Alle ausgewählten Produkte sind bereits aktiv.', 'info');
        } elseif ($allGood) {
            // Einige Produkte wurden erfolgreich aktiviert
            $this->dispatch('showAlert', 'Produkte erfolgreich aktiviert und an das Kassensystem gesendet.', 'success');
        } else {
            // Kein Produkt konnte aktiviert werden
            $this->dispatch('showAlert', 'Es gab einen Fehler beim Versuch, die Produkte an das Kassensystem zu senden. Versuche es bitte erneut falls das Problem weiterhin besteht, bitte Admin informieren.', 'warning');
        }
    
        $this->progress = 0; // Fortschrittsanzeige zurücksetzen
    }

    public function sendProductDataToApi($product)
    {
        // API Einstellungen abrufen
        $apiSettings = Setting::where('type', 'api')
            ->where('key', 'like', 'cash_register%')
            ->pluck('value', 'key')
            ->mapWithKeys(fn ($value, $key) => [$key => $value])
            ->toArray();
        $apiToken = $apiSettings['cash_register_api_key'];
        $apiUrl = $apiSettings['cash_register_api_url'];
        $client = new Client();
    
        $label = $product->labels->isNotEmpty() ? $product->labels[0] : null;
        $image = $product->getAllImageUrls('m')[0] ?? "";

        $priceToSend = $product->discount > 0 ? $product->discount_price : $product->price;
    
        // Artikel für das Produkt erstellen
        $article = [
            'number' => (string) $product->id,
            'title' => (string) $product->name,
            'displayName' => (string) $product->name,
            'taxassignment' => '30000',
            'ean' => $label->barcode ?? null,
            'sale' => [
                [
                    'pricelist' => 'Endkundenpreis',
                    'price' => (string) $priceToSend,
                ]
            ],
            'stock' => [
                'inventoryManagement' => true,
                'availability' => [
                    'total' => [
                        '6763eb5561a5062954290604' => [
                            'physical' => (string)1,
                            'calculated' => (string)1,
                            'calculated1' => (string)1,
                        ],
                    ],
                    
                ],
                "negativeStock"=>false,
            ],
        ];
    
        $jsonBody = json_encode($article);
    
        Log::info($jsonBody);
    
        try {
            $maxRetries = 100; // Maximale Anzahl von Wiederholungen
            $retryDelay = 5; // Wartezeit in Sekunden
            $attempts = 0;
        
            do {
                $attempts++;
        
                try {
                    $response = $client->request('POST', $apiUrl . 'articles', [
                        'body' => $jsonBody,
                        'headers' => [
                            'Authorization' => 'Bearer ' . $apiToken,
                            'accept' => 'application/json',
                            'content-type' => 'application/json',
                        ],
                    ]);
        
                    if ($response->getStatusCode() === 200) {
                        $responseData = json_decode($response->getBody(), true);


                            // Extrahiere die _id aus der Antwort
                            if (isset($responseData['number']) && isset($responseData['_id'])) {
                                // Suchen des Produkts anhand der Produkt-ID
                                $product = Product::find($responseData['number']); // Angenommene Produkt-ID
                                
                                if ($product) {
                                    // Speichern der _id in der cash_register_id Spalte
                                    $product->cash_register_id = $responseData['_id'];
                                    $product->save(); // Speichern der Änderungen
                                    Log::info('API-Daten erfolgreich gesendet:', $responseData);
                                    return true; // Anfrage war erfolgreich, Schleife verlassen
                                }
                            }



                    } else {
                        Log::error('Fehler beim Senden der API-Daten:', ['status' => $response->getStatusCode()]);
                        return false;
                    }
                } catch (\GuzzleHttp\Exception\ClientException $e) {
                    // Prüfen, ob der Fehler ein 429-Rate-Limit-Fehler ist
                    if ($e->getResponse() && $e->getResponse()->getStatusCode() === 429) {
                        Log::warning('Rate-Limit erreicht. Warte 5 Sekunden...', [
                            'message' => $e->getMessage(),
                        ]);
        
                        if ($attempts < $maxRetries) {
                            sleep($retryDelay); // 5 Sekunden warten
                        } else {
                            Log::error('Maximale Anzahl von Wiederholungen erreicht. Anfrage abgebrochen.');
                            return false;
                        }
                    } else {
                        // Wenn ein anderer Fehler auftritt, diesen loggen und abbrechen
                        Log::error('API-Anfrage fehlgeschlagen:', [
                            'message' => $e->getMessage(),
                            'response' => $e->hasResponse() ? $e->getResponse()->getBody()->getContents() : null,
                        ]);
                        return false;
                    }
                }
        
            } while ($attempts < $maxRetries);
        } catch (\Exception $e) {
            Log::error('Unerwarteter Fehler bei der API-Anfrage:', [
                'message' => $e->getMessage(),
            ]);
            return false;
        }
    }

    public function deleteProductFromApi($product)
    {
        // API Einstellungen abrufen
        $apiSettings = Setting::where('type', 'api')
            ->where('key', 'like', 'cash_register%')
            ->pluck('value', 'key')
            ->mapWithKeys(fn($value, $key) => [$key => $value])
            ->toArray();
        $apiToken = $apiSettings['cash_register_api_key'];
        $apiUrl = $apiSettings['cash_register_api_url'];
        $client = new Client();
    
        $maxRetries = 30; // Maximale Anzahl von Wiederholungen
        $retryDelay = 5; // Wartezeit in Sekunden
        $attempts = 0;
    
        do {
            $attempts++;
    
            try {
                // Anfrage an die API senden, um das Produkt zu löschen
                $response = $client->delete($apiUrl.'articles/'.(string) $product->cash_register_id, [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $apiToken,
                        'accept' => 'application/json',
                    ],
                ]);
    
                if ($response->getStatusCode() === 200) {
                    Log::info("Produkt {$product->id} erfolgreich aus dem Kassensystem gelöscht.");
                    return true; // Erfolgreich, Schleife verlassen
                } else {
                    Log::error("Fehler beim Löschen von Produkt {$product->id} aus dem Kassensystem. Status: " . $response->getStatusCode());
                    return false; // Kein 429-Fehler, daher Abbruch
                }
            } catch (\GuzzleHttp\Exception\ClientException $e) {
                // Prüfen, ob der Fehler ein 429-Rate-Limit-Fehler ist
                if ($e->getResponse() && $e->getResponse()->getStatusCode() === 429) {
                    Log::warning("Rate-Limit erreicht beim Löschen von Produkt {$product->id}. Warte {$retryDelay} Sekunden...");
                    
                    if ($attempts < $maxRetries) {
                        sleep($retryDelay); // Wartezeit vor erneutem Versuch
                    } else {
                        Log::error("Maximale Anzahl von Wiederholungen erreicht. Löschen von Produkt {$product->id} abgebrochen.");
                        return false;
                    }
                } else {
                    // Wenn ein anderer Fehler auftritt, diesen loggen und abbrechen
                    Log::error("API-Anfrage zum Löschen von Produkt {$product->id} fehlgeschlagen:", [
                        'message' => $e->getMessage(),
                        'response' => $e->hasResponse() ? $e->getResponse()->getBody()->getContents() : null,
                    ]);
                    return false;
                }
            }
        } while ($attempts < $maxRetries);
    
        // Sollte nie erreicht werden, aber als Sicherheitsmaßnahme:
        Log::error("Unbekannter Fehler beim Löschen von Produkt {$product->id}. Vorgang abgebrochen.");
        return false;
    }

    
    public function cancelRental()
    {
        try {
            if (!$this->shelfRental) {
                $this->dispatch('showAlert', 'Regalmiete nicht gefunden.', 'error');
                return;
            }
    
            // Aktive Produkte deaktivieren
            $activeProducts = $this->shelfRental->products->where('status', 2);
            if ($activeProducts->isNotEmpty()) {
                foreach ($activeProducts as $product) {
                    $this->deleteProductFromApi($product);
                    $product->update(['status' => 1]); // Zurück auf Entwurf setzen
                }
            }
    
            // Status der Regalmiete auf 7 (Storniert) setzen
            $this->shelfRental->update(['status' => 7]);
    
            // Blockierte Daten für die Mietzeit löschen
            $deletedBlockedDates = ShelfBlockedDate::where('shelf_id', $this->shelfRental->shelf_id)
                ->where('retail_space_id', $this->shelfRental->shelf->retail_space_id)
                ->whereBetween('blocked_date', [
                    $this->shelfRental->rental_start,
                    $this->shelfRental->rental_end
                ])
                ->delete();
    
            // Erfolgsmeldung
            $this->dispatch('showAlert', 'Regalmiete erfolgreich storniert.', 'success');
    
            // Daten neu laden
            $this->loadRental();
        } catch (\Exception $e) {
            Log::error('Fehler beim Stornieren der Regalmiete:', ['message' => $e->getMessage()]);
            $this->dispatch('showAlert', 'Fehler beim Stornieren der Regalmiete.', 'error');
        }
    }
    

    public function render()
    {
        return view('livewire.admin.show-shelf-rental')->layout('layouts.master');
    }
}
