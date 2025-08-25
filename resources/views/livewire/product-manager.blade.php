

<div class="w-full relative bg-cover bg-center backgroundimageOverlay bg-[#f8f2e8f2] py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 bg-white shadow-lg rounded-lg p-5">
        <div class="relative" wire:loading.class="cursor-wait">

            <!-- Ladeindikator -->
            <div 
                wire:loading 
                x-cloak
                class="absolute inset-0 flex items-center justify-center bg-white bg-opacity-70 rounded-lg z-10 cursor-wait">
                <div class="loader"></div> <!-- Ladeanimation -->
            </div>


            <div>

                    <x-back-button />
                    <form wire:submit.prevent="saveProduct" enctype="multipart/form-data" class=" mt-5">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">
                        {{ $productId ? 'Produkt bearbeiten' : 'Neues Produkt hinzufügen' }}
                    </h2>  
                    <!-- Erfolgsmeldung -->
                    @if (session()->has('success'))
                        <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif
                    <!-- Fehlerausgabe -->
                    @if ($errors->any())
                        <div class="bg-red-100 text-red-800 px-4 py-2 rounded mb-4">
                            <ul class="list-disc pl-5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif 
                    <div class="lg:grid lg:grid-cols-2 lg:gap-8 xl:gap-16 ">
                        <div class="shrink-0 max-w-md lg:max-w-lg">
                            <script src="/adminresources/js/imageeditor.js"></script>
                            <div class="relative" x-data="{
                                editModalOpen: false,
                                imageUrl: null,
                                editorInstance: null,
                                imageIndex: 0,  // Speichert den Index des bearbeiteten Bildes
                                openEditor(imageUrl, index){
                                    this.imageUrl = imageUrl;  // Setze die Bild-URL
                                    this.imageIndex = index;  // Setze den Index des Bildes, das bearbeitet wird
                                    // Lade das Bild und skalier es
                                    this.scaleImage(this.imageUrl, (scaledImageUrl) => {
                                        const container = document.getElementById('imageEditor');
                                        const { TABS, TOOLS } = FilerobotImageEditor;
                                        const config = {
                                            source: scaledImageUrl, // Verwende das skalierte Bild
                                            onSave: (editedImageObject, designState) => {
                                                const imageData = editedImageObject; // Base64-Daten des bearbeiteten Bildes
                                                // Livewire-Funktion aufrufen, um das bearbeitete Bild zu speichern
                                                @this.updateImage(imageData, this.imageIndex);
                                                this.editModalOpen = false;  // Schließe das Modal
                                            },
                                            tabsIds: [TABS.ADJUST, TABS.FINETUNE, TABS.FILTERS], // Nur diese Tabs aktivieren
                                            defaultTabId: TABS.ADJUST, 
                                            defaultToolId: TOOLS.BRIGHTNESS, 
                                            annotationsCommon: {
                                                fill: '#ff0000'
                                            },
                                            language: 'de',
                                            defaultSavedImageType: 'webp',
                                            defaultSavedImageName: 'default.webp',
                                            defaultSavedImageQuality: 1,
                                            closeAfterSave: true,
                                            hideHeaderBar: true,
                                        };
                                        // Initialisiere den Filerobot Image Editor
                                        this.editorInstance = new FilerobotImageEditor(container, config);
                                        this.editModalOpen = true;
                                        // Render den Editor
                                        this.editorInstance.render({
                                            onClose: (closingReason) => {
                                                this.editorInstance.terminate();
                                                this.editModalOpen = false;
                                            }
                                        });
                                    });
                                },
                                // Funktion zum Skalieren des Bildes mit Beibehaltung des Seitenverhältnisses
                                scaleImage(imageUrl, callback) {
                                    const img = new Image();
                                    img.src = imageUrl;
                                    img.onload = () => {
                                        const MAX_WIDTH = 1080;
                                        const MAX_HEIGHT = 1080;
                                        let canvas = document.createElement('canvas');
                                        let ctx = canvas.getContext('2d');
                                        let scaleFactor = 1;
                                        let newWidth = img.width;
                                        let newHeight = img.height;
                                        // Berechne den Skalierungsfaktor basierend auf den maximalen Dimensionen
                                        if (img.width > MAX_WIDTH || img.height > MAX_HEIGHT) {
                                            const widthScale = MAX_WIDTH / img.width;
                                            const heightScale = MAX_HEIGHT / img.height; 
                                            // Wähle den kleineren Skalierungsfaktor, um das Bild innerhalb der maximalen Dimensionen zu halten
                                            scaleFactor = Math.min(widthScale, heightScale);
                                            // Berechne die neuen Dimensionen unter Beibehaltung des Seitenverhältnisses
                                            newWidth = img.width * scaleFactor;
                                            newHeight = img.height * scaleFactor;
                                        }
                                        // Setze die neuen Dimensionen für das Canvas
                                        canvas.width = newWidth;
                                        canvas.height = newHeight;
                                        // Zeichne das Bild auf das Canvas, um es zu skalieren
                                        ctx.drawImage(img, 0, 0, newWidth, newHeight);
                                        // Konvertiere das Canvas zurück in eine Base64-URL
                                        const scaledImageUrl = canvas.toDataURL('image/webp'); // Du kannst hier auch andere Formate verwenden
                                        // Callback mit der skalierten Bild-URL
                                        callback(scaledImageUrl);
                                    };
                                }
                            }">
                                <input
                                    type="file"
                                    accept="image/jpeg, image/png, image/gif"
                                    id="images"
                                    wire:model="images"
                                    multiple
                                    class="hidden"
                                />
                                <label for="images" class=" w-full  cursor-pointer " >
                                    <!-- Zeigt das Hauptbild an, wenn eines gesetzt wurde -->
                                    @if (count($uploadedImages) > 0)
                                        <div class="relative w-full h-full" onclick="event.preventDefault()" @touchstart="hover = true;" @click.away="hover = false"  x-data="{ hover: false }" @mouseenter="hover = true" @mouseleave="hover = false">
                                            <div class="w-full aspect-square overflow-hidden shadow-lg rounded-lg " >
                                                <img
                                                    class="w-full h-full  object-cover "
                                                    src="{{ is_object($uploadedImages[0]) ? $uploadedImages[0]->temporaryUrl() : $uploadedImages[0] }}"
                                                    alt=""
                                                />
                                            </div>
                                            <!-- Menü mit Buttons, das nur bei Hover angezeigt wird -->
                                            <div  x-cloak
                                                class="absolute top-0 right-0 w-full h-full flex justify-center items-center p-2 transition-opacity duration-300" 
                                                :class="{'opacity-100': hover, 'opacity-0': !hover}" 
                                                style="background-color:#fbfbfbcf"
                                                >
                                                <div class="space-y-2 text-center">
                                                    <!-- Image edit  Button -->
                                                    <button @click="openEditor('{{ is_object($uploadedImages[0]) ? $uploadedImages[0]->temporaryUrl() : $uploadedImages[0] }}', 0)" :disabled="!hover"  class="bg-gray-600 text-white py-2 px-4  rounded-full shadow-md my-2 hover:bg-green-500 flex items-center space-x-3">
                                                        <svg class="w-4 h-4 " fill="currentColor" stroke="currentColor" stroke-width="2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M448 80c8.8 0 16 7.2 16 16l0 319.8-5-6.5-136-176c-4.5-5.9-11.6-9.3-19-9.3s-14.4 3.4-19 9.3L202 340.7l-30.5-42.7C167 291.7 159.8 288 152 288s-15 3.7-19.5 10.1l-80 112L48 416.3l0-.3L48 96c0-8.8 7.2-16 16-16l384 0zM64 32C28.7 32 0 60.7 0 96L0 416c0 35.3 28.7 64 64 64l384 0c35.3 0 64-28.7 64-64l0-320c0-35.3-28.7-64-64-64L64 32zm80 192a48 48 0 1 0 0-96 48 48 0 1 0 0 96z"/></svg>
                                                        <span>Bearbeiten</span>
                                                    </button>
                                                    <!-- Löschen Button -->
                                                    <button wire:click="removeImage(0)" :disabled="!hover"  class="bg-red-400 text-white py-2 px-4 rounded-full shadow-md mt-2 hover:bg-red-500 flex items-center space-x-3">
                                                        <!-- SVG für das Löschen Icon -->
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                                                        </svg>
                                                        <span>Löschen</span>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="w-full  cursor-pointer  aspect-square bg-gray-100 border-2 border-gray-300 @error('uploadedImages') border-red-400  @enderror transition-all duration-100  hover:border-blue-400 hover:fill-blue-400 hover:text-blue-400 rounded-lg flex items-center justify-center text-gray-600">
                                            <div class="p-4 text-center">
                                                <svg class="opacity-50 w-2/4 mx-auto" stroke="currentColor" fill="currentColor" xmlns="http://www.w3.org/2000/svg" id="Layer_1" data-name="Layer 1" viewBox="0 0 110 110"><path d="M69.23,92.3H13.73a1,1,0,0,1-1-1v-48a1,1,0,0,1,1-1H74.18a1,1,0,0,1,1,1V72.83a1,1,0,0,1-.51.87,12.07,12.07,0,0,0-2,1.43A12.52,12.52,0,0,0,70.09,90.8a1,1,0,0,1-.86,1.5Zm-54.5-2H67.6a14.52,14.52,0,0,1,3.75-16.67,14.78,14.78,0,0,1,1.83-1.37v-28H14.73Z"/><path d="M40.55,61.87a6.71,6.71,0,1,1,6.71-6.71A6.72,6.72,0,0,1,40.55,61.87Zm0-11.41a4.71,4.71,0,1,0,4.71,4.7A4.71,4.71,0,0,0,40.55,50.46Z"/><path d="M69.23,92.3H13.73a1,1,0,0,1-1-1V80.4a1,1,0,0,1,.29-.71L26.35,66.36a4.27,4.27,0,0,1,6,0L42,76,55.54,62.49a4.27,4.27,0,0,1,6,0L72.72,73.67a1,1,0,0,1,.29.74,1,1,0,0,1-.34.72A12.52,12.52,0,0,0,70.09,90.8a1,1,0,0,1-.86,1.5Zm-54.5-2H67.6a14.52,14.52,0,0,1,3-15.94L60.13,63.91a2.26,2.26,0,0,0-3.18,0L42.72,78.15a1.05,1.05,0,0,1-.71.29h0a1,1,0,0,1-.71-.29L30.93,67.78a2.25,2.25,0,0,0-3.17,0l-13,13Z"/><path d="M46.64,83.07a1,1,0,0,1-.71-.29l-4.62-4.63a1,1,0,0,1,0-1.41,1,1,0,0,1,1.41,0l4.63,4.62a1,1,0,0,1,0,1.42A1,1,0,0,1,46.64,83.07Z"/><path d="M80.91,99H7a1,1,0,0,1-1-1V36.51a1,1,0,0,1,1-1H80.91a1,1,0,0,1,1,1V71a1,1,0,0,1-1,1,12.52,12.52,0,0,0-6.23,1.66,12.38,12.38,0,0,0-2,1.44A12.5,12.5,0,0,0,80.91,97a1,1,0,0,1,0,2ZM8,97H73.58a14.49,14.49,0,0,1-2.23-23.4A14.14,14.14,0,0,1,73.69,72a14.39,14.39,0,0,1,6.22-1.9V37.51H8Z"/><path d="M90.19,75.72a1,1,0,0,1-.69-.27A12.39,12.39,0,0,0,80.91,72a1,1,0,0,1-1-1V37.51H15.25a1,1,0,0,1-1-.71l-.9-3a1,1,0,0,1,.67-1.25L84.76,11a1,1,0,0,1,.76.08,1,1,0,0,1,.49.59L104,70.52a1,1,0,0,1-.67,1.25L90.48,75.68A.92.92,0,0,1,90.19,75.72Zm-8.28-5.66a14.39,14.39,0,0,1,8.53,3.54l11.31-3.45L84.39,13.22l-68.79,21,.39,1.3H80.91a1,1,0,0,1,1,1Z"/><path d="M80.91,99a14.5,14.5,0,0,1-9.56-25.4A14.14,14.14,0,0,1,73.69,72,14.5,14.5,0,1,1,80.91,99Zm0-27a12.52,12.52,0,0,0-6.23,1.66,12.38,12.38,0,0,0-2,1.44,12.5,12.5,0,1,0,20.74,9.4A12.5,12.5,0,0,0,80.91,72Z"/><path d="M80.91,92.45a1,1,0,0,1-1-1V77.61a1,1,0,1,1,2,0V91.45A1,1,0,0,1,80.91,92.45Z"/><path d="M87.83,85.53H74a1,1,0,1,1,0-2H87.83a1,1,0,1,1,0,2Z"/></svg>
                                                Klicken Sie hier, um ein oder mehrere Bilder hochzuladen
                                            </div>
                                        </div>
                                    @endif
                                    <!-- Weitere Bilder -->
                                    <div class="mt-4 w-max">
                                        <div class="flex gap-4">
                                        @for ($i = 1; $i <= 4; $i++)
                                            <div class="relative w-16 bg-gray-100 transition-all duration-100  aspect-square border-2 border-gray-300 hover:border-blue-400 hover:fill-blue-400 hover:text-blue-400 rounded-lg flex items-center justify-center">
                                                @if (isset($uploadedImages[$i]))  
                                                    <div x-data="{ open: false }" class="relative w-full aspect-square  shadow-lg rounded-lg ">
                                                        <img  onclick="event.preventDefault()" @click="open = !open" @touchstart="event.preventDefault();open = !open" @click="open = !open"
                                                            class="w-full h-full object-cover rounded-lg  transition-all duration-100"
                                                            src="{{ is_string($uploadedImages[$i]) ? $uploadedImages[$i] : $uploadedImages[$i]->temporaryUrl() }}"  
                                                        />
                                                        <!-- Dropdown-Menü -->
                                                        <div 
                                                            x-show="open" 
                                                            x-transition 
                                                            @click.away="open = false" 
                                                            class="absolute left-0 mt-2 w-40 bg-white border rounded-lg shadow-lg z-10"
                                                            >
                                                            <!-- Editieren Button -->
                                                            <button 
                                                                type="button"
                                                                @click="openEditor('{{ is_object($uploadedImages[$i]) ? $uploadedImages[$i]->temporaryUrl() : $uploadedImages[$i] }}', {{$i}})"
                                                                class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                                            >
                                                            Bearbeiten
                                                            </button>
                                                            <!-- Löschen Button -->
                                                            <button 
                                                                type="button"
                                                                wire:click="removeImage({{ $i }})"
                                                                class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-100"
                                                            >
                                                                Löschen
                                                            </button>
                                                        </div>
                                                    </div>
                                                @else
                                                    <span  class="text-gray-500 cursor-pointer">
                                                            <svg class="opacity-50 w-2/4 mx-auto aspect-square" stroke="currentColor" fill="currentColor" xmlns="http://www.w3.org/2000/svg" id="Layer_1" data-name="Layer 1" viewBox="0 0 110 110"><path d="M69.23,92.3H13.73a1,1,0,0,1-1-1v-48a1,1,0,0,1,1-1H74.18a1,1,0,0,1,1,1V72.83a1,1,0,0,1-.51.87,12.07,12.07,0,0,0-2,1.43A12.52,12.52,0,0,0,70.09,90.8a1,1,0,0,1-.86,1.5Zm-54.5-2H67.6a14.52,14.52,0,0,1,3.75-16.67,14.78,14.78,0,0,1,1.83-1.37v-28H14.73Z"/><path d="M40.55,61.87a6.71,6.71,0,1,1,6.71-6.71A6.72,6.72,0,0,1,40.55,61.87Zm0-11.41a4.71,4.71,0,1,0,4.71,4.7A4.71,4.71,0,0,0,40.55,50.46Z"/><path d="M69.23,92.3H13.73a1,1,0,0,1-1-1V80.4a1,1,0,0,1,.29-.71L26.35,66.36a4.27,4.27,0,0,1,6,0L42,76,55.54,62.49a4.27,4.27,0,0,1,6,0L72.72,73.67a1,1,0,0,1,.29.74,1,1,0,0,1-.34.72A12.52,12.52,0,0,0,70.09,90.8a1,1,0,0,1-.86,1.5Zm-54.5-2H67.6a14.52,14.52,0,0,1,3-15.94L60.13,63.91a2.26,2.26,0,0,0-3.18,0L42.72,78.15a1.05,1.05,0,0,1-.71.29h0a1,1,0,0,1-.71-.29L30.93,67.78a2.25,2.25,0,0,0-3.17,0l-13,13Z"/><path d="M46.64,83.07a1,1,0,0,1-.71-.29l-4.62-4.63a1,1,0,0,1,0-1.41,1,1,0,0,1,1.41,0l4.63,4.62a1,1,0,0,1,0,1.42A1,1,0,0,1,46.64,83.07Z"/><path d="M80.91,99H7a1,1,0,0,1-1-1V36.51a1,1,0,0,1,1-1H80.91a1,1,0,0,1,1,1V71a1,1,0,0,1-1,1,12.52,12.52,0,0,0-6.23,1.66,12.38,12.38,0,0,0-2,1.44A12.5,12.5,0,0,0,80.91,97a1,1,0,0,1,0,2ZM8,97H73.58a14.49,14.49,0,0,1-2.23-23.4A14.14,14.14,0,0,1,73.69,72a14.39,14.39,0,0,1,6.22-1.9V37.51H8Z"/><path d="M90.19,75.72a1,1,0,0,1-.69-.27A12.39,12.39,0,0,0,80.91,72a1,1,0,0,1-1-1V37.51H15.25a1,1,0,0,1-1-.71l-.9-3a1,1,0,0,1,.67-1.25L84.76,11a1,1,0,0,1,.76.08,1,1,0,0,1,.49.59L104,70.52a1,1,0,0,1-.67,1.25L90.48,75.68A.92.92,0,0,1,90.19,75.72Zm-8.28-5.66a14.39,14.39,0,0,1,8.53,3.54l11.31-3.45L84.39,13.22l-68.79,21,.39,1.3H80.91a1,1,0,0,1,1,1Z"/><path d="M80.91,99a14.5,14.5,0,0,1-9.56-25.4A14.14,14.14,0,0,1,73.69,72,14.5,14.5,0,1,1,80.91,99Zm0-27a12.52,12.52,0,0,0-6.23,1.66,12.38,12.38,0,0,0-2,1.44,12.5,12.5,0,1,0,20.74,9.4A12.5,12.5,0,0,0,80.91,72Z"/><path d="M80.91,92.45a1,1,0,0,1-1-1V77.61a1,1,0,1,1,2,0V91.45A1,1,0,0,1,80.91,92.45Z"/><path d="M87.83,85.53H74a1,1,0,1,1,0-2H87.83a1,1,0,1,1,0,2Z"/></svg>
                                                    </span>
                                                @endif
                                            </div>
                                        @endfor
                                    </div>
                                </label>
                                <!-- Dynamische Anzeige der Bildanzahl -->
                                <div class="mt-4">
                                    <span class="text-gray-500">
                                        {{ count($uploadedImages) }} von maximal 5 Bildern hochgeladen
                                    </span>
                                </div>
                                @error('uploadedImages') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

                                <!-- Modal für den Filerobot Editor -->
                                <div x-show="editModalOpen" x-cloak wire:ignore onclick="event.preventDefault()" x-effect="document.body.style.overflow = editModalOpen ? 'hidden' : 'auto'" @keydown.escape="editModalOpen = false"
                                    class="fixed inset-0 flex items-center justify-center z-50 bg-gray-500 bg-opacity-50">
                                    <div class="bg-white p-6 rounded-lg max-w-[90vw]"  @click.away="editModalOpen = false">
                                        <h3 class="text-xl mb-4">Bild bearbeiten</h3>
                                        <!-- Filerobot Image Editor Container -->
                                        <div id="imageEditor" class="mb-4" style="height:70vh;min-width:50vw;"></div>
                                    </div>
                                </div>

                
                
            </div>



        </div>
    </div>
    
    
    <!-- Produktdetails -->
    <div class="mt-6 sm:mt-8 lg:mt-0">
        
            <div class="grid grid-cols-2 gap-4">
                <!-- Produktname -->
                <div x-data="{ tooltipButtonVisible: false }" class="mb-4"
                    @mouseenter="tooltipButtonVisible = true" 
                    @mouseleave="tooltipButtonVisible = false" 
                    @click="tooltipButtonVisible = true"
                    @click.away="tooltipButtonVisible = false">
                    <div x-data="{  tooltipVisible: false }" class="relative inline-block">
                        <label for="name" class="flex items-center justify-center block text-sm font-medium text-gray-700">
                            Produktname
                            <button x-show="tooltipButtonVisible"
                                @mouseenter="tooltipVisible = true" 
                                @mouseleave="tooltipVisible = false" 
                                @click="tooltipVisible = !tooltipVisible"
                                @click.away="tooltipVisible = false"
                                x-transition
                                x-cloak
                                type="button"
                                class="ml-2 w-4 h-4 bg-gray-200 text-[12px] rounded-full flex items-center justify-center text-gray-600 hover:bg-gray-300 focus:outline-none"
                                >
                                ?
                            </button>
                        </label>
                    
                        <!-- Tooltip -->
                        <div 
                            x-show="tooltipVisible" 
                            x-cloak
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 translate-y-1"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 translate-y-1"
                            class="absolute left-0 mt-2 w-64 bg-gray-800 text-white text-xs rounded-md shadow-lg p-2 z-10"
                        >
                        Gib den Produktnamen ein. Dieser wird auch auf dem Produkt Etikett zu sehen sein. Zum Beispiel: "T-Shirt".
                        </div>
                    </div>
                    <input
                        type="text"
                        id="name"
                        wire:model="name"
                        placeholder="Produktname eingeben"
                        wire:ignore
                        class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                    />
                    @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
    
                <!-- Preis -->
                <div x-data="{ tooltipButtonVisible: false }" class="mb-4"
                    @mouseenter="tooltipButtonVisible = true" 
                    @mouseleave="tooltipButtonVisible = false" 
                    @click="tooltipButtonVisible = true"
                    @click.away="tooltipButtonVisible = false">
                    <div x-data="{  tooltipVisible: false }" class="relative inline-block">
                        <label for="price" class="flex items-center justify-center block text-sm font-medium text-gray-700">
                            Preis (€)
                            <button x-show="tooltipButtonVisible"
                                @mouseenter="tooltipVisible = true" 
                                @mouseleave="tooltipVisible = false" 
                                @click="tooltipVisible = !tooltipVisible"
                                @click.away="tooltipVisible = false"
                                x-transition
                                x-cloak
                                type="button"
                                class="ml-2 w-4 h-4 bg-gray-200 text-[12px] rounded-full flex items-center justify-center text-gray-600 hover:bg-gray-300 focus:outline-none"
                                >
                                ?
                            </button>
                        </label>
                    
                        <!-- Tooltip -->
                        <div 
                            x-show="tooltipVisible" 
                            x-cloak
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 translate-y-1"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 translate-y-1"
                            class="absolute left-0 mt-2 w-64 bg-gray-800 text-white text-xs rounded-md shadow-lg p-2 z-10"
                        >
                            Gib den Preis ein. Der Preis ist oft der entscheidende Faktor für Käufer. Daher solltest du sicherstellen, dass dein Preis sowohl attraktiv als auch realistisch ist. <br>
                            <strong>Wichtig</strong>: Bei MiniFinds gehen 16% Provision pro verkauftem Produkt an uns. Diese Provision deckt unsere Leistung ab, dein Produkt optimal zu inserieren und lokal erfolgreich zu verkaufen. Durch unsere Unterstützung wird dein Produkt nicht nur online sichtbar, sondern auch im Markt vor Ort, wo es von vielen Käufern entdeckt werden kann. 
                        </div>
                    </div>
                    <input
                        wire:model.live="price"
                        x-mask:dynamic="$money($input, ',', '')"
                        placeholder="0.00"
                        wire:ignore
                        class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                    />
                    @error('price') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>
    
            <div class="grid grid-cols-2 gap-4">
                <!-- Kategorie -->
                <div wire:ignore x-data="{ tooltipButtonVisible: false }" class="mb-4"
                    @mouseenter="tooltipButtonVisible = true" 
                    @mouseleave="tooltipButtonVisible = false" 
                    @click="tooltipButtonVisible = true"
                    @click.away="tooltipButtonVisible = false">
                    <div x-data="{  tooltipVisible: false }" class="relative inline-block">
                        <label for="category" class="flex items-center justify-center block text-sm font-medium text-gray-700">
                            Kategorie
                            <button x-show="tooltipButtonVisible"
                                @mouseenter="tooltipVisible = true" 
                                @mouseleave="tooltipVisible = false" 
                                @click="tooltipVisible = !tooltipVisible"
                                @click.away="tooltipVisible = false"
                                x-transition
                                x-cloak
                                type="button"
                                class="ml-2 w-4 h-4 bg-gray-200 text-[12px] rounded-full flex items-center justify-center text-gray-600 hover:bg-gray-300 focus:outline-none"
                                >
                                ?
                            </button>
                        </label>
                    
                        <!-- Tooltip -->
                        <div 
                            x-show="tooltipVisible" 
                            x-cloak
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 translate-y-1"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 translate-y-1"
                            class="absolute left-0 mt-2 w-64 bg-gray-800 text-white text-xs rounded-md shadow-lg p-2 z-10"
                        >
                            Gib die Kategorie an. 
                        </div>
                    </div>
                    <select
                        id="category"
                        wire:model.live="category"
                       data-placeholder="Wählen Sie eine Kategorie"
                        class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                    ><option value="" >Wählen Sie eine Kategorie</option>
                        @foreach($allcategories as $onecategory)
                            <option value="{{ $onecategory->slug }}" {{ $onecategory->slug == $category ? 'selected' : '' }}>{{ $onecategory->name }}</option>
                            @foreach($onecategory->children as $child)
                                <option value="{{ $child->slug }}" {{ $child->slug == $category ? 'selected' : '' }}>- {{ $child->name }}</option>
                                @foreach($child->children as $grandchild)
                                    <option value="{{ $grandchild->slug }}" {{ $grandchild->slug == $category ? 'selected' : '' }}>-- {{ $grandchild->name }}</option>
                                @endforeach
                            @endforeach
                        @endforeach
                    </select>
                    @error('category') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Ages -->
                <div  wire:ignore x-data="{ tooltipButtonVisible: false }" class="mb-4"
                    @mouseenter="tooltipButtonVisible = true" 
                    @mouseleave="tooltipButtonVisible = false" 
                    @click="tooltipButtonVisible = true"
                    @click.away="tooltipButtonVisible = false">
                    <div x-data="{  tooltipVisible: false }" class="relative inline-block">
                        <label for="ageRecommendation" class="flex items-center justify-center block text-sm font-medium text-gray-700">
                        Altersgruppe
                            <button x-show="tooltipButtonVisible"
                                @mouseenter="tooltipVisible = true" 
                                @mouseleave="tooltipVisible = false" 
                                @click="tooltipVisible = !tooltipVisible"
                                @click.away="tooltipVisible = false"
                                x-transition
                                x-cloak
                                type="button"
                                class="ml-2 w-4 h-4 bg-gray-200 text-[12px] rounded-full flex items-center justify-center text-gray-600 hover:bg-gray-300 focus:outline-none"
                                >
                                ?
                            </button>
                        </label>
                    
                        <!-- Tooltip -->
                        <div 
                            x-show="tooltipVisible" 
                            x-cloak
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 translate-y-1"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 translate-y-1"
                            class="absolute left-0 mt-2 w-64 bg-gray-800 text-white text-xs rounded-md shadow-lg p-2 z-10"
                        >
                            Gib die Altersgruppe an. 
                        </div>
                    </div>
                    <select 
                        id="ageRecommendation" 
                        wire:model.live="ageRecommendation" 
                        data-placeholder="Alle Altersgruppen"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 "
                    >
                        <option value="0-2 Jahre">0-2 Jahre</option>
                        <option value="3-5 Jahre">3-5 Jahre</option>
                        <option value="6-10 Jahre">6-10 Jahre</option>
                        <option value="11+ Jahre">11+ Jahre</option>
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <!-- Tags -->
                <div  wire:ignore x-data="{ tooltipButtonVisible: false }" class="mb-4"
                        @mouseenter="tooltipButtonVisible = true" 
                        @mouseleave="tooltipButtonVisible = false" 
                        @click="tooltipButtonVisible = true"
                        @click.away="tooltipButtonVisible = false">
                        <div x-data="{  tooltipVisible: false }" class="relative inline-block">
                            <label for="tags" class="flex items-center justify-center block text-sm font-medium text-gray-700">
                            Schlagwörter
                                <button x-show="tooltipButtonVisible"
                                    @mouseenter="tooltipVisible = true" 
                                    @mouseleave="tooltipVisible = false" 
                                    @click="tooltipVisible = !tooltipVisible"
                                    @click.away="tooltipVisible = false"
                                    x-transition
                                    x-cloak
                                    type="button"
                                    class="ml-2 w-4 h-4 bg-gray-200 text-[12px] rounded-full flex items-center justify-center text-gray-600 hover:bg-gray-300 focus:outline-none"
                                    >
                                    ?
                                </button>
                            </label>
                        
                            <!-- Tooltip -->
                            <div 
                                x-show="tooltipVisible" 
                                x-cloak
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 translate-y-1"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100 translate-y-0"
                                x-transition:leave-end="opacity-0 translate-y-1"
                                class="absolute left-0 mt-2 w-64 bg-gray-800 text-white text-xs rounded-md shadow-lg p-2 z-10"
                            >
                                Gib die Schlagwörter an. 
                            </div>
                        </div>
                        <select 
                            id="tags"
                            wire:model.live="tags"
                            multiple
                            data-placeholder="Schlagwörter auswählen"
                            class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        >
                        
                            @foreach($alltags as $tag)
                                <option value="{{ $tag->id }}" @if(in_array($tag->id, $tags)) selected="selected" @endif>{{ $tag->name }}</option>
                            @endforeach
                        </select>
                    @error('tags') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <!-- Produkt Grösse-->
                <div x-data="{ tooltipButtonVisible: false }" class="mb-4"
                    @mouseenter="tooltipButtonVisible = true" 
                    @mouseleave="tooltipButtonVisible = false" 
                    @click="tooltipButtonVisible = true"
                    @click.away="tooltipButtonVisible = false">
                    <div x-data="{  tooltipVisible: false }" class="relative inline-block">
                        <label for="size" class="flex items-center justify-center block text-sm font-medium text-gray-700">
                            Größe
                            <button x-show="tooltipButtonVisible"
                                @mouseenter="tooltipVisible = true" 
                                @mouseleave="tooltipVisible = false" 
                                @click="tooltipVisible = !tooltipVisible"
                                @click.away="tooltipVisible = false"
                                x-transition
                                x-cloak
                                type="button"
                                class="ml-2 w-4 h-4 bg-gray-200 text-[12px] rounded-full flex items-center justify-center text-gray-600 hover:bg-gray-300 focus:outline-none"
                                >
                                ?
                            </button>
                        </label>
                    
                        <!-- Tooltip -->
                        <div 
                            x-show="tooltipVisible" 
                            x-cloak
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 translate-y-1"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 translate-y-1"
                            class="absolute left-0 mt-2 w-64 bg-gray-800 text-white text-xs rounded-md shadow-lg p-2 z-10"
                        >
                        Gib die Produkt Größe an. Diese wird mit auf dem Produkt Etikett zu sehen sein.                         </div>
                    </div>
                    <input
                        type="text"
                        id="size"
                        wire:model="size"
                        placeholder="Größe eingeben"
                        wire:ignore
                        class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                    />
                    @error('size') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>
            <!-- Beschreibung -->
            <div   wire:ignore x-data="{ tooltipButtonVisible: false }" class="mb-4"
                    @mouseenter="tooltipButtonVisible = true" 
                    @mouseleave="tooltipButtonVisible = false" 
                    @click="tooltipButtonVisible = true"
                    @click.away="tooltipButtonVisible = false">
                    <div x-data="{  tooltipVisible: false }" class="relative inline-block">
                        <label for="description" class="flex items-center justify-center block text-sm font-medium text-gray-700">
                        Beschreibung
                            <button x-show="tooltipButtonVisible"
                                @mouseenter="tooltipVisible = true" 
                                @mouseleave="tooltipVisible = false" 
                                @click="tooltipVisible = !tooltipVisible"
                                @click.away="tooltipVisible = false"
                                x-transition
                                x-cloak
                                type="button"
                                class="ml-2 w-4 h-4 bg-gray-200 text-[12px] rounded-full flex items-center justify-center text-gray-600 hover:bg-gray-300 focus:outline-none"
                                >
                                ?
                            </button>
                        </label>
                    
                        <!-- Tooltip -->
                        <div 
                            x-show="tooltipVisible" 
                            x-cloak
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 translate-y-1"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 translate-y-1"
                            class="absolute left-0 mt-2 w-64 bg-gray-800 text-white text-xs rounded-md shadow-lg p-2 z-10"
                        >
                            Gib die Beschreibung an. 
                        </div>
                    </div>
                <textarea
                    id="description"
                    wire:model="description"
                    rows="8"
                    wire:ignore
                    class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                ></textarea>
                <small class="text-gray-400">
                    Zeichen: <span x-text="$wire.description.length"></span><span>/1600</span>
                </small>
                @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
    
            
        </div>
    </div>
    <!-- Buttons -->
    <div class="flex justify-end">
                <button
                    type="button"
                    onclick="window.history.back()"  
                    wire:navigate 
                    class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 mr-2"
                >
                    Abbrechen
                </button>
                <button
                    type="submit"
                    wire:loading.attr="disabled"
                    class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600"
                >
                    {{ $productId ? 'Aktualisieren' : 'Speichern' }}
                </button>
            </div>
    </form>
            </div>
        </div>
    </div>

        <script src="/adminresources/js/jquerywithselect.js"></script>
        <link href="/adminresources/css/select2.css" rel="stylesheet" />
  
        <script>
                window.waitForselect = async function() {
                    const maxAttempts = 10;
                    let attempts = 0;

                    while ((typeof $.fn.select2 === 'undefined' || typeof $("#category").select2 !== 'function') && attempts < maxAttempts) {
                        await new Promise(resolve => setTimeout(resolve, 200)); 
                        attempts++;
                        console.log("Warte auf select2... Versuch Nr. " + attempts);
                    }
                    if (typeof $.fn.select2 !== 'undefined' || typeof $("#category").select2 == 'function') {
                            console.log("select2:init");
                            $("#category").select2({
                                placeholder: "Wählen Sie eine Kategorie",
                                allowClear: true
                            });
                            $("#ageRecommendation").select2({
                                placeholder: "Wählen Sie eine Altersempfehlung",
                                minimumResultsForSearch: Infinity,
                                allowClear: true
                            });
                            $("#tags").select2({
                                placeholder: "Wählen Sie Schlagwörter",
                                maximumSelectionLength: 3,
                                allowClear: true
                            });
                    }else{
                        console.log("select2:undefined");
                    }
                }

                document.addEventListener('DOMContentLoaded', () => {
                    if(typeof $.fn.select2 !== 'undefined' || typeof $("#category").select2 == 'function'){
                            $("#category").select2({
                                placeholder: "Wählen Sie eine Kategorie",
                                allowClear: true
                            }).on('change', function () {
                                let selectedCategory = $(this).val();
                                @this.set('category', selectedCategory); 
                            });
                            $("#ageRecommendation").select2({
                                placeholder: "Wählen Sie eine Altersempfehlung",
                                minimumResultsForSearch: Infinity,
                                allowClear: true
                            }).on('change', function () {
                                let selectedAge = $(this).val();
                                @this.set('ageRecommendation', selectedAge); 
                            });
                            $("#tags").select2({
                                placeholder: "Wählen Sie Schlagwörter",
                                multiple: true,
                                maximumSelectionLength: 3,
                                allowClear: true
                            }).on('change', function () {
                                let selectedTags = $(this).val();
                                @this.set('tags', selectedTags); 
                            });
                    }else{
                        waitForselect();
                    }
                });
                document.addEventListener('livewire:initialized', () => {
                    
                        const value = @this.ageRecommendation; 
                        $("#ageRecommendation").val(value).trigger('change'); 
                    
                });
        </script>


        <div class="my-20">
            <div class="max-w-7xl mx-auto px-6 py-16 bg-white shadow-lg rounded-lg mt-8">
                <h2 class="text-3xl  text-gray-800 mb-6">Wichtige Hinweise zur Produkterstellung</h2>

                <!-- Hinweis 1 -->
                <div class="mb-6 flex items-start">
                    <span class="inline-flex items-center justify-center h-10 aspect-square rounded-full  bg-[#65765f] text-white font-bold mr-4">1</span>
                    <div>
                        <h3 class="text-xl font-semibold text-gray-700 mb-2">Vollständige und präzise Informationen</h3>
                        <p class="text-gray-600 leading-relaxed">
                            Wenn du deine Produkte einstellst, solltest du unbedingt darauf achten, dass alle wichtigen Felder ausgefüllt sind. <br>
                            Dazu gehören unter anderem der Produktname, der Preis und eine konkrete Beschreibung (z.B. Marke & Größe). Passende Kategorien und Schlagwörter können die Suche ebenfalls erleichtern. <br>
                            <strong>Beispiel</strong>: „Pullover“+ „Marke“ + „Größe“ + „Preis“
                        </p>
                    </div>
                </div>

                <!-- Hinweis 2 -->
                <div class="mb-6 flex items-start">
                    <span class="inline-flex items-center justify-center h-10 aspect-square rounded-full bg-[#65765f] text-white font-bold mr-4">2</span>
                    <div>
                        <h3 class="text-xl font-semibold text-gray-700 mb-2">Bilder verwenden</h3>
                        <p class="text-gray-600 leading-relaxed">
                        Um deinen potenziellen Käufern vorab schon ein Eindruck deiner Produkte zu gewähren, hast du die Möglichkeit Bilder von deinen Artikeln hochzuladen. Kleiner Tipp: Achte bei deinen Bildern auf gute Lichtverhältnisse und eine klare Darstellung. 
                        </p>
                    </div>
                </div>

                <!-- Hinweis 3 -->
                <div class="mb-6 flex items-start">
                    <span class="inline-flex items-center justify-center h-10 aspect-square rounded-full  bg-[#65765f] text-white font-bold mr-4">3</span>
                    <div>
                        <h3 class="text-xl font-semibold text-gray-700 mb-2">Richtige Kategorisierung und Schlagwörter</h3>
                        <p class="text-gray-600 leading-relaxed">
                            Deine Produkte sollten immer in der <strong>richtigen Kategorie</strong> einsortiert werden, damit sie von potenziellen Kunden leicht gefunden werden können. Eine klare und korrekte Kategorisierung sorgt dafür, dass dein Produkt genau dort angezeigt wird, wo Käufer danach suchen. Zusätzlich solltest du unbedingt relevante <strong>Schlagwörter</strong> hinzufügen. Schlagwörter sind Schlüsselbegriffe, die dein Produkt beschreiben und in der Suche verwendet werden.
                            <br><br>
                            Stell dir vor, du verkaufst einen Kinderwagen. Schlagwörter wie "Buggy", "Kindertransport", "Kompakt", "Faltbar" oder "Reisebuggy" helfen dabei, dein Angebot in den richtigen Suchergebnissen anzuzeigen. Minifinds nutzt diese Informationen, um die Sichtbarkeit deines Produkts nicht nur online, sondern auch im lokalen Markt zu maximieren. Durch eine gezielte Inserierung erhöhen wir die Chance, dass Käufer genau dein Produkt entdecken.
                        </p>
                    </div>
                </div>

                <!-- Hinweis 4 -->
                <div class="flex items-start">
                    <span class="inline-flex items-center justify-center h-10 aspect-square rounded-full bg-[#65765f] text-white font-bold mr-4">4</span>
                    <div>
                        <h3 class="text-xl font-semibold text-gray-700 mb-2">Preisangabe und Provision beachten</h3>
                        <p class="text-gray-600 leading-relaxed">
                        Der Preis ist oft der entscheidende Faktor für Käufer. Daher solltest du sicherstellen, dass dein Preis sowohl attraktiv als auch realistisch ist. <br>
                        <strong>Wichtig</strong>: Bei MiniFinds gehen 16% Provision pro verkauftem Produkt an uns. Diese Provision deckt unsere Leistung ab, dein Produkt optimal zu inserieren und lokal erfolgreich zu verkaufen. Durch unsere Unterstützung wird dein Produkt nicht nur online sichtbar, sondern auch im Markt vor Ort, wo es von vielen Käufern entdeckt werden kann. 

                        </p>
                    </div>
                </div>
            </div>

        </div>
</div>
