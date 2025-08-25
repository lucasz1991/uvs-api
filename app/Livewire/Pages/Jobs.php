<?php

namespace App\Livewire\Pages;

use Livewire\Component;
use App\Notifications\ContactFormSubmitted;
use Illuminate\Support\Facades\Notification;

class Jobs extends Component
{
    public $name;
    public $email;
    public $message;
    public $status;

    // Methode zur Verarbeitung der Bewerbung
    public function submitApplication()
    {
        // Validierung der Eingabefelder
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string',
        ]);

        try {
            $adminEmail = 'lucas@zacharias-net.de';

            // Benachrichtigung an den Administrator senden (die gleiche Notification wie beim Kontaktformular)
            Notification::route('mail', $adminEmail)
                ->notify(new ContactFormSubmitted($this->name, $this->email, 'Jobbewerbung', $this->message));

            // Erfolgsmeldung
            $this->status = 'Deine Bewerbung wurde erfolgreich gesendet!';

            // Felder zurücksetzen
            $this->reset(['name', 'email', 'message']);
        } catch (\Swift_TransportException $e) {
            session()->flash('error', 'Die Bestätigungs-E-Mail konnte nicht gesendet werden. Bitte überprüfen Sie Ihre E-Mail-Adresse oder versuchen Sie es später erneut.');
        } catch (\Exception $e) {        
            session()->flash('error', 'Ein Fehler ist aufgetreten. Bitte versuchen Sie es später erneut.');
        }
    }

    public function render()
    {
        return view('livewire.pages.jobs')->layout('layouts.app');
    }
}

