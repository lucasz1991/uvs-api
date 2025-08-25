<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;
use App\Models\ShelfRental;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Support\Facades\Auth;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Livewire\Attributes\Locked;


class ProductManager extends Component
{
    use WithFileUploads;
    
    public $productId;
    public $name;
    public $price;
    public $description;
    public $size;
    public $images; 
    public $uploadedImages = []; 
    public $category;
    public $tags= []; 
    public $allcategories;
    public $alltags;
    public $ageRecommendation;
    public $status;

    #[Locked] 
    public ShelfRental $shelfRental;
    #[Locked] 
    public Product $product;



    public function mount($shelfRental = null, $product = null)
    {   
        if($shelfRental){
            $this->shelfRental = $shelfRental;
        }else{
            $this->product = $product;
            $this->shelfRental = $this->product->shelfRental;
        }
        if ($product && $product->shelf_rental_id !== $this->shelfRental->id) {
            abort(403, 'Produkt gehört nicht zu dieser Regalmiete.');
        }
        if ($product) {
            $this->productId = $product->id;
            $this->name = $product->name;
            $this->price = number_format($product->price, 2, ',', '');
            $this->description = $product->description;
            $this->size = $product->size;
            $this->images = $product->images;
            $this->uploadedImages = $product->getAllImageUrls('l') ?? [];
            $this->category = $product->category;
            $this->tags = $product->tags ?? [];
            $this->ageRecommendation = $product->age_recommendation;
            $this->status = $product->status;
        } else {
            // Standardwerte für ein neues Produkt
            $this->resetFields();
        }
         // Holen der Kategorien und Tags
         $this->allcategories = Category::whereNull('parent_id')
         ->with('children.children')
         ->get();

         $this->alltags = Tag::all();
    }

    public function resetFields()
    {
        $this->productId = null;
        $this->name = '';
        $this->price = '';
        $this->description = '';
        $this->size = '';
        $this->images = [];
        $this->uploadedImages = [];
        $this->category = '';
        $this->tags = [];
        $this->ageRecommendation = '';
        $this->status = '1'; // Standard: Entwurf
    }

    protected $rules = [
        'name' => 'required|string|max:50',
        'price' => [
            'required',
            'regex:/^(?:1500(?:,0{1,2})?|(?:[1-9]\d{0,2}|1[0-4]\d{2})(?:,\d{1,2})?)$/'
        ],
        'description' => 'string|max:1600',
        'size' => 'max:7',
        'uploadedImages' => 'max:5|array',
        'uploadedImages.*' => 'max:10020',
        'category' => 'nullable|string|max:50',
    ];
    
    protected $messages = [
        'name.required' => 'Der Name des Produkts ist erforderlich.',
        'name.string' => 'Der Name muss ein gültiger Text sein.',
        'name.max' => 'Der Name darf maximal 50 Zeichen lang sein.',
        'price.required' => 'Der Preis ist erforderlich.',
        'price.regex' => 'Der Preis muss das Format "123,45" haben, zwischen 1,00 und 1500,00 Euro liegen und maximal zwei Nachkommastellen haben.',
    
        'description.required' => 'Die Beschreibung des Produkts ist erforderlich.',
        'description.string' => 'Die Beschreibung muss ein gültiger Text sein.',
        'description.min' => 'Die Beschreibung muss mindestens 20 Zeichen lang sein.',
        'description.max' => 'Die Beschreibung darf höchstens 1600 Zeichen lang sein.',
        'uploadedImages.max' => 'Es dürfen höchstens 5 Bilder hochgeladen werden.',
        'uploadedImages.array' => 'Die Bilder müssen als Array übermittelt werden.',
        'uploadedImages.*.image' => 'Jede hochgeladene Datei muss ein Bild sein.',
        'uploadedImages.*.mimes' => 'Bilder dürfen nur im Format JPEG, PNG oder GIF hochgeladen werden.',
        'uploadedImages.*.max' => 'Jedes Bild darf maximal 10 MB groß sein.',
        'category.nullable' => 'Die Kategorie ist optional, muss jedoch ein gültiger Text sein.',
        'max.file' => 'Die Datei :attribute darf maximal :max Kilobyte groß sein.',
    ];

