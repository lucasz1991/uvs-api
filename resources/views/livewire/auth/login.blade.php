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
                        <x-application-logo />
                    </div>
                </a>

                <h2 class="mt-6 text-2xl font-bold sm:text-3xl md:text-4xl color-primary">
                    Willkommen zurück!
                </h2>

                <p class="mt-4 text-xl font-bold leading-relaxed color-primary">
                    Melde dich mit deinen Zugangsdaten an, um auf dein Konto zuzugreifen. Verwalte deine Buchungen und Produkte oder entdecke weitere Funktionen. Noch kein Konto?
                </p>
            </div>
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
              Willkommen zurück!
              </h1>
    
              <p class="mt-4 text-xl font-bold leading-relaxed color-primary">
              Melde dich mit deinen Zugangsdaten an, um auf dein Konto zuzugreifen. Verwalte deine Buchungen und Produkte oder entdecke weitere Funktionen. Noch kein Konto?
              </p>
            </div>
    
            <div  class="mt-8 ">

                      <form wire:submit.prevent="login">
    @csrf

    <div>
        <x-label for="email" value="E-Mail" />
        <x-input 
            id="email" 
            class="block mt-1 w-full" 
            type="email" 
            wire:model="email" 
            required 
            autofocus 
            autocomplete="username" 
        />
        <x-input-error for="email" class="mt-2" />
    </div>

    <div class="mt-4">
        <x-label for="password" value="Passwort" />
        <x-input 
            id="password" 
            class="block mt-1 w-full" 
            type="password" 
            wire:model="password" 
            required 
            autocomplete="current-password" 
        />
        <x-input-error for="password" class="mt-2" />
    </div>

    <div class="block mt-4">
        <label for="remember_me" class="inline-flex items-center mb-5 cursor-pointer">
            <input 
                id="remember_me" 
                name="remember" 
                type="checkbox" 
                wire:model="remember" 
                class="sr-only peer" 
            />
            <div class="relative w-9 h-5 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
            <span class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">Angemeldet bleiben</span>
        </label>
    </div>

    <div class="flex items-center justify-end mt-4">
        @if (Route::has('password.request'))
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                Hast du dein Passwort vergessen?
            </a>
        @endif
        <div class="max-md:space-y-3 max-sm:text-right ml-3">
            <x-button href="{{ route('register') }}" wire:navigate>
                Registrieren
            </x-button>
            <x-button class="ms-4">
                Einloggen
            </x-button>
        </div>
    </div>
</form>

            </div>

          </div>
        </main>
      </div>
    </section>
</div>
