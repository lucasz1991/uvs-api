<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Location;
use App\Models\RetailSpace;
use App\Models\Shelve;
use Livewire\WithFileUploads;

class LocationList extends Component
{
    use WithFileUploads;

    public $locations;
    public $newLocation;
    public $newSalesArea;
    public $editingRetailSpace;
    public $RetailAreaElements;
    public $newRetailAreaElement;
    
    public Location $selectedLocation;
    public $selectedLocationId;
    public $editingSalesAreas;
    public RetailSpace $selectedRetailSpace;
    public $selectedRetailSpaceId;
    public $totalShelves;
    public $selectedRetailSpaceEditorInit;
    // Variablen zum öffnen der Modale
    public $showAddLocationModal = false;
    public $showAddSalesAreaModal = false;
    public $showEditRetailSpaceModal = false;
    public $showNewRetailAreaElementForm = false;

    public $backgroundImage;

    protected $listeners = [
        'refreshComponent' => '$refresh',
        'saveRetailSpaceLayout'
    ];

    public function mount()
    {
        $this->locations = Location::all();
        $this->editingSalesAreas = [];
        $this->selectedRetailSpaceEditorInit= false;
        $this->newLocation = [
            'name' => '',
            'address' => '',
            'city' => '',
            'state' => '',
            'postal_code' => '',
            'country' => '',
            'phone_number' => '',
        ];
        $this->newSalesArea = [
            'name' => '',
            'layout' => '',
        ];
        $this->newRetailAreaElement = [
            'name' => '',
            'type' => '',
            'width' => '',
            'height' => '',
            'color' => '#ffffff'
        ];
        $this->RetailAreaElements = [
            ['name' => 'Regal Horizontal', 'type' => 'shelf', 'width' => 90, 'height' => 50, 'color' => '#09bf11', 'shape' => 'rect'],
            ['name' => 'Regal Vertikal', 'type' => 'shelf', 'width' => 50, 'height' => 90, 'color' => '#09bf11', 'shape' => 'rect'],
            ['name' => 'Kasse', 'type' => 'other', 'width' => 200, 'height' => 60, 'color' => '#b3b3b3', 'shape' => 'rect'],
            ['name' => 'Eingang', 'type' => 'other', 'width' => 100, 'height' => 50, 'color' => '#b3b3b3', 'shape' => 'rect'],
        ];
    }

    public function addLocation()
    {
        $this->validate([
            'newLocation.name' => 'required|string|max:255',
            'newLocation.address' => 'required|string|max:255',
            'newLocation.city' => 'required|string|max:255',
            'newLocation.state' => 'required|string|max:255',
            'newLocation.postal_code' => 'required|string|max:20',
            'newLocation.country' => 'required|string|max:255',
            'newLocation.phone_number' => 'required|string|max:20',
        ]);
    
        // Fügt den neuen Standort zur Datenbank hinzu
        Location::create($this->newLocation);
    
        // Aktualisiert die Liste aller Standorte
        $this->locations = Location::all();
    
        // Setzt das Modal zurück
        $this->showAddLocationModal = false;
        
        // Setzt das Formular zurück
        $this->newLocation = [
            'name' => '',
            'address' => '',
            'city' => '',
            'state' => '',
            'postal_code' => '',
            'country' => '',
            'phone_number' => '',
        ];
    
        // Benachrichtigung anzeigen
        $this->dispatch('showAlert', 'Standort erfolgreich gespeichert.', 'success');
    }



    public function createSalesArea()
    {
        // Validierung der Eingaben
            $this->validate([
                'newSalesArea.name' => 'required|string|max:255',
                'newSalesArea.width' => 'required|numeric|min:0',
                'newSalesArea.height' => 'required|numeric|min:0',
            ]);
    
           // Standard-Matrix erstellen
            $matrix = [
                'dimensions' => [
                    'width' => $this->newSalesArea['width'], // Breite aus Eingabe
                    'height' => $this->newSalesArea['height'], // Höhe aus Eingabe
                ],
                'backgroundimg' => [
                    'url' => '',
                    'size' => 'contain',
                ],
                'elements' => [
                    'others' => array(),
                    'shelves' => array(),
                ]
            ];

        // Verkaufsfläche für die ausgewählte Location erstellen
        if ($this->selectedLocation) {
            RetailSpace::create([
                'name' => $this->newSalesArea['name'],
                'layout' => $matrix, // Die Matrix wird hier direkt gespeichert
                'location_id' => $this->selectedLocation->id,
            ]);

            // Verkaufsflächen für die ausgewählte Location aktualisieren
            $this->editingSalesAreas = RetailSpace::where('location_id', $this->selectedLocation->id)->get();

            // Modal schließen und Formular zurücksetzen
            $this->showAddSalesAreaModal = false;

            $this->newSalesArea = ['name' => '', 'layout' => '', 'width' => null, 'height' => null];
        }

        // Erfolgsmeldung anzeigen
        $this->dispatch('showAlert', 'Verkaufsfläche erfolgreich gespeichert.', 'success');
    }
    

