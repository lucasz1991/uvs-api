<div>
    <section class="bg-white">
      <div class="lg:grid lg:min-h-[70vh] lg:grid-cols-12">
        <section class="relative flex h-32 items-center justify-end bg-gray-900 lg:col-span-5 lg:h-full xl:col-span-6">
          <img
            alt=""
            src="{{ asset('site-images/background.webp') }}"
            class="absolute inset-0 h-full w-full object-cover opacity-80"
          />
            <!-- Overlay -->
            <div class="absolute inset-0" style="background-color:var(--secondary-color); opacity:0.9;"></div>
          <div class="hidden lg:relative lg:block lg:p-12">
            <a class="block text-white" href="#">
              <span class="sr-only">Home</span>
              <div class="w-20">
                  <x-application-logo  />
                </div>
            </a>
    
            <h2 class="mt-6 text-2xl font-bold  sm:text-3xl md:text-4xl color-primary">
              Willkommen bei Minifinds!
            </h2>
    
            <p class="mt-4 text-xl font-bold leading-relaxed color-primary">
                Erstelle jetzt dein persönliches Konto und genieße alle Vorteile unserer Plattform. Mit deiner Registrierung erhältst du Zugang zu exklusiven Funktionen wie der einfachen Buchung von Verkaufsflächen, der Nachverfolgung deiner Verkäufe und dem direkten Kontakt zu Käufern. Bitte fülle das Formular sorgfältig aus, damit wir dir ein optimales Nutzungserlebnis bieten können. Deine Daten werden sicher und vertraulich behandelt. Wir freuen uns, dich als Teil unserer Community begrüßen zu dürfen!
            </p>
          </div>
        </section>
    
        <main
          class="flex items-center justify-center px-8 py-8 sm:px-12 lg:col-span-7 lg:px-16 lg:py-12 xl:col-span-6"
        >
          <div class="max-w-xl lg:max-w-3xl">
            <div class="relative -mt-16 block lg:hidden">
              <a
                class="inline-flex size-16 items-center justify-center rounded-full bg-white text-blue-600 sm:size-20"
                href="#"
              >
                <span class="sr-only">Home</span>
                <div class="w-20">
                  <x-application-logo  />
                </div>
              </a>
    
              <h1 class="mt-2 text-2xl font-bold text-gray-900 sm:text-3xl md:text-4xl">
                Willkommen bei Minifinds!
              </h1>
    
              <p class="mt-4 text-xl font-bold leading-relaxed color-primary">
                  Erstelle jetzt dein persönliches Konto und genieße alle Vorteile unserer Plattform. Mit deiner Registrierung erhältst du Zugang zu exklusiven Funktionen wie der einfachen Buchung von Verkaufsflächen, der Nachverfolgung deiner Verkäufe und dem direkten Kontakt zu Käufern. Bitte fülle das Formular sorgfältig aus, damit wir dir ein optimales Nutzungserlebnis bieten können. Deine Daten werden sicher und vertraulich behandelt. Wir freuen uns, dich als Teil unserer Community begrüßen zu dürfen!
              </p>
            </div>
    
            <div  class="mt-8 grid grid-cols-6 gap-6">
               


                <!-- E-Mail und Benutzername -->
                <div class="col-span-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- E-Mail -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">E-Mail</label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            wire:model="email"
                            value="{{ old('email') }}" 
                            class="w-full rounded-lg border-gray-300 p-3 mt-1 text-sm"
                            placeholder="E-Mail-Adresse" 
                         
                        />
                        <x-input-error for="email" class="mt-2" />

                    </div>

                    <!-- Benutzername -->
                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-700">Benutzername</label>
                        <input 
                            type="text" 
                            id="username" 
                            name="username" 
                            wire:model="username"
                            value="{{ old('username') }}" 
                            class="w-full rounded-lg border-gray-300 p-3 mt-1 text-sm"
                            placeholder="Benutzername" 
                         
                        />
                        <x-input-error for="username" class="mt-2" />

                    </div>
                </div>

                <div class="col-span-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Passwort -->
                    <div class="">
                        <label for="password" class="block text-sm font-medium text-gray-700">Passwort</label>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            wire:model="password"
                            class="w-full rounded-lg border-gray-300 p-3 mt-1 text-sm"
                            placeholder="Passwort" 
                          
                        />
                        <x-input-error for="password" class="mt-2" />

                    </div>
    
                    <!-- Passwort bestätigen -->
                    <div class="">
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Passwort bestätigen</label>
                        <input 
                            type="password" 
                            id="password_confirmation" 
                            name="password_confirmation" 
                            wire:model="password_confirmation"
                            class="w-full rounded-lg border-gray-300 p-3 mt-1 text-sm"
                            placeholder="Passwort bestätigen" 
                           
                        />
                        <x-input-error for="password_confirmation" class="mt-2" />

                    </div>
                </div>
