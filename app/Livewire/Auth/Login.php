<?php

namespace App\Livewire\Auth;

use Livewire\Component;

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class Login extends Component
{

    public $message;
    public $messageType;
    public $email = '';
    public $password = '';
    public $remember = false;



    protected $rules = [
        'email' => 'required|email|max:255|exists:users,email',
        'password' => 'required|min:6|max:255',

    ];
    
    protected $messages = [
        'email.required' => 'Bitte gib deine E-Mail-Adresse ein.',
        'email.email' => 'Bitte gib eine gültige E-Mail-Adresse ein.',
        'email.max' => 'Die E-Mail-Adresse darf maximal 255 Zeichen lang sein.',
        'email.exists' => 'Diese E-Mail-Adresse ist nicht registriert.',
        'password.required' => 'Bitte gib dein Passwort ein.',
        'password.min' => 'Das Passwort muss mindestens 6 Zeichen lang sein.',
        'password.max' => 'Das Passwort darf maximal 255 Zeichen lang sein.',
    ];

    public function login()
    {
        $this->validate();

        if (!Auth::attempt(['email' => $this->email, 'password' => $this->password, 'role' => 'admin'], $this->remember)) {
            throw ValidationException::withMessages([
                'email' => 'Die eingegebene E-Mail-Adresse oder das Passwort ist falsch.',
            ]);
        }
            // Benutzer abrufen
        $user = Auth::user();

        // Admin-Rolle prüfen
        if ($user->role !== 'admin' && $user->role !== 'superadmin') {
            auth('web')->logout(); // Falls kein Admin, sofort ausloggen
            throw ValidationException::withMessages([
                'email' => 'Du hast keinen Zugriff auf das Admin-Panel.',
            ]);
        }
        $this->dispatch('showAlert','Willkommen zurück!', 'success');
        return $this->redirect('/dashboard');
    }

    public function mount()
    {
        // Überprüfen, ob eine Nachricht in der Session existiert
        if (session()->has('message')) {
            $this->message = session()->get('message');
            $this->messageType = session()->get('messageType', 'default'); 
            // Event zum Anzeigen der Nachricht dispatchen
            $this->dispatch('showAlert', $this->message, $this->messageType);
        }
    }


    public function render()
    {
        return view('livewire.auth.login')->layout("layouts/app");
    }
}
