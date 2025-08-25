<?php

namespace App\Livewire\Admin\Users\Messages;

use Livewire\Component;
use App\Models\User;
use App\Models\Mail;
use Illuminate\Support\Facades\DB;
use Livewire\WithFileUploads;
use Livewire\Attributes\On;
use App\Models\File;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class MessageForm extends Component
{
    use WithFileUploads;

    public $selectedUsers = [];

    public $showMailModal = false; 
    public $mailUserId = null;
    public $mailWithMail = false;
    public $mailSubject = ''; 
    public $mailHeader = '';
    public $mailBody = '';
    public $mailLink = '';
    public array $fileUploads = [];

    #[On('openMailModal')]
    public function handleOpenMailModal($payload = null): void
    {
        // Fall A: Einzeluser (Parent sendet int)
        if (is_numeric($payload)) {
            $this->mailUserId = (int) $payload;
            $this->showMailModal = true;
            return;
        }

        // Fall B: Bulk (Parent sendet Array mit IDs)
        if (is_array($payload) && ! empty($payload)) {
            // IDs normalisieren
            $ids = array_values(array_unique(array_map('intval', $payload)));
            $this->selectedUsers = $ids;
            $this->mailUserId = null;
            $this->showMailModal = true;
            return;
        }

        // Nichts Brauchbares erhalten
        $this->dispatch('showAlert', 'Bitte wähle mindestens einen Benutzer aus.', 'info');
    }



    public function resetMailModal()
    {
        $this->showMailModal = false;
        $this->mailUserId = null;
        $this->mailWithMail = false;
        $this->mailSubject = '';
        $this->mailHeader = '';
        $this->mailBody = '';
        $this->mailLink = '';
        $this->fileUploads = [];
    }

    public function sendMail()
    {
        // Validierung mit individuellen Fehlermeldungen
        $this->validate([
            'mailSubject' => 'required|string|max:255',
            'mailHeader' => 'required|string|max:255',
            'mailBody' => 'required|string',
            "fileUploads"     => ['array'],
            "fileUploads.*"   => ['file','max:2048'], // 3 MB je Datei
        ], [
            'mailSubject.required' => 'Bitte geben Sie einen Betreff ein.',
            'mailSubject.max' => 'Der Betreff darf maximal 255 Zeichen lang sein.',
            'mailHeader.required' => 'Bitte geben Sie eine Überschrift ein.',
            'mailHeader.max' => 'Die Überschrift darf maximal 255 Zeichen lang sein.',
            'mailBody.required' => 'Bitte geben Sie eine Nachricht ein.',
            'fileUploads.*.max'    => 'Eine Datei überschreitet die maximale Größe von 2 MB.',
        ]);
    
        // Inhalte für die Datenbank vorbereiten
        $content = [
            'subject' => $this->mailSubject,
            'header' => $this->mailHeader,
            'body' => $this->mailBody,
            'link' => $this->mailLink, // Link kann optional leer sein
        ];
    
        if ($this->mailUserId) {
            // Einzelner Benutzer
            $user = User::find($this->mailUserId);
    
            if ($user) {
                // Mail speichern
                $mail = Mail::create([
                    'type' => $this->mailWithMail ? 'both' : 'message',
                    'status' => false,
                    'content' => $content,
                    'recipients' => [
                        [
                            'user_id' => $user->id,
                            'email' => $user->email,
                            'status' => false, // Status für den Empfänger
                        ],
                    ],
                ]);
    
                $this->dispatch('showAlert', 'E-Mail wurde zur Verarbeitung an ' . $user->email . ' vorbereitet.', 'success');
            } else {
                $this->dispatch('showAlert', 'Benutzer nicht gefunden.', 'error');
            }
        } else {
            // Massenverarbeitung
            $recipients = [];
    
            foreach ($this->selectedUsers as $userId) {
                $user = User::find($userId);
                if ($user) {
                    $recipients[] = [
                        'user_id' => $user->id,
                        'email' => $user->email,
                        'status' => false, // Status für jeden Empfänger
                    ];
                }
            }
    
            // Mail speichern
            $mail = Mail::create([
                'type' => $this->mailWithMail ? true : false,
                'status' => false,
                'content' => $content,
                'recipients' => $recipients,
            ]);
    
            $this->dispatch('showAlert', 'E-Mail wurde zur Verarbeitung für ' . count($recipients) . ' Benutzer vorbereitet.', 'success');
        }
        foreach ($this->fileUploads as $uploadedFile) {
            $filename = $uploadedFile->getClientOriginalName();
            $path     = $uploadedFile->store('uploads/files', 'public');
            $mime     = Storage::disk('public')->mimeType($path) ?? $uploadedFile->getClientMimeType();

            $mail->files()->create([
                'name'       => $filename,
                'path'       => $path,
                'mime_type'  => $mime,
                'size'       => $uploadedFile->getSize(),
                'expires_at' => null,
            ]);
        }
        
        // Modal zurücksetzen
        $this->resetMailModal();
    }

    public function render()
    {
        return view('livewire.admin.users.messages.message-form');
    }
}