    public function updated($propertyName)
    {
   
        $this->validateOnly($propertyName);
    }

    public function saveProduct()
    {   
        $this->validate();
        $imagePaths = [];
        $timestamp = now()->format('YmdHis');
        
    
        foreach ($this->uploadedImages as $index => $image) {
            if (is_object($image) || (is_string($image) && Str::contains($image, '/storage/temp/'))) {
                // Livewire Vorschau-Datei (neues Bild)
                $imageObject = $image;
                if ($imageObject) {
                    $userId = Auth::id();

                    if ($this->productId) {
                        $baseName = "{$this->shelfRental->id}_{$this->productId}_{$userId}_{$timestamp}_{$index}";
                    } else {
                        $baseName = "{$this->shelfRental->id}_{$userId}_{$timestamp}_{$index}";
                    }
                    
        
                    $sizes = [
                        's' => 400,
                        'm' => 850,
                        'l' => 2000,
                    ];
        
                    foreach ($sizes as $suffix => $width) {
                        $path = "site-images/products/{$baseName}_{$suffix}.webp";
                        $this->resizeAndConvertToJpg($imageObject, $path, $width);
                    }
        
                    $imagePaths[] = $baseName;
                } else {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'uploadedImages' => "Die Vorschau-Datei konnte nicht verarbeitet werden.",
                    ]);
                }
            } elseif (is_string($image) && Str::contains($image, '/storage/site-images/products/')) {
                
               // Extrahiere den Basisnamen aus dem bestehenden Bild
               $urlPath = parse_url($image, PHP_URL_PATH);
               $baseName = pathinfo($urlPath, PATHINFO_FILENAME);  // Extrahiert den Dateinamen ohne Erweiterung
               
               // Entferne die letzten zwei Zeichen des Basisnamens
               $baseName = substr($baseName, 0, -2);  // Entfernt die letzten zwei Zeichen
               
               $imagePaths[] = $baseName;
                
            } else {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'uploadedImages' => 'Ungültiger Bildtyp. Bilder müssen Dateien oder URLs sein.',
                ]);
            }
        }
        $price = str_replace(',', '.', $this->price); // Ersetze das Komma durch einen Punkt
        $price = floatval($price);
        $data = [
            'customer_id' => Auth::user()->customer->id, // Setzt die Kunden-ID basierend auf dem angemeldeten Benutzer
            'shelf_rental_id' => $this->shelfRental->id, // Verknüpft mit der Regalmiete
            'name' => $this->name,
            'description' => $this->description ?? '',
            'price' => $price,
            'size' => $this->size ?? '',
            'category' => $this->category ?? '',
            'tags' => $this->tags ?? [],
            'age_recommendation' => $this->ageRecommendation ?? '',
            'images' => json_encode($imagePaths) ??  [],
            'status' => $this->status,
        ];
        
        // Produkt speichern oder aktualisieren
        if ($this->productId) {
            Product::findOrFail($this->productId)->update($data);
        } else {
            Product::create($data);
        }
        
        return $this->redirect('/shelf-rental/'.$this->shelfRental->id, navigate: true);
    }

    public function updateImage($imageData, $index)
    {
        // Stelle sicher, dass der Index innerhalb des gültigen Bereichs liegt
        if (isset($this->uploadedImages[$index])) {
            // Hole das Bild an dem angegebenen Index
            $image = $this->uploadedImages[$index];
    
            // Beispiel für die Generierung eines neuen Dateinamens (ohne $timestamp, falls gewünscht)
            $userId = Auth::id();
            $timestamp = now()->format('YmdHis');

            if ($this->productId) {
                $baseName = "{$this->shelfRental->id}_{$this->productId}_{$userId}_{$timestamp}_{$index}";
            } else {
                $baseName = "{$this->shelfRental->id}_{$userId}_{$timestamp}_{$index}";
            }
            
            // Konvertiere das Bild aus Base64 (wenn nötig)
            $imageDecoded = $imageData;
            $imageName = "{$baseName}.webp";
            $imagePath = public_path("images/{$imageName}");
            
            // Bildgrößen anpassen und speichern
            $sizes = [
                's' => 400,
                'm' => 850,
                'l' => 2000,
            ];
            
            foreach ($sizes as $suffix => $width) {
                $path = "site-images/products/{$baseName}_{$suffix}.webp";
                $storageLink = $this->resizeAndConvertToJpg($imageDecoded, $path, $width); // Größe anpassen und speichern
            }
    
            // Aktualisiere das Bild im Array am angegebenen Index
            $this->uploadedImages[$index] = url($storageLink);
            

        } 
    }
    
    private function validateImage($image)
    {
        if (is_object($image)) {
            $maxSize = 10020; // Maximalgröße in KB
            $validMimeTypes = ['image/jpeg', 'image/png', 'image/gif'];
    
            if ($image->getSize() > $maxSize * 1024) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'uploadedImages' => "Ein Bild darf maximal {$maxSize} KB groß sein.",
                ]);
            }
    
            if (!in_array($image->getMimeType(), $validMimeTypes)) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'uploadedImages' => "Ein Bild muss eines der Formate JPEG, PNG oder GIF haben.",
                ]);
            }
        } elseif (!is_string($image)) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'uploadedImages' => "Ungültiger Bildtyp. Bilder müssen Dateien oder URLs sein.",
            ]);
        }
    }

    private function resizeAndConvertToJpg($image, $path, $size)
    {
        try {
            if ($image instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile) {
                $img = Image::read($image); 
            } elseif (isset($image['imageBase64'])) {
                $imageData = substr($image['imageBase64'], strpos($image['imageBase64'], ',') + 1);
                $decodedImage = base64_decode($imageData);
                if ($decodedImage === false) {
                    throw new \Exception("Fehler: Das Bild konnte nicht dekodiert werden.");
                }
                $img = Image::read($decodedImage); 
            } elseif (is_string($image) && Str::contains($image, '/storage/temp/')) {
                $relativePath = str_replace('/storage/', '', parse_url($image, PHP_URL_PATH));
                $absolutePath = storage_path('app/public/' . $relativePath);
                if (!file_exists($absolutePath)) {
                    throw new \Exception("Datei existiert nicht: " . $absolutePath);
                }
                $img = Image::read($absolutePath);
            } else {
                $img = Image::read($image); 
            }
            // Berechne das Seitenverhältnis
            $aspectRatio = $img->width() / $img->height();
            // Berechne neue Dimensionen basierend auf der maximalen Größe
            $newWidth = $size;
            $newHeight = $size / $aspectRatio;
            // Wenn die neue Höhe größer ist als das maximale Limit, passe die Breite an
            if ($newHeight > $size) {
                $newHeight = $size;
                $newWidth = $size * $aspectRatio;
            }
            // Skaliere das Bild proportional
            $img->resize($newWidth, $newHeight);
            // Konvertieren und Speichern
            $encodedImage = $img->encodeByMediaType('image/webp', true, 85);
            // Speichern
            $stored = Storage::disk('public')->put($path, $encodedImage);
            $storagelink = 'storage/'.$path;
            if ($stored) {
                return $storagelink; 
            } else {
                throw new \Exception("Bild konnte nicht gespeichert werden.");
            }
        } catch (\Exception $e) {
            \Log::error('Fehler beim Speichern des Bildes: ' . $e->getMessage());
            throw $e; 
        }
    }

    public function updatedImages()
    {
        foreach ($this->images as $image) {
            if (is_object($image)) { 
                $relativePath = 'temp/' . Auth::id() . '_' . ($this->productId ?? 'new') . '_' . time() . '.webp';

                $this->uploadedImages[] = asset($this->resizeAndConvertToJpg($image, $relativePath, 600));
            } else {
                // Bereits gespeicherte URLs
                $this->uploadedImages[] = $image;
            }
        }
        $this->dispatch('refresh');
        $this->images = null;
    }
    
    public function removeImage($index)
    {
        unset($this->uploadedImages[$index]);
        $this->uploadedImages = array_values($this->uploadedImages); 
        //$this->dispatch('refresh');
    }

    public function render()
    {
        return view('livewire.product-manager')->layout("layouts.app");
    }
}
