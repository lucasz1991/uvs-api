<?php

namespace App\Livewire\Users\ApManagement;

use Livewire\Component;
use App\Models\ApiKey;
// use App\Models\ApiAbility; // <— DB-Variante

class ApiKeyFormModal extends Component
{
    public bool $showModal = false;

    public ?int $apiKeyId = null;
    public int $userId;

    public string $name = '';
    public bool $active = true;
    public ?string $expires_at = null;

    /** Neu/gewünscht */
    public array $abilities = [];
    public array $availableAbilities = []; // Für das Select

    public ?string $notes = null;
    public ?string $plainToken = null;

    protected $rules = [
        'name'       => 'required|string|max:255',
        'active'     => 'boolean',
        'expires_at' => 'nullable|date',
        'abilities'  => 'array',
        'abilities.*'=> 'string|max:255',
        'notes'      => 'nullable|string|max:2000',
    ];

    protected $listeners = ['open-api-key-form' => 'loadKey'];

    public function mount(): void
    {
        $this->availableAbilities = array_values(config('api.abilities', [
            'participants.store', 'participants.read'
        ]));
    }

    public function loadKey(int $userId, ?int $apiKeyId = null): void
    {
        $this->resetValidation();
        $this->plainToken = null;

        $this->userId = $userId;
        $this->apiKeyId = $apiKeyId;

        if ($apiKeyId) {
            $key = ApiKey::where('user_id', $userId)->findOrFail($apiKeyId);

            $this->name       = $key->name;
            $this->active     = (bool) $key->active;
            $this->expires_at = $key->expires_at?->format('Y-m-d\TH:i');

            $settings         = $key->settings ?? [];
            $this->abilities  = array_values($settings['abilities'] ?? []);

            $meta             = $key->meta ?? [];
            $this->notes      = $meta['notes'] ?? null;
        } else {
            $this->name = '';
            $this->active = true;
            $this->expires_at = null;
            $this->abilities = [];
            $this->notes = null;
        }

        $this->showModal = true;
    }

    public function generateNewToken(): void
    {
        if (!$this->apiKeyId) {
            $this->addError('plainToken', 'API-Key muss zuerst gespeichert werden.');
            return;
        }

        // Bestehenden Key laden
        $key = ApiKey::where('user_id', $this->userId)->findOrFail($this->apiKeyId);

        // Neuen Klartext-Token generieren
        $plain = \Illuminate\Support\Str::random(64);
        $hash  = hash('sha256', $plain);

        // Hash ersetzen
        $key->update([
            'token_hash' => $hash
        ]);

        // Klartext-Token einmalig anzeigen
        $this->plainToken = $plain;

        $this->dispatch('api-key.saved', userId: $this->userId);
    }


    public function saveKey(): void
    {
        $this->validate();

        $settings = [
            'abilities' => array_values($this->abilities),
        ];
        $meta = ['notes' => $this->notes];

        if ($this->apiKeyId) {
            $key = ApiKey::where('user_id', $this->userId)->findOrFail($this->apiKeyId);
            $key->update([
                'name'       => $this->name,
                'active'     => $this->active,
                'expires_at' => $this->expires_at ? now()->parse($this->expires_at) : null,
                'settings'   => $settings,
                'meta'       => $meta,
            ]);
        } else {
            $mint = ApiKey::mint([
                'user_id'    => $this->userId,
                'name'       => $this->name,
                'active'     => $this->active,
                'expires_at' => $this->expires_at ? now()->parse($this->expires_at) : null,
                'settings'   => $settings,
                'meta'       => $meta,
            ]);
            $this->apiKeyId = $mint['model']->id;
            $this->plainToken = $mint['plain'];
        }

        $this->dispatch('api-key.saved', userId: $this->userId);
    }

    public function closeModal(): void
    {
        $this->showModal = false;
    }

    public function render()
    {
        return view('livewire.users.ap-management.api-key-form-modal', [
            'availableAbilities' => $this->availableAbilities,
        ]);
    }
}
