<div id="booking" name="booking" x-data="{ 
    showStep: @entangle('showStep'),
    progress: @entangle('progress'),
    period: @entangle('period'),
    startDate: @entangle('startDate'),
    endDate: @entangle('endDate'),
    validStartDate: @entangle('validStartDate'),
    validEndDate: @entangle('validEndDate'),
    formStep: @entangle('formStep')
    }" >
    
    <div x-init="$watch('progress', value => { setTimeout(() => { checkhandleNavigation(); }, 1);})"
    class="w-full relative bg-cover bg-center backgroundimageOverlay"  wire:loading.class="cursor-wait" style=" padding: 50px 0; ">
    
    <div class="absolute inset-0 bg-[#f8f2e8f2] z-1 bg-overlay"></div>
    <div class="container mx-auto z-10">
        
        <div class="mx-auto px-4 sm:px-6 lg:px-8  bg-content">
            <h1 class="text-3xl font-bold mb-8 text-center">Jetzt Stand buchen</h1>
            
            
            <div class="flex items-end max-w-screen-lg mx-auto pl-5" >
                <div class="w-full">
                    <h6 class="text-sm font-bold  mb-2 mr-4 text-green-500">Zeitraum</h6>
                    <div class="flex items-center w-full">
                        <div wire:click="progress > 0 ? $wire.setShowStep(1) : null" 
                                x-data="{ isClicked: false }" 
                                @click="isClicked = true; setTimeout(() => isClicked = false, 100)"
                                style="transform:scale(1);"
                                :style="isClicked ? 'transform:scale(0.7);' : 'transform:scale(1);'"
                                class="transition-all duration-100 w-7 h-7 z-50 shrink-0 mx-[-1px] border-2  flex items-center justify-center rounded-full " :class="progress > 0 ? 'border-green-500 cursor-pointer' : 'border-gray-500 cursor-not-allowed'">
                        @if($this->progress > 0)
                        <svg
                        x-transition:enter="transition ease-out duration-500"
                        x-transition:enter-start="w-[0%]"
                        x-transition:enter-end="w-[100%]"
                        xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 fill-green-500  transition-all duration-500" viewBox="0 0 24 24">
                        <path
                        d="M9.707 19.121a.997.997 0 0 1-1.414 0l-5.646-5.647a1.5 1.5 0 0 1 0-2.121l.707-.707a1.5 1.5 0 0 1 2.121 0L9 14.171l9.525-9.525a1.5 1.5 0 0 1 2.121 0l.707.707a1.5 1.5 0 0 1 0 2.121z"
                                        data-original="#000000" />
                                    </svg>
                                @elseif($this->progress === 0)
                                    <span class="w-3 h-3 bg-green-500 rounded-full delay-1000"></span>
                                @else
                                <span class="text-sm text-green-500 font-bold">1</span>
                                @endif
                            </div>
                            <div class="relative w-full h-[3px] bg-gray-500">
                                <!-- Innerer Balken für Fortschritt -->
                                <div 
                                    class="absolute left-0 top-0 h-full bg-green-500 transition-all duration-1000 animate-pulse"
                                    :class="showStep > 1 ? 'w-[100%]' : 'w-[0%]'"
                                    x-transition:enter="transition ease-out duration-1000"
                                    x-transition:enter-start="w-[0%]"
                                    x-transition:enter-end="w-[100%]"
                                >
                                </div>
                            </div>
                            </div>
                        </div>
                        <div class="w-full">
                            <h6 class="text-sm font-bold  mb-2 mr-4 transition duration-700 ease-in-out" :class="progress >= 1  ? 'text-green-500' : 'text-gray-500'">Datum</h6>
                            <div class="flex items-center w-full">
                            <div
                                wire:click="progress >= 1 ? $wire.setShowStep(2) : null" 
                                x-data="{ isClicked: false }" 
                                @click="isClicked = true; setTimeout(() => isClicked = false, 100)"
                                style="transform:scale(1);"
                                :style="isClicked ? 'transform:scale(0.7);' : 'transform:scale(1);'"
                                class="transition-all duration-100 w-7 h-7 z-50 shrink-0 mx-[-1px] border-2  flex items-center justify-center rounded-full " :class="progress >= 2 ? 'border-green-500 cursor-pointer' : 'border-gray-500 cursor-not-allowed'">
                                @if($this->progress > 1)
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 fill-green-500" viewBox="0 0 24 24">
                                    <path
                                        d="M9.707 19.121a.997.997 0 0 1-1.414 0l-5.646-5.647a1.5 1.5 0 0 1 0-2.121l.707-.707a1.5 1.5 0 0 1 2.121 0L9 14.171l9.525-9.525a1.5 1.5 0 0 1 2.121 0l.707.707a1.5 1.5 0 0 1 0 2.121z"
                                        data-original="#000000" />
                                    </svg>
                                @elseif($this->progress === 1)
                                    <span class="w-3 h-3 bg-green-500 rounded-full transition duration-700 ease-in-out"></span>
                                @else
                                <span class="text-sm text-gray-500 font-bold">2</span>
                                @endif
                            </div>
                            <div class="relative w-full h-[3px] bg-gray-500">
                                <!-- Innerer Balken für Fortschritt -->
                                <div 
                                    class="absolute left-0 top-0 h-full bg-green-500 transition-all duration-1000 animate-pulse"
                                    :class="showStep > 2 ? 'w-[100%]' : 'w-[0%]'"
                                    x-transition:enter="transition ease-out duration-1000"
                                    x-transition:enter-start="w-[0%]"
                                    x-transition:enter-end="w-[100%]"
                                >
                                </div>
                            </div>
                            </div>
                        </div>
                        <div class="w-full">
                            <h6 class="text-sm font-bold  mb-2 mr-4 transition duration-700 ease-in-out" :class="progress >= 2 ? 'text-green-500' : 'text-gray-500'">Stand</h6>
                            <div class="flex items-center w-full">
                            <div wire:click="progress >= 2 ? $wire.setShowStep(3) : null" 
                                x-data="{ isClicked: false }" 
                                @click="isClicked = true; setTimeout(() => isClicked = false, 100)"
                                style="transform:scale(1);"
                                :style="isClicked ? 'transform:scale(0.7);' : 'transform:scale(1);'"
                                class="transition-all duration-100 w-7 h-7 z-50 shrink-0 mx-[-1px] border-2  flex items-center justify-center rounded-full" :class="progress >= 3 ? 'border-green-500 cursor-pointer' : 'border-gray-500 cursor-not-allowed'">
                                @if($this->progress > 2)
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 fill-green-500" viewBox="0 0 24 24">
                                    <path
                                        d="M9.707 19.121a.997.997 0 0 1-1.414 0l-5.646-5.647a1.5 1.5 0 0 1 0-2.121l.707-.707a1.5 1.5 0 0 1 2.121 0L9 14.171l9.525-9.525a1.5 1.5 0 0 1 2.121 0l.707.707a1.5 1.5 0 0 1 0 2.121z"
                                        data-original="#000000" />
                                    </svg>
                                @elseif($this->progress === 2)
                                    <span class="w-3 h-3 bg-green-500 rounded-full  delay-1000"></span>
                                @else
                                <span class="text-sm text-gray-500 font-bold">3</span>
                                @endif
                            </div>
                            <div class="relative w-full h-[3px] bg-gray-500">
                                <!-- Innerer Balken für Fortschritt -->
                                <div 
                                    class="absolute left-0 top-0 h-full bg-green-500 transition-all duration-1000 animate-pulse"
                                    :class="showStep > 3 ? 'w-[100%]' : 'w-[0%]'"
                                    x-transition:enter="transition ease-out duration-1000"
                                    x-transition:enter-start="w-[0%]"
                                    x-transition:enter-end="w-[100%]"
                                >
                                </div>
                            </div>
                            </div>
                        </div>
                        <div>
                            <h6 class="text-sm font-bold  w-max mb-2 transition duration-700 ease-in-out" :class="progress >= 3 ? 'text-green-500' : 'text-gray-500'">Buchen</h6>
                            <div class="flex items-center">
                            <div  wire:click="progress >= 3 ? $wire.setShowStep(4) : null" 
                                x-data="{ isClicked: false }" 
                                @click="isClicked = true; setTimeout(() => isClicked = false, 100)"
                                style="transform:scale(1);"
                                :style="isClicked ? 'transform:scale(0.7);' : 'transform:scale(1);'"
                                class="transition-all duration-100 w-7 h-7 z-50 shrink-0 mx-[-1px] border-2 border-gray-300 flex items-center justify-center rounded-full "  :class="progress >= 4 ? 'border-green-500 cursor-pointer' : 'border-gray-500 cursor-not-allowed'">
                                @if($this->progress > 3)
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 fill-green-500" viewBox="0 0 24 24">
                                    <path
                                        d="M9.707 19.121a.997.997 0 0 1-1.414 0l-5.646-5.647a1.5 1.5 0 0 1 0-2.121l.707-.707a1.5 1.5 0 0 1 2.121 0L9 14.171l9.525-9.525a1.5 1.5 0 0 1 2.121 0l.707.707a1.5 1.5 0 0 1 0 2.121z"
                                        data-original="#000000" />
                                    </svg>
                                @elseif($this->progress === 3)
                                    <span class="w-3 h-3 bg-green-500 rounded-full delay-1000"></span>
                                @else
                                <span class="text-sm text-gray-500 font-bold">4</span>
                                @endif
                            </div>
                            </div>
                        </div>
                    </div>
              
                        <div class="container mx-auto py-12" >
                            <div>
                                

                                <!-- Step 1 -->
                                <!-- Step 1 -->
                                <!-- Step 1 -->
                                <!-- Step 1 -->
                                <!-- Step 1 -->
                                <!-- Step 1 -->
                                <!-- Step 1 -->
                                <!-- Step 1 -->
                                <!-- Step 1 -->
                                <!-- Step 1 -->
                                <!-- Step 1 -->
                                <!-- Step 1 -->
                                <!-- Step 1 -->
                                <!-- Step 1 -->
                                <!-- Step 1 -->
                                <!-- Step 1 -->
                                <!-- Step 1 -->
                                <!-- Step 1 -->
                                <div x-show="showStep == 1"   x-cloak  x-collapse.duration.1000ms>
                                    <div class="max-w-5xl mx-auto font-[sans-serif] md:p-4">
                                        <div x-data="{ hoverCard: 2 }" @mouseleave="hoverCard = 2" class="grid grid-cols-3   mt-8 ">
                                            
                                            <div
                                                @mouseenter="hoverCard = 1"
                                                @click="hoverCard = 1" 
                                                :style="hoverCard === 1 
                                                    ? 'transform: scale(1.2) translateX(30px) rotateX(0deg) rotateY(0deg); z-index: 10;' 
                                                    : hoverCard === 2 
                                                    ? 'transform: scale(1) translateX(10px) rotateX(5deg) rotateY(30deg); z-index: 0;' 
                                                    : 'transform: scale(1) translateX(-10px) rotateX(10deg) rotateY(35deg); z-index: 0;'"
                                                style="transition: transform 0.5s; transform-style: preserve-3d; perspective: 1000px;"
                                            class="bg-white  shadow-lg rounded-3xl  border border-gray-200  p-3 md:p-8 transition-all duration-200 ">
                                                <div class="text-center md:px-3">
                                                    <div class=" justify-center items-baseline my-5 md:my-10">
                                                        <h4 class="text-gray-800 font-semibold text-6xl">7</h4>
                                                        <span class="text-md">Tage</span>
                                                    </div>
                                                    <h3 class="text-gray-800 font-semibold text-2xl mt-4">26,00 €</h3>
                                                    <div class="mt-3 px-1 py-0.5 text-xs  text-gray-500 rounded break-word">16 % Verkaufs&shy;provision</div>
                                                    <button type="button"  x-data="{ isClicked: false }" 
                                                        @click="$wire.setPeriod(1);isClicked = true; setTimeout(() => isClicked = false, 100)"
                                                        style="transform:scale(1);"
                                                        :style="isClicked ? 'transform:scale(0.9);' : 'transform:scale(1);'"
                                                        :class="hoverCard === 1 ? 'bg-blue-200  hover:bg-blue-500 hover:text-white' : 'bg-gray-100 border-gray-200'"
                                                        class="transition-all duration-100 border text-gray-700 w-full mt-8 px-2 md:px-5 py-1 md:py-2.5 text-sm  rounded-full">auswählen</button>
                                                </div>
                                           
                                            </div>

                                            
                                            <div
                                                @mouseenter="hoverCard = 2"
                                                @click="hoverCard = 2" 
                                                :style="hoverCard === 2 
                                                        ? 'transform: scale(1.1) translateX(0) rotateX(0deg) rotateY(0deg); z-index: 10;' 
                                                        : hoverCard === 3 
                                                        ? 'transform: scale(1.1) translateX(-35px) rotateX(5deg) rotateY(20deg); z-index: 1;' 
                                                        : 'transform: scale(1.1) translateX(35px) rotateX(-5deg) rotateY(20deg); z-index: 1;'"
                                                    style="transition: transform 0.5s; transform-style: preserve-3d; perspective: 1000px; "
                                                class="bg-white  shadow-lg rounded-3xl  border border-gray-200  p-3 md:p-8 transition-all duration-200 ">
                                                <div class="text-center md:px-3 ">
                                                    <div class=" justify-center items-baseline my-5 md:my-10">
                                                        <h4 class="text-gray-800 font-semibold text-6xl">14</h4>
                                                        <span class="text-md">Tage</span>
                                                    </div>
                                                    <h3 class="text-gray-800 font-semibold text-2xl mt-4">46,00 €</h3>
                                                    <div class="mt-3 px-1 py-0.5 text-xs  text-gray-500 rounded break-word">16 % Verkaufs&shy;provision</div>
                                                    <button type="button"  x-data="{ isClicked: false }" 
                                                        @click="
                                                            
                                                            isClicked = true; 
                                                            setTimeout(() => { 
                                                                isClicked = false; 
                                                                $wire.setPeriod(2);
                                                            }, 100);
                                                        "
                                                        style="transform:scale(1);"
                                                        :style="isClicked ? 'transform:scale(0.9);' : 'transform:scale(1);'"
                                                        :class="hoverCard === 2 ? 'bg-blue-200  hover:bg-blue-500 hover:text-white' : 'bg-gray-100 border-gray-200'"
                                                        class="transition-all duration-100 border text-gray-700 w-full mt-8 px-2 md:px-5 py-1 md:py-2.5 text-sm  rounded-full">auswählen</button>
                                                </div>
                                            </div>

                                            
                                            <div
                                                @mouseenter="hoverCard = 3"
                                                @click="hoverCard = 3" 
                                                :style="hoverCard === 3 
                                                    ? 'transform: scale(1.2) translateX(-30px) rotateX(0deg)  rotateY(0deg); z-index: 10;' 
                                                    : hoverCard === 2 
                                                    ? 'transform: scale(1) translateX(-10px) rotateX(-5deg)  rotateY(30deg); z-index: 0;' 
                                                    : 'transform: scale(1) translateX(10px) rotateX(-10deg)  rotateY(35deg); z-index: 0;'"
                                                style="transition: transform 0.5s; transform-style: preserve-3d; perspective: 1000px;"
                                                class="bg-white  shadow-lg rounded-3xl  border border-gray-200  p-3 md:p-8 transition-all duration-200 ">
                                                <div class="text-center md:px-3">
                                                    <div class=" justify-center items-baseline my-5 md:my-10">
                                                        <h4 class="text-gray-800 font-semibold text-6xl">21</h4>
                                                        <span class="text-md">Tage</span>
                                                    </div>
                                                    <h3 class="text-gray-800 font-semibold text-2xl mt-4">66,00 €</h3>
                                                    <div class="mt-3 px-1 py-0.5 text-xs  text-gray-500 rounded break-word">16 % Verkaufs&shy;provision</div>
                                                    <button type="button"  x-data="{ isClicked: false }" 
                                                        @click="$wire.setPeriod(3);isClicked = true; setTimeout(() => isClicked = false, 100)"
                                                        style="transform:scale(1);"
                                                        :style="isClicked ? 'transform:scale(0.9);' : 'transform:scale(1);'"
                                                        :class="hoverCard === 3 ? 'bg-blue-200  hover:bg-blue-500 hover:text-white' : 'bg-gray-100 border-gray-200'"
                                                        class="transition-all duration-100 border text-gray-700 w-full mt-8 px-2 md:px-5 py-1 md:py-2.5 text-sm  rounded-full">auswählen</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Step 2 -->
                                <!-- Step 2 -->
                                <!-- Step 2 -->
                                <!-- Step 2 -->
                                <!-- Step 2 -->
                                <!-- Step 2 -->
                                <!-- Step 2 -->
                                <!-- Step 2 -->
                                <!-- Step 2 -->
                                <!-- Step 2 -->
                                <!-- Step 2 -->
                                <!-- Step 2 -->
                                <!-- Step 2 -->
                                <!-- Step 2 -->
                                <!-- Step 2 -->
                                <!-- Step 2 -->
                                <!-- Step 2 -->
                                <div
                                    x-data="calendarHandler()"
                                    x-show="showStep == 2" 
                                    x-init="() => { 
                                        $watch('showStep', value => { 
                                        if(value == 2){
                                            setTimeout(() => {initializeCalendar();}, 10);
                                            $el.scrollIntoView({ behavior: 'smooth', block: 'end' });
                                        } });
                                    }"
                                    x-cloak  
                                    x-collapse.duration.1000ms
                                    
                                    >
                                    <div class="max-w-4xl mx-auto">

                                        <div  id="calender-container" class="scroll-py-6 snap-x overflow-hidden transition duration-1000 ease-in-out opacity-0" :class="showStep == 2 ? ' opacity-100 ' : ''">
                                            <div x-ignore  class="sm:max-w-[90%] sm:max-h-[60vh] md:max-w-[70%] md:max-h-[80vh] aspect-[8/7] mx-auto overflow-hidden  snap-start" id='calendar'></div>
                                            <input type="date" id="startDate" wire:model="startDate" x-cloak  class="hidden">
                                            <input type="date" id="endDate" wire:model="endDate" x-cloak  class="hidden">
                                            @error('startDate') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                                            @error('endDate') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
    
                                            <div id="calender-confirm-button" class="justify-center mt-4 hidden">
                                                <button type="button" x-on:click="submitToLivewire" 
                                                        x-data="{ isClicked: false }" 
                                                        @click="isClicked = true; setTimeout(() => isClicked = false, 100)"
                                                        style="transform:scale(1);"
                                                        :style="isClicked ? 'transform:scale(0.7);' : 'transform:scale(1);'"
                                                        class="transition-all duration-100 py-2.5 px-5 me-2 mb-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 ">weiter</button>
                                            </div>
    
                                            
                                        </div>
                                    </div>
                                    @assets
                                        <script src="/adminresources/js/fullcalendar.js"></script>
                                    @endassets
                                 </div>
                                <!-- Step 3 -->
                                <!-- Step 3 -->
                                <!-- Step 3 -->
                                <!-- Step 3 -->
                                <!-- Step 3 -->
                                <!-- Step 3 -->
                                <!-- Step 3 -->
                                <!-- Step 3 -->
                                <!-- Step 3 -->
                                <!-- Step 3 -->
                                <!-- Step 3 -->
                                <!-- Step 3 -->
                                <!-- Step 3 -->
                                <!-- Step 3 -->
                                <!-- Step 3 -->
                                <!-- Step 3 -->
                                <!-- Step 3 -->
                                <!-- Step 3 -->
                                <!-- Step 3 -->
                                <!-- Step 3 -->
                                <!-- Step 3 -->
                                <!-- Step 3 -->
                                <!-- Step 3 -->
                                <!-- Step 3 -->
                                <!-- Step 3 -->
                                <!-- Step 3 -->
                                <!-- Step 3 -->
                                @assets
                                <link href="/adminresources/css/zoomist.css" rel="stylesheet" />
                                <script src="/adminresources/js/zoomist.js"></script>
                                @endassets
                                @script
                                <script>
                                    
                                window.initializefloorplan = async function() {
                                        var wire;
                                        while ( typeof wire == 'undefined') {
                                            await new Promise(resolve => setTimeout(resolve, 5)); 
                                            wire = Livewire.find(document.getElementById('booking').getAttribute('wire:id'));
                                        }
                                        var svgWrapper = document.getElementById('svg-wrapper');
                                        if (!svgWrapper) {
                                            return;
                                        }
                                        if(typeof window.zoomistInstance !== 'undefined' && window.zoomistInstance !== null){
                                            window.zoomistInstance.destroy(true);
                                            window.zoomistInstance = new Zoomist(svgWrapper, {
                                                draggable: true,
                                                wheelable: true,
                                                controls: true,
                                                slider: true,
                                                zoomer: true,
                                                minScale: 1,
                                                maxScale: 2.5,
                                                pinchable: true,
                                                bounds: false,
                                                initScale: 1,
                                            });
                                        }else{
                                            window.zoomistInstance = new Zoomist(svgWrapper, {
                                                draggable: true,
                                                wheelable: true,
                                                controls: true,
                                                slider: true,
                                                zoomer: true,
                                                minScale: 1,
                                                maxScale: 2.5,
                                                pinchable: true,
                                                bounds: false,
                                                initScale: 1,
                                            });
                                        }
                                }
                                </script>
                                @endscript
                                
                                <div 
                                    x-show="showStep == 3" 
                                    x-effect="if(showStep == 3){setTimeout(() => {initializefloorplan();setTimeout(() => {$el.scrollIntoView({ behavior: 'smooth', block: 'end' });}, 500)}, 100)}"
                                    x-cloak  
                                    x-collapse.duration.1000ms
                                >
                                @php
                                    $svgWidth = $this->retailSpaceLayout['dimensions']['width'];
                                    $svgHeight = $this->retailSpaceLayout['dimensions']['height'];
                                        
                                    function gcd($a, $b) {
                                        return ($b == 0) ? $a : gcd($b, $a % $b);
                                    }                          
                                    $divisor = gcd($svgWidth, $svgHeight);                    
                                    $ratio = ($svgWidth / $divisor) . "/" . ($svgHeight / $divisor);
                                @endphp
                                    <div x-data="{ selectedShelve: null }" class="max-w-2xl mx-auto mb-6 md:p-4 overflow-hidden booking-svg">
                                        <div id="svg-wrapper" class="w-full relative border overflow-hidden  shadow-sm">
                                            <div class="zoomist-wrapper">
                                                <div id="zoomist-image" class="zoomist-image relative">
                                                    <!-- SVG-Darstellung der Verkaufsfläche -->
                                                    <svg xmlns="http://www.w3.org/2000/svg" 
                                                            id="editFloorPlan" 
                                                         
                                                            viewBox="0 0 {{ $this->retailSpaceLayout['dimensions']['width'] }} {{ $this->retailSpaceLayout['dimensions']['height'] }}"
                                                            style="aspect-ratio:{{ $ratio }};background-image: url('{{ asset($this->retailSpaceLayout['backgroundimg']['url']) ?? '' }}');background-size: {{ $this->retailSpaceLayout['backgroundimg']['size'] ?? 'cover' }};background-repeat: no-repeat;background-position: center;"  
                                                            preserveAspectRatio="xMidYMid meet"
                                                        >
                                                        @php
                                                            $spaceLayoutElementsShelves = $this->retailSpaceLayout['elements']['shelves'];
                                                            
                                                        @endphp
                                                        @foreach($spaceLayoutElementsShelves as $shelf)
                                                        @php
                                                            $svgWidth = $this->retailSpaceLayout['dimensions']['width'];
                                                            $svgHeight = $this->retailSpaceLayout['dimensions']['height'];
                                                            $x = ($shelf['x'] / $svgWidth) * $svgWidth;
                                                            $y = ($shelf['y'] / $svgHeight) * $svgHeight;
                                                            $width = $shelf['width'];
                                                            $height = $shelf['height'];

                                                            // Textposition basierend auf der Orientierung
                                                            if ($width > $height) {
                                                                $textX = $x + $width / 2;  // Mittig horizontal
                                                                $textY = $y + $height / 2 + 10; // Leicht nach unten verschieben
                                                                $rotation = ''; // Keine Rotation
                                                            } else {
                                                                $textX = $x + $width / 2 - 10; // Leicht nach links verschieben
                                                                $textY = $y + $height / 2;    // Mittig vertikal
                                                                $rotation = "rotate(90, {$textX}, {$textY})"; // Um 90 Grad drehen
                                                            }

                                                            $isBlocked = in_array($shelf['element_id'], $this->blockedShelves ?? [] );
                                                        @endphp
                                                        @if($isBlocked)
                                                            <g  class="svg-shelf cursor-not-allowed" x="{{ $x }}" y="{{ $y }}" width="{{ $width }}" height="{{ $height }}"  fill="#f98e8e" style="fill: #bdbdbd; stroke: #bdbdbd; stroke-width: 2;" >
                                                                <rect x="{{ $x }}" y="{{ $y }}" width="{{ $width }}" height="{{ $height }}" fill="#f98e8e"
                                                                    style="fill: #f98e8e; stroke: #f98e8e; stroke-width: 2;" ></rect>
                                                                <text x="{{ $textX }}" y="{{ $textY }}" style="stroke: #fff; stroke-width: 1;"  font-size="2.1em" fill="#fff" text-anchor="middle" alignment-baseline="middle" transform="{{ $rotation }}">
                                                                    {{ $shelf['text'] }}
                                                                </text>
                                                            </g>
                                                        @else
                                                        <g  class="svg-shelf hoverable transition-all duration-100 relative"
                                                            
                                                                @click="
                                                                selectedShelve = '{{ $shelf['element_id'] }}';
                                                                "
                                                                @touchstart="
                                                                selectedShelve = '{{ $shelf['element_id'] }}';
                                                                "
                                                            x="{{ $x }}" y="{{ $y }}" width="{{ $width }}" height="{{ $height }}"  data-type="shelf" data-id="{{ $shelf['element_id'] ?? null }}" data-text="{{ $shelf['text'] ?? 'Regal' }}" data-color="{{ $shelf['color'] ?? '#4caf50' }}">
                                                                <rect x="{{ $x }}" y="{{ $y }}" width="{{ $width }}" height="{{ $height }}" x-bind:fill="selectedShelve === '{{ $shelf['element_id'] }}' ? '#90caf9' : '#4caf50'"
                                                                    x-bind:style="selectedShelve === '{{ $shelf['element_id'] }}' ? 'fill: #90caf9; stroke: #1e88e5; stroke-width: 2;' : ''"></rect>
                                                                <text x="{{ $textX }}" y="{{ $textY }}"  font-size="2.1em" fill="#fff" text-anchor="middle" alignment-baseline="middle" transform="{{ $rotation }}">
                                                                    {{ $shelf['text'] }}
                                                                </text>
                                                            </g>
                                                        @endif
                                                        @endforeach

                                                        @foreach($this->retailSpaceLayout['elements']['others'] as $element)
                                                        @php
                                                            $x = ($element['x'] / $svgWidth) * $svgWidth;
                                                            $y = ($element['y'] / $svgHeight) * $svgHeight;
                                                            $width = $element['width'];
                                                            $height = $element['height'];

                                                            // Textposition basierend auf der Orientierung
                                                            if ($width > $height) {
                                                                $textX = $x + $width / 2;  // Mittig horizontal
                                                                $textY = $y + $height / 2 + 10; // Leicht nach unten verschieben
                                                                $rotation = ''; // Keine Rotation
                                                            } else {
                                                                $textX = $x + $width / 2 - 10; // Leicht nach links verschieben
                                                                $textY = $y + $height / 2;    // Mittig vertikal
                                                                $rotation = "rotate(90, {$textX}, {$textY})"; // Um 90 Grad drehen
                                                            }
                                                        @endphp
                                                            <g x="{{ $x }}" y="{{ $y }}" width="{{ $width }}" height="{{ $height }}" fill="{{ $element['color'] ?? '#4caf50' }}" data-type="other" data-id="{{ $element['element_id'] ?? null }}" data-text="{{ $element['text'] ?? 'Eingang' }}" data-color="{{ $element['color'] ?? '#f44336' }}">
                                                                <rect x="{{ $x }}" y="{{ $y }}" width="{{ $width }}" height="{{ $height }}" fill="{{ $element['color'] ?? '#f44336' }}"></rect>
                                                                <text x="{{ $textX }}" y="{{ $textY }}" font-size="1em" fill="#fff" text-anchor="middle" alignment-baseline="middle" transform="{{ $rotation }}">
                                                                    {{ $element['text'] ?? 'Eingang' }}
                                                                </text>
                                                            </g>
                                                        @endforeach
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Weiter-Button -->
                                         <div  class="flex justify-center mt-4">

                                             <button 
                                                 x-show="selectedShelve" 
                                                 @click="$wire.setShelve(selectedShelve);isClicked = true; setTimeout(() => isClicked = false, 100)" 
                                                    x-data="{ isClicked: false }" 
                                                    :style="isClicked ? 'transform:scale(0.7);' : 'transform:scale(1);'"
                                                    class="transition-all duration-100 py-2.5 px-5 me-2 mb-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 ">
                                                 Weiter
                                             </button>
                                         </div>
                                    </div>
                                </div>
                              



                                <!-- Step 4 -->
                                <!-- Step 4 -->
                                <!-- Step 4 -->
                                <!-- Step 4 -->
                                <!-- Step 4 -->
                                <!-- Step 4 -->
                                <!-- Step 4 -->
                                <!-- Step 4 -->
                                <!-- Step 4 -->
                                <!-- Step 4 -->
                                <!-- Step 4 -->
                                <!-- Step 4 -->
                                <!-- Step 4 -->
                                <!-- Step 4 -->
                                <!-- Step 4 -->
                                <!-- Step 4 -->
                                <!-- Step 4 -->
                                <!-- Step 4 -->
                                <!-- Step 4 -->
                                <!-- Step 4 -->
                                <!-- Step 4 -->
                                <!-- Step 4 -->
                                <!-- Step 4 -->
                                <!-- Step 4 -->
                                <!-- Step 4 -->
                                <!-- Step 4 -->
                                <!-- Step 4 -->
                                <div  x-show="showStep == 4" 
                                        x-init="() => { 
                                                $watch('showStep', value => { 
                                                if(value == 4){
                                                    setTimeout(() => {$el.scrollIntoView({ behavior: 'smooth', block: 'end' });}, 500);
                                                } });
                                            }"
                                        x-cloak  x-collapse.duration.1000ms>  
                                    
                                    <section class="">
                                        <div class="max-w-4xl mx-auto">
                                            <div class="grid grid-cols-1 gap-x-16 gap-y-8 lg:grid-cols-5">
                                            
                                            <div class="lg:col-span-2 lg:py-12 max-lg:px-8">
                                                <h2 class="text-xl font-semibold text-gray-800 mb-4">Buchungsübersicht</h2>
                                                <div class="grid grid-cols-2 gap-x-16 gap-y-8 md:grid-cols-1">
                                                    <div class="space-y-4">
                                                        <div>
                                                            <label class="text-sm font-medium text-gray-500">Zeitraum:</label>
                                                            <p class="text-gray-800 font-medium">
                                                                {{ $period }} Tage 
                                                            </p>
                                                        </div>
                                                        <div>
                                                            <label class="text-sm font-medium text-gray-500">Mietbeginn:</label>
                                                            <p class="text-gray-800 font-medium">
                                                                {{ \Carbon\Carbon::parse($startDate ?? now())->translatedFormat('d.m.Y') }} 
                                                            </p>
                                                        </div>
                                                        <div>
                                                            <label class="text-sm font-medium text-gray-500">Mietende:</label>
                                                            <p class="text-gray-800 font-medium">
                                                                {{ \Carbon\Carbon::parse($endDate ?? now()->addWeek())->translatedFormat('d.m.Y') }}
                                                            </p>
                                                        </div>

                                                    </div>
                                                    <div class="space-y-4">

                                                        <div>
                                                            <label class="text-sm font-medium text-gray-500">Regalnummer:</label>
                                                            <p class="text-gray-800 font-medium">{{ $selectedShelve->floor_number ?? 'Nicht verfügbar' }}</p>
                                                        </div>
                                                        <div>
                                                            <label class="text-sm font-medium text-gray-500">Gesamtpreis:</label>
                                                            @if ($isDiscounted)
                                                                <p class="text-gray-500 line-through font-medium">{{ number_format($totalPrice, 2, ',', '.') }} €</p>
                                                                <p class="text-red-600 font-medium">
                                                                    🎁 Valentinstagrabatt: {{ number_format($discountedPrice, 2, ',', '.') }} €
                                                                </p>
                                                            @else
                                                                <p class="text-gray-800 font-medium">{{ number_format($finalPrice, 2, ',', '.') }} €</p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                           <!-- Formular -->
                                            <div x-data="{authorizationID: @entangle('authorizationID'), isExistingCustomer: @entangle('isExistingCustomer'), wantLogin: @entangle('wantLogin') }"  class="rounded-lg bg-white p-8 shadow-lg lg:col-span-3 lg:p-12">
                                                <h2 class="text-xl font-semibold text-gray-800 mb-4">Kundeninformationen</h2>
                                                <!-- Wenn der kunde schon eingeloggt ist -->
                                                @auth
                                                <div>
                                                    <div class="grid grid-cols-1 gap-4">
                                                        <!-- Kundendaten anzeigen -->
                                                        <div>
                                                            <ul class="list-none  text-gray-800">
                                                                <li>
                                                                    <strong>E-Mail:</strong> {{ $customer->email ?? 'Nicht verfügbar' }}
                                                                </li>
                                                                <li>
                                                                    <strong>Vorname:</strong> {{ $customer->customer->first_name ?? 'Nicht verfügbar' }}
                                                                </li>
                                                                <li>
                                                                    <strong>Nachname:</strong> {{ $customer->customer->last_name ?? 'Nicht verfügbar' }}
                                                                </li>
                                                                <li>
                                                                    <strong>Telefon:</strong> {{ $customer->customer->phone_number ?? 'Nicht verfügbar' }}
                                                                </li>
                                                                <li>
                                                                    <strong>Adresse:</strong> {{ $customer->customer->street ?? 'Nicht verfügbar' }},
                                                                    {{ $customer->customer->city ?? 'Nicht verfügbar' }},
                                                                    {{ $customer->customer->postal_code ?? 'Nicht verfügbar' }}
                                                                    {{ $customer->customer->state ?? 'Nicht verfügbar' }}
                                                                    {{ $customer->customer->country ?? '' }}
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>

                                                    
                                                </div>
                                                <!-- Bezahlmöglichkeiten -->
                                                <div class="mt-6">
                                                        <h3 class="text-lg font-medium text-gray-700 mb-4">Bezahlmöglichkeiten</h3>
                                                        
                                                        <!-- Anzeige des grünen Häkchens, wenn Zahlung autorisiert -->
                                                        <div x-show="authorizationID" class="inline-flex items-center text-green-600">
                                                            <svg class="w-5 h-5 mr-2" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" aria-hidden="true">
                                                                <path fill-rule="evenodd" d="M16.707 4.293a1 1 0 010 1.414L8 14.414 3.293 9.707a1 1 0 111.414-1.414L8 11.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                            </svg>
                                                            <span>Zahlung Autorisiert </span>
                                                        </div>
                                                        
                                                        <script src="https://www.paypal.com/sdk/js?client-id={{ urlencode($this->apiSettings['paypal_api_client_id'] ?? 'default_client_id') }}&currency=EUR&components=buttons&enable-funding=card&disable-funding=venmo,paylater&intent=authorize" ></script>    
                                                                     
                                                        <div 
                                                            
                                                            class="space-y-4" 
                                                            x-data="{
                                                                
                                                                initPaypal() {
                                                                    const paypalContainer = document.getElementById('paypal-button-container');
                                                                    if (paypalContainer) {
                                                                        paypalContainer.innerHTML = ''; 
                                                                    }
                                                                    window.paypal.Buttons({
                                                                        style: {
                                                                            shape: 'rect',
                                                                            layout: 'vertical',
                                                                            color: 'gold',
                                                                            label: 'paypal',
                                                                        },
                                                                        async createOrder() {
                                                                            try {
                                                                                const response = await fetch('{{ route('paypal.createOrder') }}', {
                                                                                    method: 'POST',
                                                                                    headers: { 
                                                                                        'Content-Type': 'application/json',
                                                                                        'X-CSRF-TOKEN': '{{ csrf_token() }}', 
                                                                                    },
                                                                                    body: JSON.stringify({
                                                                                        cart: [
                                                                                            {
                                                                                                id: '01',
                                                                                                name: 'Regalmiete',
                                                                                                quantity: '1',                          
                                                                                            }
                                                                                        ],
                                                                                        totalPrice: $wire.get('finalPrice'),
                                                                                    }),
                                                                                });
                                                                                const orderData = await response.json();
                                                                                if (orderData.id) {
                                                                                    return orderData.id;
                                                                                }
                                                                                throw new Error(orderData.message || 'Unbekannter Fehler');
                                                                            } catch (error) {
                                                                                console.error('Fehler beim Erstellen der Bestellung:', error);
                                                                                alert('Fehler beim Erstellen der Bestellung. Bitte versuche es erneut.');
                                                                            }
                                                                        },
                                                                        async onApprove(data, actions) {
                                                                            try {
                                                                                const authorizeResponse = await fetch('{{ route('paypal.authorizeOrder', ':orderID') }}'.replace(':orderID', data.orderID),{
                                                                                        method: 'POST',
                                                                                        headers: {
                                                                                            'Content-Type': 'application/json',
                                                                                            'X-CSRF-TOKEN': '{{ csrf_token() }}', 
                                                                                        },
                                                                                    }
                                                                                );

                                                                                const orderData = await authorizeResponse.json();


                                                                                                const transaction =

                                                                                                    orderData?.purchase_units?.[0]?.payments?.captures?.[0] ||

                                                                                                    orderData?.purchase_units?.[0]?.payments?.authorizations?.[0];

                                                                                                const errorDetail = orderData?.details?.[0];


                                                                                                if (!transaction || transaction.status === 'DECLINED') {

                                                                                                    let errorMessage;

                                                                                                    if (transaction) {

                                                                                                        errorMessage = `Transaction ${transaction.status}: ${transaction.id}`;

                                                                                                    } else if (errorDetail) {

                                                                                                        errorMessage = `${errorDetail.description} (${orderData.debug_id})`;

                                                                                                    } else {

                                                                                                        errorMessage = JSON.stringify(orderData);

                                                                                                    }

                                                                                                    console.log('errorMessage:',errorMessage);
                                                                                                    throw new Error(errorMessage);

                                                                                                } else {
                                                                                                    $wire.set('authorizationID', orderData['purchase_units'][0]['payments']['authorizations'][0]['id'] ?? null); 
                                                                                                    console.log('Zahlung erfolgreich autorisiert!',orderData['purchase_units'][0]['payments']['authorizations'][0]['id'] ?? null);
                                                                                                    // PayPal-Button ausblenden, indem wir den Container leeren
                                                                                                    const paypalContainer = document.getElementById('paypal-button-container');
                                                                                                    if (paypalContainer) {
                                                                                                        paypalContainer.style.display = 'none'; // Button ausblenden
                                                                                                    }

                                                                                                }
                                                                            } catch (error) {
                                                                                console.error('Fehler bei der Zahlung:', error);
                                                                                alert('Fehler beim abschliessen der Zahlung. Bitte versuche es erneut.');
                                                                            }
                                                                        },
                                                                    }).render('#paypal-button-container');
                                                                }
                                                            }"
                                                            x-init="() => { 
                                                                $watch('showStep', value => { 
                                                                    if(value == 4){
                                                                        setTimeout(() => { 
                                                                            initPaypal();
                                                                        }, 300);
                                                                    } 
                                                                });
                                                            }"
                                                        >
                                                            <div id="paypal-button-container"></div>
                                                        </div>
                                                        @error('paymentMethod') 
                                                            <span class="text-red-500 text-sm">{{ $message }}</span> 
                                                        @enderror
                                                    </div>

                                                <!-- AGB und Datenschutz Checkbox -->
                                                <div class="mt-6">
                                                        <label for="terms" class="inline-flex  items-center mb-5 cursor-pointer">
                                                            <input id="terms" name="terms" wire:model="terms" type="checkbox" value="" class="sr-only peer">
                                                            <div class="relative w-9 h-5 min-w-9 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300  rounded-full peer  peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all  peer-checked:bg-blue-600"></div>
                                                            <span class="ms-3 text-sm font-medium text-gray-900 ">Ich akzeptiere die <a href="/termsandconditions" wire:navigate  class="text-green-600 hover:underline">AGB's</a> und die <a href="/privacypolicy" wire:navigate  class="text-green-600 hover:underline">Datenschutzerklärung</a>.</span>
                                                        </label>
                                                        <x-input-error for="terms" class="mt-2" />

                                                    </div>

                                                <!-- Buchung abschließen -->
                                                <div class="mt-6 flex justify-end">
                                                    <button type="button" href="/" wire:navigate   x-data="{ isClicked: false }" 
                                                            @click="isClicked = true; setTimeout(() => isClicked = false, 100)"
                                                            :style="isClicked ? 'transform:scale(0.9);' : 'transform:scale(1);'"
                                                            class="transition-all duration-100 text-gray-500 hover:text-gray-700 mr-4">
                                                            Abbrechen
                                                        </button>
                                                    <button 
                                                        type="button" 
                                                        wire:click="finalizePayment" 
                                                        x-bind:disabled="!authorizationID"
                                                        wire:loading.attr="disabled"
                                                        :class="authorizationID ? 'bg-green-500 hover:bg-green-600' : 'bg-gray-300 cursor-not-allowed'"
                                                        class="px-4 py-2 text-white rounded-lg transition-all duration-100"
                                                    >
                                                        Kostenpflichtig buchen
                                                    </button>
                                                </div>
                                                @endauth
                                                @guest
                                                    
                                                <div>

                                                <div class="space-y-6 mt-6">
                                                <!-- Schritt 1: Persönliche Daten -->
                                                <h2 class="text-lg font-medium text-gray-700 mb-4 flex items-center">
                                                    Persönliche Daten
                                                    <template x-show="formStep >= 2"  x-collapse.duration.1000ms x-cloak>
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ml-2 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                                        </svg>
                                                    </template>
                                                </h2>
                                                <div x-show="formStep === 1"   x-collapse.duration.1000ms x-cloak>
                                                    <!-- E-Mail -->
                                                    <div class="mb-4">
                                                        <label for="email" class="block text-sm font-medium text-gray-700">E-Mail</label>
                                                        <input
                                                            type="email"
                                                            id="email"
                                                            wire:model="email"
                                                            class="w-full rounded-lg border-gray-200 p-3 text-sm"
                                                            placeholder="E-Mail"
                                                        />
                                                        @error('email') 
                                                            <span class="text-red-500 text-sm">{{ $message }}</span> 
                                                        @enderror
                                                    </div>
                                                    <!-- Vorname -->
                                                    <div class="mb-4">
                                                        <label for="firstName" class="block text-sm font-medium text-gray-700">Vorname</label>
                                                        <input
                                                            type="text"
                                                            id="firstName"
                                                            wire:model="first_name"
                                                            class="w-full rounded-lg border-gray-200 p-3 text-sm"
                                                            placeholder="Vorname"
                                                        />
                                                        @error('first_name') 
                                                            <span class="text-red-500 text-sm">{{ $message }}</span> 
                                                        @enderror
                                                    </div>
                                                    <!-- Nachname -->
                                                    <div class="mb-4">
                                                        <label for="lastName" class="block text-sm font-medium text-gray-700">Nachname</label>
                                                        <input
                                                            type="text"
                                                            id="lastName"
                                                            wire:model="last_name"
                                                            class="w-full rounded-lg border-gray-200 p-3 text-sm"
                                                            placeholder="Nachname"
                                                        />
                                                        @error('last_name') 
                                                            <span class="text-red-500 text-sm">{{ $message }}</span> 
                                                        @enderror
                                                    </div>
                                                    <div class="col-span-6 sm:flex sm:items-center sm:gap-4 mt-4">
                                                        <button 
                                                            type="button"
                                                            wire:click="checkFormStep1"  x-data="{ isClicked: false }" 
                                                                @click="isClicked = true; setTimeout(() => isClicked = false, 100)"
                                                                :style="isClicked ? 'transform:scale(0.9);' : 'transform:scale(1);'"
                                                                class="transition-all duration-100 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600"
                                                        >
                                                            Weiter
                                                        </button>
                                                        <p class="mt-4 text-sm text-gray-500 sm:mt-0">
                                                            Du hast schon ein Konto?
                                                            <a href="/login" wire:navigate  class="text-gray-700 underline">Einloggen</a>.
                                                        </p>
                                                    </div>
                                                </div>

                                                <!-- Schritt 2: Adressdaten -->
                                                <template x-show="formStep >= 2"  x-collapse.duration.1000ms x-cloak>
                                                    <h2 class="text-lg font-medium text-gray-700 mb-4 flex items-center">
                                                        Adressdaten
                                                        <template x-show="formStep === 3" x-cloak>
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ml-2 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                                            </svg>
                                                        </template>
                                                    </h2>
                                                </template>
                                                <div x-show="formStep === 2"   x-collapse.duration.1000ms x-cloak>
                                                    <!-- Straße und Nummer -->
                                                    <div class="mb-4">
                                                        <label for="street" class="block text-sm font-medium text-gray-700">Straße und Hausnummer</label>
                                                        <input
                                                            type="text"
                                                            id="street"
                                                            wire:model="street"
                                                            class="w-full rounded-lg border-gray-200 p-3 text-sm"
                                                            placeholder="Straße und Hausnummer"
                                                        />
                                                        @error('street') 
                                                            <span class="text-red-500 text-sm">{{ $message }}</span> 
                                                        @enderror
                                                    </div>
                                                    <!-- Stadt -->
                                                    <div class="mb-4">
                                                        <label for="city" class="block text-sm font-medium text-gray-700">Stadt</label>
                                                        <input
                                                            type="text"
                                                            id="city"
                                                            wire:model="city"
                                                            class="w-full rounded-lg border-gray-200 p-3 text-sm"
                                                            placeholder="Stadt"
                                                        />
                                                        @error('city') 
                                                            <span class="text-red-500 text-sm">{{ $message }}</span> 
                                                        @enderror
                                                    </div>
                                                    <!-- PLZ -->
                                                    <div class="mb-4">
                                                        <label for="postal_code" class="block text-sm font-medium text-gray-700">Postleitzahl</label>
                                                        <input
                                                            type="text"
                                                            id="postal_code"
                                                            wire:model="postal_code"
                                                            class="w-full rounded-lg border-gray-200 p-3 text-sm"
                                                            placeholder="Postleitzahl"
                                                        />
                                                        @error('postal_code') 
                                                            <span class="text-red-500 text-sm">{{ $message }}</span> 
                                                        @enderror
                                                    </div>
                                                    <!-- Bundesland -->
                                                    <div class="mb-4">
                                                        <label for="state" class="block text-sm font-medium text-gray-700">Bundesland</label>
                                                        <input
                                                            type="text"
                                                            id="state"
                                                            wire:model="state"
                                                            class="w-full rounded-lg border-gray-200 p-3 text-sm"
                                                            placeholder="Bundesland"
                                                        />
                                                        @error('state') 
                                                            <span class="text-red-500 text-sm">{{ $message }}</span> 
                                                        @enderror
                                                    </div>
                                                    <button 
                                                        type="button"
                                                        wire:click="checkFormStep2"  x-data="{ isClicked: false }" 
                                                            @click="isClicked = true; setTimeout(() => isClicked = false, 100)"
                                                            :style="isClicked ? 'transform:scale(0.9);' : 'transform:scale(1);'"
                                                            class="transition-all duration-100 mt-4 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600"
                                                    >
                                                        Weiter
                                                    </button>
                                                </div>

                                                <!-- Schritt 3: Bezahlmöglichkeiten -->
                                                <div x-show="formStep === 3" x-collapse.duration.1000ms x-cloak>
                                                    <h2 class="text-lg font-medium text-gray-700 mb-4">Bezahlmöglichkeiten</h2>
                                                    <!-- Anzeige des grünen Häkchens, wenn Zahlung autorisiert -->
                                                    <div x-show="authorizationID" class="flex items-center text-green-600">
                                                        <svg class="w-5 h-5 mr-2" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" aria-hidden="true">
                                                            <path fill-rule="evenodd" d="M16.707 4.293a1 1 0 010 1.414L8 14.414 3.293 9.707a1 1 0 111.414-1.414L8 11.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                        </svg>
                                                        <span>Zahlung Autorisiert</span>
                                                    </div>
                                                    <!-- Auswahl der Bezahlmöglichkeiten -->
                                                    <script src="https://www.paypal.com/sdk/js?client-id={{ urlencode($this->apiSettings['paypal_api_client_id'] ?? 'default_client_id') }}&currency=EUR&components=buttons&enable-funding=card&disable-funding=venmo,paylater&intent=authorize" data-sdk-integration-source="developer-studio"></script>    

                                                    <div 
                                                            wire:ignore
                                                            class="space-y-4" 
                                                            x-data="{
                                                                
                                                                initPaypal() {
                                                                    const paypalContainer = document.getElementById('paypal-button-container');
                                                                    if (paypalContainer) {
                                                                        paypalContainer.innerHTML = ''; 
                                                                    }
                                                                    window.paypal.Buttons({
                                                                        style: {
                                                                            shape: 'rect',
                                                                            layout: 'vertical',
                                                                            color: 'gold',
                                                                            label: 'paypal',
                                                                        },
                                                                        async createOrder() {
                                                                            try {
                                                                                const response = await fetch('{{ route('paypal.createOrder') }}', {
                                                                                    method: 'POST',
                                                                                    headers: { 
                                                                                        'Content-Type': 'application/json',
                                                                                        'X-CSRF-TOKEN': '{{ csrf_token() }}', 
                                                                                    },
                                                                                    body: JSON.stringify({
                                                                                        cart: [
                                                                                            {
                                                                                                id: '01',
                                                                                                name: 'Regalmiete',
                                                                                                quantity: '1',                          
                                                                                            }
                                                                                        ],
                                                                                        totalPrice: $wire.get('finalPrice'),
                                                                                    }),
                                                                                });
                                                                                const orderData = await response.json();
                                                                                if (orderData.id) {
                                                                                    return orderData.id;
                                                                                }
                                                                                throw new Error(orderData.message || 'Unbekannter Fehler');
                                                                            } catch (error) {
                                                                                console.error('Fehler beim Erstellen der Bestellung:', error);
                                                                                alert('Fehler beim Erstellen der Bestellung. Bitte versuche es erneut.');
                                                                            }
                                                                        },
                                                                        async onApprove(data, actions) {
                                                                            try {
                                                                                const authorizeResponse = await fetch('{{ route('paypal.authorizeOrder', ':orderID') }}'.replace(':orderID', data.orderID),{
                                                                                        method: 'POST',
                                                                                        headers: {
                                                                                            'Content-Type': 'application/json',
                                                                                            'X-CSRF-TOKEN': '{{ csrf_token() }}', 
                                                                                        },
                                                                                    }
                                                                                );

                                                                                const orderData = await authorizeResponse.json();


                                                                                                const transaction =

                                                                                                    orderData?.purchase_units?.[0]?.payments?.captures?.[0] ||

                                                                                                    orderData?.purchase_units?.[0]?.payments?.authorizations?.[0];

                                                                                                const errorDetail = orderData?.details?.[0];


                                                                                                if (!transaction || transaction.status === 'DECLINED') {

                                                                                                    let errorMessage;

                                                                                                    if (transaction) {

                                                                                                        errorMessage = `Transaction ${transaction.status}: ${transaction.id}`;

                                                                                                    } else if (errorDetail) {

                                                                                                        errorMessage = `${errorDetail.description} (${orderData.debug_id})`;

                                                                                                    } else {

                                                                                                        errorMessage = JSON.stringify(orderData);

                                                                                                    }

                                                                                                    console.log('errorMessage:',errorMessage);
                                                                                                    throw new Error(errorMessage);

                                                                                                } else {
                                                                                                    $wire.set('authorizationID', orderData['purchase_units'][0]['payments']['authorizations'][0]['id'] ?? null); 
                                                                                                    console.log('Zahlung erfolgreich autorisiert!',orderData['purchase_units'][0]['payments']['authorizations'][0]['id'] ?? null);
                                                                                                    // PayPal-Button ausblenden, indem wir den Container leeren
                                                                                                    const paypalContainer = document.getElementById('paypal-button-container');
                                                                                                    if (paypalContainer) {
                                                                                                        paypalContainer.style.display = 'none'; // Button ausblenden
                                                                                                    }

                                                                                                }
                                                                            } catch (error) {
                                                                                console.error('Fehler bei der Zahlung:', error);
                                                                                alert('Fehler beim abschliessen der Zahlung. Bitte versuche es erneut.');
                                                                            }
                                                                        },
                                                                    }).render('#paypal-button-container');
                                                                }
                                                            }"
                                                    x-init="() => { 
                                                            $watch('formStep', value => { 
                                                            if(value == 3){
                                                                setTimeout(() => { 
                                                                    initPaypal();
                                                                }, 500);
                                                            } });
                                                        }"
                                                    >
                                                <div id="paypal-button-container"></div>
                                            </div>
                                                    @error('paymentMethod')
                                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                                    @enderror

                                                    <!-- AGB und Datenschutz Checkbox -->
                                                    <div class="mt-6">
                                                        <label for="terms" class="inline-flex items-center mb-5 cursor-pointer">
                                                            <input id="terms" wire:model="terms" name="terms" type="checkbox" value="" class="sr-only peer">
                                                            <div class="relative w-9 h-5  min-w-9 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300  rounded-full peer  peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all  peer-checked:bg-blue-600"></div>
                                                            <span class="ms-3 text-sm font-medium text-gray-900 ">Ich akzeptiere die <a href="/termsandconditions" wire:navigate  class="text-green-600 hover:underline">AGB's</a> und die <a href="/privacypolicy" wire:navigate  class="text-green-600 hover:underline">Datenschutzerklärung</a>.</span>
                                                        </label>
                                                        @error('terms')
                                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                                        @enderror
                                                    </div>

                                                    <!-- Button "Kostenpflichtig buchen" -->
                                                    <div class="mt-6">
                                                            <button 
                                                                type="button" 
                                                                wire:click="finalizePayment" 
                                                                x-bind:disabled="!authorizationID"
                                                                wire:loading.attr="disabled"
                                                                :class="authorizationID ? 'bg-green-500 hover:bg-green-600' : 'bg-gray-300 cursor-not-allowed'"
                                                                class="px-4 py-2 text-white rounded-lg transition-all duration-100"
                                                            >
                                                                Kostenpflichtig buchen
                                                            </button>
                                                    </div>
                                                </div>

                                            </div>

                                                            
                                                </div>         
                                                @endguest


                                                
                                            </div>
                                        </div>
                                    </section>

                                </div>

                                <script>
                                
                                    window.handleNavigation = async function(event) {
                                        var wire2;
                                        while (typeof wire2 === 'undefined') {
                                            await new Promise(resolve => setTimeout(resolve, 1)); 
                                            wire2 = Livewire.find(document.getElementById('booking').getAttribute('wire:id'));
                                        }
                                        if (wire2) {
                                            
                                            const showStep = wire2.get('showStep');
                                            const progress = wire2.get('progress');
                                            
                                            if (showStep > 1) {
                                                event.preventDefault();
                                                
                                                if (showStep > 1 && progress > 0 && progress < showStep) {
                                                    wire2.set('showStep', showStep - 1);
                                                    wire2.set('progress', progress - 1);
                                                } else {
                                                    goBackInHistory();
                                                }
                                            } else {
                                                goBackInHistory();
                                            }
                                        } else {
                                            goBackInHistory();
                                        }
                                    } 
                                    function goBackInHistory(){
                                        var history = window.history;
                                        history.back();
                                    } 
                                    window.checkhandleNavigation = async function() {
                                        var showsteplivewirevariable;
                                        var wire2;
                                        while (typeof wire2 === 'undefined') {
                                            await new Promise(resolve => setTimeout(resolve, 1)); 
                                            wire2 = Livewire.find(document.getElementById('booking').getAttribute('wire:id'));
                                        }
                                        showsteplivewirevariable = wire2.get('showStep');                                        
                                        if(showsteplivewirevariable > 1) {
                                            window.history.pushState(null, null, window.location.href); // Verhindert tatsächliches Vor- oder Zurücknavigieren
                                        }   
                                        
                                    }
                                    // Navigations script zur besonderen Verwaltung von broswerNavigation 

                                    document.addEventListener('DOMContentLoaded', function () {
                                        // Initialisiere Navigationsevents, wenn die Seite geladen ist
                                        window.addEventListener('popstate', function(event) {                                            
                                            handleNavigation(event);
                                        });

                                        window.addEventListener('hashchange', function(event) {
                                            console.log('hashchange Event - URL Hash Änderung');
                                            handleNavigation(event);
                                        });
                                        
                                    });
                                    
                                
                                   
                                </script>
                                
                            </div>
                        </div>
                </div>
        </div>
    </div>

    <x-whatnext-banner />

</div>