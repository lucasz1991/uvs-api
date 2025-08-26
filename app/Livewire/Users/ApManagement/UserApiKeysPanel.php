<?php

namespace App\Livewire\Users\ApManagement;

use App\Models\ApiKey;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class UserApiKeysPanel extends Component
{
    use WithPagination;

    public int $userId;

    public string $search = '';

    protected $paginationTheme = 'tailwind';

    // listener for API key creation
    protected $listeners = ['api-key.saved' => 'resetPage'];

    public function mount(int $userId): void
    {
        $this->userId = $userId;
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    } 

    public function deleteKey(int $keyId): void
    {
        $key = ApiKey::findOrFail($keyId);
        $key->delete();
        $this->resetPage();
    }

    public function render()
    {
        $user = User::findOrFail($this->userId);

        $keys = ApiKey::query()
            ->where('user_id', $user->id)
            ->when($this->search, fn($q) =>
                $q->where(function ($w) {
                    $w->where('name', 'like', "%{$this->search}%")
                      ->orWhere('settings->abilities', 'like', "%{$this->search}%");
                })
            )
            ->orderByDesc('id')
            ->paginate(10);

        return view('livewire.users.ap-management.user-api-keys-panel', [
            'user' => $user,
            'keys' => $keys,
        ]);
    }
}