    public function selectLocation($locationId)
    {
        // Findet die ausgewählte Location
        $this->selectedLocation = Location::find($locationId);
        $this->selectedLocationId = $this->selectedLocation->id;
        // selectedRetailSpaceId auf null damit Verkaufsfläche Bearbeiten (Formular) sich schliesst
        $this->selectedRetailSpaceId = null;
        
            if ($this->selectedLocation) {
                // Lädt die Verkaufsflächen direkt aus der Beziehung
                $this->editingSalesAreas = RetailSpace::where('location_id', $this->selectedLocation->id)->get();
            } else {
                $this->editingSalesAreas = collect(); // Leere Sammlung, falls keine Location gefunden wird
            }
        $showAddSalesAreaForm = false;
    }

    public function selectRetailSpace($retailSpaceId)
    {
        // Findet die ausgewählte Verkaufsfläche
        $this->selectedRetailSpace = RetailSpace::find($retailSpaceId);
        $this->selectedRetailSpaceId = $this->selectedRetailSpace ? $this->selectedRetailSpace->id : null;
        $this->totalShelves = Shelve::where('retail_space_id', $this->selectedRetailSpaceId)->count();
        if ($this->selectedRetailSpace) {
            // Setzt die Bearbeitungsdaten für die ausgewählte Verkaufsfläche

            $this->editingRetailSpace = $this->selectedRetailSpace->layout;

        }
        
        // Schließt das Formular zum Hinzufügen einer neuen Verkaufsfläche, falls es geöffnet ist
        $this->showAddSalesAreaForm = false;
    }

