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
            Neues Passwort
            </h2>
    
            <p class="mt-4 text-xl font-bold leading-relaxed  color-primary">
            Du bist nur noch einen Schritt entfernt! Erstelle jetzt ein neues Passwort, um wieder vollen Zugriff auf dein Konto bei MiniFinds zu erhalten. Sicher, schnell und einfach – so kannst du direkt weitermachen und die besten Produkte entdecken!          </div>
        </section>
    
        <main
          class="flex items-center justify-center px-8 py-8 sm:px-12 lg:col-span-7 lg:px-16 lg:py-12 xl:col-span-6"
        >
          <div class="max-w-xl lg:max-w-3xl">
            <div class="relative -mt-16 block lg:hidden">
              <a
                class="inline-flex size-16 items-center justify-center  text-blue-600 "
                href="#"
              >
                <span class="sr-only">Home</span>
                <div class="w-20">
                  <x-application-logo  />
                </div>
              </a>
    
              <h1 class="mt-2 text-2xl font-bold text-gray-900 sm:text-3xl md:text-4xl">
              Neues Passwort
              </h1>
    
              <p class="mt-4 text-xl font-bold leading-relaxed text-gray-500">
              Du bist nur noch einen Schritt entfernt! Erstelle jetzt ein neues Passwort, um wieder vollen Zugriff auf dein Konto bei MiniFinds zu erhalten. Sicher, schnell und einfach – so kannst du direkt weitermachen und die besten Produkte entdecken!              </p>
            </div>
    
            <div  class="mt-8 w-xl shrink-0">
                                <x-validation-errors class="mb-4" />
                                @if (session()->has('status'))
                                    <div class="mb-4 text-green-600 text-sm font-semibold">
                                        {{ session('status') }}
                                    </div>
                                @endif

                    <form wire:submit.prevent="resetPassword">
                        <div class="mb-4">
                            <label for="email" class="block text-sm font-medium text-gray-700">E-Mail-Adresse</label>
                            <input wire:model="email" id="email" type="email" class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password" class="block text-sm font-medium text-gray-700">Neues Passwort</label>
                            <input wire:model="password" id="password" type="password" class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            @error('password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-6">
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Passwort bestätigen</label>
                            <input wire:model="password_confirmation" id="password_confirmation" type="password" class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <x-button class="">
                            Passwort zurücksetzen
                        </x-button>
                    </form>
            </div>

          </div>
        </main>
      </div>
    </section>
</div>
