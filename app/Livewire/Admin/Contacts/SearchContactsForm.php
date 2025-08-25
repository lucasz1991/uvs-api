<?php

namespace App\Livewire\Admin\Contacts;

use Livewire\Component;
use App\Http\Controllers\Admin\ContactController;
use Livewire\WithFileUploads;
use DOMDocument;
use App\Models\Contact;



class SearchContactsForm extends Component
{
    use WithFileUploads;

    public $showModal = false;
    public $file;
    public $contactController;
    public $articles = [];
    public $tempContacts = [];
    public $fileContent = '';

    public function openModal()
    {
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->file = null;
        $this->fileContent = '';
        $this->articles = [];
        $this->tempContacts = [];
    }
    

    public function analyzeFile()
    {
        if (!$this->file) {
            return;
        }

        // Datei validieren (nur .html erlaubt)
        $this->validate([
            'file' => 'required|mimes:html,htm|max:10120', // max. 10MB
        ]);
        // Controller aufrufen
        $contactController = new ContactController();
        $this->articles = $contactController->analyzehtml($this->file);
    }


    public function convertToContacts()
    {
        $contactController = new ContactController();
        $this->tempContacts = $contactController->analyzeArticles($this->articles);
    }

    public function saveContacts()
    {
        if (empty($this->tempContacts)) {
            return;
        }

        foreach ($this->tempContacts as $contact) {
            Contact::updateOrCreate(
                ['email' => $contact['mail']], 
                [
                    'name'       => $contact['Name'],           
                    'phone'      => $contact['Tel_Nummer'],     
                    'address'    => $contact['Anschrift'],      
                    'website'    => $contact['website'],        
                    'category'   => $contact['Branche'],        
                ]
            );
        }
        $this->closeModal();
        $this->dispatch('contactsSaved');
    }


    public function render()
    {
        return view('livewire.admin.contacts.search-contacts-form');
    }
}
