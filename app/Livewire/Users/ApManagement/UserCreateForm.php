<?php

namespace App\Livewire\Users\ApManagement;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserCreateForm extends Component
{
    public string $name = '';
    public string $email = '';

    public bool $showModal = false;

     protected $listeners = ['usercreateformshow' => 'showModal'];


    protected $rules = [
        'name'     => 'required|string|max:255',
        'email'    => 'required|email|unique:users,email'
    ];

    public function showModal(): void
    {
        $this->showModal = true;
    }

    public function createUser(): void
    {
        $this->validate();
        $password = \Illuminate\Support\Str::random(12);

        $user = User::create([
            'name'     => $this->name,
            'email'    => $this->email,
            'password' => Hash::make($password),
        ]);

        $this->reset(['name', 'email']);
        $this->showModal = false;

        $this->dispatch('user.created', userId: $user->id);
    }

    public function render()
    {
        return view('livewire.users.ap-management.user-create-form');
    }
}
