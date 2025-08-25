<div x-data="{ showModal: @entangle('showMailModal') }" x-init="$watch('showModal', value => { if (!value) $wire.set('mailUserId', null); })">
    <!-- Modal für Mail-Verfassen -->
    <x-dialog-modal wire:model="showMailModal" >
        <x-slot name="title">
            Mail verfassen
            @if ($mailUserId)
                <!-- Wenn es eine einzelne Mail ist -->
                <span class="text-sm text-gray-500 block mt-1">
                    An: {{ App\Models\User::find($mailUserId)?->email ?? 'Benutzer nicht gefunden' }}
                </span>
            @else
                <!-- Wenn es eine Massenmail ist -->
                <span class="text-sm text-gray-500 block mt-1">
                    An {{ count($selectedUsers) }} Benutzer senden
                </span>
            @endif
        </x-slot>
        <x-slot name="content"> 
            <!-- Alert Hinweis -->
            <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-4" role="alert">
                <p class="font-bold">Wichtiger Hinweis</p>
                <p>Bitte stelle sicher, dass die E-Mail sorgfältig und überlegt verfasst ist. Überprüfe insbesondere den Betreff, die Überschrift und die Nachricht auf Rechtschreibung und Relevanz, da sie direkt an die ausgewählten Benutzer gesendet wird.</p>
            </div>
            <div class="mt-4">
                <label class="inline-flex items-center cursor-pointer">
                  <input type="checkbox" value="" wire:model="mailWithMail" class="sr-only peer">
                  <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600 dark:peer-checked:bg-blue-600"></div>
                  <span class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">Auch als E-Mail senden?</span>
                </label>
            </div>
            <div class="mt-4">
                <label for="mailSubject" class="block text-sm font-medium text-gray-700">Betreff</label>
                <input type="text" id="mailSubject" wire:model="mailSubject" class="mt-1 block w-full border rounded px-4 py-2">
                <x-input-error for="mailSubject" class="mt-2" />
            </div>
            <div class="mt-4">
                <label for="mailHeader" class="block text-sm font-medium text-gray-700">Überschrift</label>
                <input type="text" id="mailHeader" wire:model="mailHeader"  class="mt-1 block w-full border rounded px-4 py-2">
                <x-input-error for="mailHeader" class="mt-2" />
            </div>
            <div class="mt-4">
                <label for="mailBody" class="block text-sm font-medium text-gray-700">Nachricht</label>
                <textarea id="mailBody" rows="6" wire:model="mailBody"  class="mt-1 block w-full border rounded px-4 py-2"></textarea>
                <x-input-error for="mailBody" class="mt-2" />
            </div>
            <div class="mt-4">
                <label for="mailLink" class="block text-sm font-medium text-gray-700">Link (optional)</label>
                <input type="url" id="mailLink" wire:model="mailLink"  class="mt-1 block w-full border rounded px-4 py-2">
                <x-input-error for="mailLink" class="mt-2" />
            </div>
            <div class="mt-4">
                <x-ui.filepool.drop-zone :model="'fileUploads'" />
            </div>
        </x-slot>
        <x-slot name="footer">
            <x-secondary-button wire:click="resetMailModal" wire:loading.attr="disabled">
                Abbrechen
            </x-secondary-button>
            <x-button wire:click="sendMail" wire:loading.attr="disabled" class="ml-2">
                Senden
            </x-button>
        </x-slot>
    </x-dialog-modal>
</div>