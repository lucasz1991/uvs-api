@props([
  // exakter Livewire-Pfad, z. B. "fileUploads.123"
  'model',

  // optionale DZ-Optionen
  'label' => 'Dateien hier ablegen oder klicken.',
  'acceptedFiles' => null,   // z. B. ".pdf,.png,.jpg"
  'maxFiles' => null,        // z. B. 20
  'maxFilesize' => null,     // z. B. 50 (MB)
])

<div
  x-data="{
    dz: null,

    init() {
      // Event vom Server hören: nur reagieren, wenn das model übereinstimmt
      window.addEventListener('filepool:saved', (e) => {
        if (e?.detail?.model === @js($model)) {
          this.resetDZ();
        }
      });

      this.$nextTick(() => this.mountDZ());
    },
    mountDZ() {
      if (this.dz) return;
      if (!window.Dropzone) { console.error('Dropzone fehlt im Layout'); return; }
      Dropzone.autoDiscover = false;
      const el = this.$refs.dzForm;
      const input = this.$refs.fileInput; // NICHT in wire:ignore
      if (!el || !input) return;
      this.dz = new Dropzone(el, {
        url: '#',                 // nur UI
        autoProcessQueue: false,
        clickable: el,
        previewsContainer: el.querySelector('.dz-previews') || el,
        addRemoveLinks: true,
        maxFiles: 20,
        maxFilesize: 15,
        chunking: true,
        chunkSize: 1000000, // 1 MB pro Chunk
      });
      // Mehrere Dateien hinzufügen → bestehende + neue mergen, dann CHANGE feuern
      this.dz.on('addedfile', (file) => {
        const dt = new DataTransfer();
        for (const f of input.files) dt.items.add(f);
        dt.items.add(file);
        input.files = dt.files;
        input.dispatchEvent(new Event('change', { bubbles: true })); // wichtig für Livewire
      });
      // Entfernte Datei auch aus dem Input entfernen → CHANGE feuern
      this.dz.on('removedfile', (file) => {
        const dt = new DataTransfer();
        for (const f of input.files) {
          const same = f.name === file.name && f.size === file.size && f.lastModified === file.lastModified;
          if (!same) dt.items.add(f);
        }
        input.files = dt.files;
        input.dispatchEvent(new Event('change', { bubbles: true }));
      });
    },

    resetDZ() {
      // Dropzone leeren (inkl. Previews)
      if (this.dz) {
        this.dz.removeAllFiles(true); // true = auch bereits verarbeitete entfernen
      }
      // verstecktes Input leeren + CHANGE, damit Livewire den Reset merkt
      const input = this.$refs.fileInput;
      if (input) {
        const empty = new DataTransfer();
        input.files = empty.files;
        input.dispatchEvent(new Event('change', { bubbles: true }));
      }
    },
  }"
  x-init="init()"
>
  <!-- Dropzone-UI: Livewire darf hier NICHT reinfunken -->
  <form x-ref="dzForm" class="dropzone pointer-events-auto min-h-[140px] rounded-xl border-2 border-dashed border-gray-300 bg-gray-50" wire:ignore>
    <div class="dz-message needsclick">
      <h5 class="text-gray-600 dark:text-gray-100">Dateien hier ablegen oder klicken.</h5>
    </div>
    <div class="dz-previews"></div>
  </form>
  <!-- Livewire-Input: MULTIPLE aktiv -->
  <input
    x-ref="fileInput"
    type="file"
    multiple
    class="hidden"
    wire:model="{{ $model }}"
  >
  @error($model)
    <span class="text-sm text-red-600">{{ $message }}</span>
  @enderror
</div>