    public function saveRetailSpaceLayout()
    {
        // Annahme: $this->editingRetailSpace enthält das Layout als Array
        // Validierung der Layout-Daten
        $this->validate(
            [
                'editingRetailSpace' => 'required|array',
                'editingRetailSpace.dimensions.width' => 'required|numeric',
                'editingRetailSpace.dimensions.height' => 'required|numeric',
                'editingRetailSpace.elements.shelves' => 'array',
                'editingRetailSpace.elements.others' => 'array',
            ],
            [
                'editingRetailSpace.required' => 'Das Layout der Verkaufsfläche ist erforderlich.',
                'editingRetailSpace.array' => 'Das Layout muss ein gültiges Array sein.',
                'editingRetailSpace.dimensions.width.required' => 'Die Breite der Verkaufsfläche ist erforderlich.',
                'editingRetailSpace.dimensions.width.numeric' => 'Die Breite muss eine Zahl sein.',
                'editingRetailSpace.dimensions.height.required' => 'Die Höhe der Verkaufsfläche ist erforderlich.',
                'editingRetailSpace.dimensions.height.numeric' => 'Die Höhe muss eine Zahl sein.',
                'editingRetailSpace.elements.shelves.array' => 'Die Regale müssen in einem gültigen Array angegeben werden.',
                'editingRetailSpace.elements.others.array' => 'Andere Elemente müssen in einem gültigen Array angegeben werden.',
            ]
        );
    
        // Verkaufsfläche aus der Datenbank abrufen
        $retailSpace = $this->selectedRetailSpace;
        if ($retailSpace) {
            try {
                $shelvesData = $this->editingRetailSpace['elements']['shelves'];
                
                foreach ($shelvesData as &$shelf) {
                    //dd($shelf);
                    // Erstelle oder aktualisiere das Regal in der Datenbank
                    $shelve = Shelve::updateOrCreate(
                        [
                            'id' => $shelf['element_id'],
                            'shelve_id' => $shelf['element_id'],
                            'retail_space_id' => $retailSpace->id,
                        ],
                        [
                            'shelve_type_id' => 1,
                            'position_x' => $shelf['x'],
                            'position_y' => $shelf['y'],
                            'floor_number' => $shelf['text'],
                        ]
                    );
                        

                        // Layout innerhalb der Schleife direkt aktualisieren
                        $layout['elements']['shelves'] = $shelvesData;
                        
                        // Layout nach jeder Änderung in JSON umwandeln und speichern
                        $retailSpace->layout = json_encode($layout);
                        $retailSpace->save();
                }
    
                // Speichere das aktualisierte Layout in der Verkaufsfläche
                $this->editingRetailSpace['elements']['shelves'] = $shelvesData;
    
                // Speichern des aktualisierten Layouts in der Datenbank
                $retailSpace->update(['layout' => $this->editingRetailSpace]);
    
                // Erfolgsmeldung anzeigen
                session()->flash('message', 'Verkaufsfläche und Regale erfolgreich gespeichert.');
                $this->dispatch('showAlert', 'Verkaufsfläche und Regale erfolgreich gespeichert.', 'success');
    
                // Modal schließen und Formular zurücksetzen
                $this->showEditRetailSpaceModal = false;
            } catch (\Exception $e) {
                // Fehlerbehandlung bei fehlerhafter Speicherung
                session()->flash('error', 'Es gab einen Fehler beim Speichern der Verkaufsfläche.');
                $this->dispatch('showAlert', 'Es gab einen Fehler beim Speichern der Verkaufsfläche.', 'error');
            }
        } else {
            // Verkaufsfläche nicht gefunden
            session()->flash('error', 'Verkaufsfläche konnte nicht gefunden werden.');
            $this->dispatch('showAlert', 'Verkaufsfläche konnte nicht gefunden werden.', 'error');
        }
    }
    
    
    public function uploadBackgroundImage()
    {
        // Validierung
        $this->validate([
            'backgroundImage' => 'required|image|max:1024', // Maximal 1MB
        ]);

        // Datei speichern
        $filePath = $this->backgroundImage->store('backgrounds', 'public'); // Speicherort: storage/app/public/backgrounds

        // Hintergrundbild-URL aktualisieren
        $this->editingRetailSpace['backgroundimg']['url'] = asset('storage/' . $filePath);

        // Speichern in der Datenbank
        $retailSpace = RetailSpace::find($this->selectedRetailSpaceId);
        if ($retailSpace) {
            $retailSpace->update([
                'layout' => $this->editingRetailSpace,
            ]);
            $this->dispatch('showAlert', 'Hintergrundbild erfolgreich hochgeladen.', 'success');
        } else {
            $this->dispatch('showAlert', 'Verkaufsfläche konnte nicht gefunden werden.', 'error');
        }
    }


