<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Mail;

class MailManagement extends Component
{
    use WithPagination;

    public $sortBy = 'id'; // Standard-Sortierfeld
    public $sortDirection = 'desc'; // Standard-Sortierreihenfolge

    /**
     * Sortiere die Mails nach dem ausgewählten Feld.
     */
    public function sortByField($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
    }

    /**
     * Erneutes Senden einer Mail.
     */
    public function resendMail($id)
    {
        $mail = Mail::find($id);

        if ($mail) {
            // Hier die Logik für den Mailversand einfügen, z. B. Job oder Notification ausführen.
            // Für jetzt: Status auf "resent" setzen.
            $mail->update([
                'status' => true, // Mail als gesendet markieren
            ]);

            session()->flash('message', 'Mail erfolgreich erneut gesendet.');
        } else {
            session()->flash('error', 'Mail nicht gefunden.');
        }
    }

    /**
     * Render-Methode.
     */
    public function render()
    {
        $mails = Mail::orderBy($this->sortBy, $this->sortDirection)
            ->paginate(10);

        return view('livewire.admin.mail-management', [
            'mails' => $mails,
        ])->layout('layouts.master');
    }
}