</hr>
                <!-- Persönliche Daten -->
                <div class="col-span-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Vorname -->
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700">Vorname</label>
                        <input 
                            type="text" 
                            id="first_name" 
                            name="first_name" 
                            wire:model="first_name"
                            value="{{ old('first_name') }}" 
                            class="w-full rounded-lg border-gray-300 p-3 mt-1 text-sm"
                            placeholder="Vorname" 
                          
                        />
                        <x-input-error for="first_name" class="mt-2" />

                    </div>

                    <!-- Nachname -->
                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-700">Nachname</label>
                        <input 
                            type="text" 
                            id="last_name" 
                            name="last_name" 
                            wire:model="last_name"
                            value="{{ old('last_name') }}" 
                            class="w-full rounded-lg border-gray-300 p-3 mt-1 text-sm"
                            placeholder="Nachname" 
                           
                        />
                        <x-input-error for="last_name" class="mt-2" />

                    </div>
                </div>

                <!-- Kontaktinformationen -->
                <div class="col-span-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Telefonnummer -->
                    <div>
                        <label for="phone_number" class="block text-sm font-medium text-gray-700">Telefonnummer</label>
                        <input 
                            type="tel" 
                            id="phone_number" 
                            name="phone_number"
                            wire:model="phone_number" 
                            value="{{ old('phone_number') }}" 
                            class="w-full rounded-lg border-gray-300 p-3 mt-1 text-sm"
                            placeholder="Telefonnummer" 
                           
                        />
                        <x-input-error for="phone_number" class="mt-2" />

                    </div>

                    <!-- Straße -->
                    <div>
                        <label for="street" class="block text-sm font-medium text-gray-700">Straße</label>
                        <input 
                            type="text" 
                            id="street" 
                            name="street" 
                            wire:model="street"
                            value="{{ old('street') }}" 
                            class="w-full rounded-lg border-gray-300 p-3 mt-1 text-sm"
                            placeholder="Straße" 
                          
                        />
                        <x-input-error for="street" class="mt-2" />

                    </div>
                </div>

                <!-- Stadt & Postleitzahl -->
                <div class="col-span-6 grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <!-- Stadt -->
                    <div>
                        <label for="city" class="block text-sm font-medium text-gray-700">Stadt</label>
                        <input 
                            type="text" 
                            id="city" 
                            name="city" 
                            wire:model="city"
                            value="{{ old('city') }}" 
                            class="w-full rounded-lg border-gray-300 p-3 mt-1 text-sm"
                            placeholder="Stadt" 
                          
                        />
                        <x-input-error for="city" class="mt-2" />

                    </div>

                    <!-- Postleitzahl -->
                    <div>
                        <label for="postal_code" class="block text-sm font-medium text-gray-700">Postleitzahl</label>
                        <input 
                            type="text" 
                            id="postal_code" 
                            name="postal_code" 
                            wire:model="postal_code"
                            value="{{ old('postal_code') }}" 
                            class="w-full rounded-lg border-gray-300 p-3 mt-1 text-sm"
                            placeholder="Postleitzahl" 
                          
                        />
                        <x-input-error for="postal_code" class="mt-2" />
                    </div>
                    <!-- Land -->
                    <div class="">
                        <label for="country" class="block text-sm font-medium text-gray-700">Land</label>
                        <input 
                            type="text" 
                            id="country" 
                            name="country" 
                            wire:model="country"
                            value="{{ old('country') }}" 
                            class="w-full rounded-lg border-gray-300 p-3 mt-1 text-sm"
                            placeholder="Land" 
                          
                        />
                        <x-input-error for="country" class="mt-2" />
                    </div>
                </div>


                <!-- Datenschutz -->
                <div class="col-span-6">
                        <label for="terms" class="inline-flex items-center mb-5 cursor-pointer">
                            <input wire:model="terms" id="terms" name="terms" type="checkbox" value="" class="sr-only peer">
                            <div class="relative w-9 min-w-9 h-5 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                            <span class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">
                                Mit der Erstellung eines Kontos stimme ich den
                              <a href="/termsandconditions" wire:navigate class="text-gray-700 underline">Allgemeinen Geschäftsbedingungen</a>
                              und der
                              <a href="/privacypolicy" wire:navigate class="text-gray-700 underline">Datenschutzerklärung</a> zu.
                                
                            </span>
                          </label>
                        
                </div>

                <!-- Buttons -->
                <div class="col-span-6 sm:flex sm:items-center sm:gap-4">
                    <x-button wire:click="register"  wire:navigate >
                    
                        Registrieren
                    </x-button >

                    <p class="mt-4 text-sm text-gray-500 sm:mt-0">
                        Du hast schon ein Konto?
                        <a href="/login" wire:navigate  class="text-gray-700 underline">Einloggen</a>.
                    </p>
                </div>
            </div>

          </div>
        </main>
      </div>
    </section>
   
 
</div>