    public function addElementToLayout($Layouttype, $x, $y, $width, $height, $elementId = null, $text = null, $color = null)
    {
        // Eingabewerte validieren
        if (!is_numeric($x) || !is_numeric($y) || !is_numeric($width) || !is_numeric($height) || !is_int($Layouttype)) {
            throw new \InvalidArgumentException('Ungültige Werte übergeben.');
        }
    
        // Initialisiere editingRetailSpace, falls es nicht existiert
        if (empty($this->editingRetailSpace)) {
            $this->editingRetailSpace = [
                'dimensions' => ['width' => 0, 'height' => 0],
                'elements' => [
                    'shelves' => [],
                    'others' => [],
                ],
            ];
        } elseif (is_string($this->editingRetailSpace)) {
            $this->editingRetailSpace = json_decode($this->editingRetailSpace, true);
        }
    
        if (!is_array($this->editingRetailSpace)) {
            throw new \RuntimeException('Die Struktur von editingRetailSpace ist ungültig.');
        }
    
        // Referenz auf die Elemente (je nach Typ)
        $elementsKey = $Layouttype === 1 ? 'shelves' : 'others';
        $elements = $this->editingRetailSpace['elements'][$elementsKey];
    
        // Versuche ein vorhandenes Element zu aktualisieren, falls eine ID vorhanden ist
        if ($elementId) {
            foreach ($elements as &$element) {
                if (isset($element['element_id']) && $element['element_id'] === $elementId) {
                    $element['x'] = $x;
                    $element['y'] = $y;
                    $element['width'] = $width;
                    $element['height'] = $height;
                    if ($text !== null) {
                        $element['text'] = $text;
                    }
                    if ($color !== null) {
                        $element['color'] = $color;
                    }
    
                    // Aktualisierte Elemente zurücksetzen
                    $this->editingRetailSpace['elements'][$elementsKey] = $elements;
                    return; // Element wurde aktualisiert, Funktion verlassen
                }
            }
        }
    
        // Füge ein neues Element hinzu (entweder weil keine ID vorhanden ist oder kein passendes Element gefunden wurde)
        $newElement = [
            'element_id' => uniqid(true),
            'x' => $x,
            'y' => $y,
            'width' => $width,
            'height' => $height,
            'text' => $text ?? ($Layouttype === 1 ? 'RF' : 'Eingang'), // Standardtext
            'color' => $color ?? ($Layouttype === 1 ? 'rgb(21 128 61);' : '#e5e7eb'), // Standardfarbe
        ];
    
        $elements[] = $newElement;
    
        // Aktualisierte Elemente zurücksetzen
        $this->editingRetailSpace['elements'][$elementsKey] = $elements;
    
        // Debugging: Ausgabe der neuen Struktur (optional)
        logger()->info('Aktualisiertes Layout:', $this->editingRetailSpace);
    }
    
    
    
    

    public function addRetailAreaElement()
    {
        // Sicherstellen, dass `newRetailAreaElement` korrekt ist
        $this->newRetailAreaElement = array_merge([
            'name' => '',
            'type' => '',
            'width' => '',
            'height' => '',
            'color' => '#ffffff',
        ], $this->newRetailAreaElement);
    
        // Validierung der Eingaben
        $this->validate(
            [
                'newRetailAreaElement.name' => 'required|string|max:255',
                'newRetailAreaElement.type' => 'required|string',
                'newRetailAreaElement.width' => 'required|numeric|min:1',
                'newRetailAreaElement.height' => 'required|numeric|min:1',
                'newRetailAreaElement.color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            ],
            [
                'newRetailAreaElement.name.required' => 'Der Name des Flächenelements ist erforderlich.',
                'newRetailAreaElement.name.string' => 'Der Name muss eine Zeichenkette sein.',
                'newRetailAreaElement.name.max' => 'Der Name darf maximal 255 Zeichen lang sein.',
        
                'newRetailAreaElement.type.required' => 'Der Typ des Flächenelements ist erforderlich.',
                'newRetailAreaElement.type.string' => 'Der Typ muss eine Zeichenkette sein.',
        
                'newRetailAreaElement.width.required' => 'Die Breite des Flächenelements ist erforderlich.',
                'newRetailAreaElement.width.numeric' => 'Die Breite muss eine Zahl sein.',
                'newRetailAreaElement.width.min' => 'Die Breite muss mindestens 1 betragen.',
        
                'newRetailAreaElement.height.required' => 'Die Höhe des Flächenelements ist erforderlich.',
                'newRetailAreaElement.height.numeric' => 'Die Höhe muss eine Zahl sein.',
                'newRetailAreaElement.height.min' => 'Die Höhe muss mindestens 1 betragen.',
        
                'newRetailAreaElement.color.required' => 'Die Farbe des Flächenelements ist erforderlich.',
                'newRetailAreaElement.color.string' => 'Die Farbe muss als Zeichenkette angegeben werden.',
                'newRetailAreaElement.color.regex' => 'Die Farbe muss im Hexadezimalformat angegeben werden (z. B. #ffffff).',
            ]
        );
    
        // Hinzufügen des neuen Elements
        $this->RetailAreaElements[] = $this->newRetailAreaElement;
    
        // Zurücksetzen des Formulars
        $this->newRetailAreaElement = [
            'name' => '',
            'type' => '',
            'width' => '',
            'height' => '',
            'color' => '#ffffff',
        ];
        // Erfolgsmeldung anzeigen
        session()->flash('message', 'Verkaufsflächen Element erfolgreich gespeichert.');
    }

    public function render()
    {
        return view('livewire.location-list')->layout('layouts.master');
    }


}
