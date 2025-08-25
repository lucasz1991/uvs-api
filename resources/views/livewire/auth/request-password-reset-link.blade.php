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
            Passwort vergessen
            </h2>
    
            <p class="mt-4 text-xl font-bold leading-relaxed  color-primary">
            Hast du dein Passwort vergessen? Kein Problem! Gib einfach deine E-Mail-Adresse ein, und wir schicken dir einen Link, mit dem du dein Passwort zurücksetzen kannst. So hast du schnell wieder Zugriff auf deine Wunschliste und kannst weiterhin die besten Produkte bei MiniFinds entdecken!</p>
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
              Passwort vergessen
              </h1>
    
              <p class="mt-4 text-xl font-bold leading-relaxed text-gray-500">
              Hast du dein Passwort vergessen? Kein Problem! Gib einfach deine E-Mail-Adresse ein, und wir schicken dir einen Link, mit dem du dein Passwort zurücksetzen kannst. So hast du schnell wieder Zugriff auf deine Wunschliste und kannst weiterhin die besten Produkte bei MiniFinds entdecken!
              </p>
            </div>
    
            <div  class="mt-8 w-xl shrink-0">
                                <x-validation-errors class="mb-4" />

                   

                @if (session()->has('success'))
                    <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session()->has('error'))
                    <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg">
                        {{ session('error') }}
                    </div>
                @endif
                <form wire:submit.prevent="sendResetLink" class="space-y-4">
                    <div>
                                        <x-label for="email" value="E-Mail" />
                                        <x-input id="email" wire:model="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                        @error('email') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <x-button class="">
                        Link anfordern
                    </x-button>
                </form>
            </div>

          </div>
        </main>
      </div>
    </section>
</div>
