<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Contact;
use Livewire\WithPagination;

class ManageContacts extends Component
{
    use WithPagination;

    public $name, $company, $email, $phone, $address, $city, $postal_code, $country, $category, $additional_data;
    public $contactId;
    public $showForm = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'company' => 'nullable|string|max:255',
        'email' => 'nullable|email|unique:contacts,email',
        'phone' => 'nullable|string|max:20',
        'address' => 'nullable|string|max:255',
        'city' => 'nullable|string|max:100',
        'postal_code' => 'nullable|string|max:20',
        'country' => 'nullable|string|max:100',
        'category' => 'nullable|string|max:100',
        'additional_data' => 'nullable|array',
    ];

    protected $listeners = ['contactsSaved' => 'refreshContacts'];

    public function refreshContacts()
    {
        $this->resetPage(); 
        $this->dispatch('showAlert', 'Kontake erfolgreich gespeichert.', 'success');
    }

    public function render()
    {
        return view('livewire.admin.manage-contacts', [
            'contacts' => Contact::latest()->paginate(20)
        ])->layout('layouts.master');
    }

    public function create()
    {
        $this->resetFields();
        $this->showForm = true;
    }

    public function store()
    {
        $this->validate();
        Contact::create($this->allFields());
        $this->resetFields();
    }

    public function edit($id)
    {
        $contact = Contact::findOrFail($id);
        $this->fill($contact->toArray());
        $this->contactId = $id;
        $this->showForm = true;
    }

    public function update()
    {
        $this->validate();
        Contact::findOrFail($this->contactId)->update($this->allFields());
        $this->resetFields();
    }

    public function delete($id)
    {
        Contact::findOrFail($id)->delete();
    }

    private function resetFields()
    {
        $this->reset(['name', 'company', 'email', 'phone', 'address', 'city', 'postal_code', 'country', 'category', 'additional_data', 'contactId', 'showForm']);
    }

    private function allFields()
    {
        return [
            'name' => $this->name,
            'company' => $this->company,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'city' => $this->city,
            'postal_code' => $this->postal_code,
            'country' => $this->country,
            'category' => $this->category,
            'additional_data' => $this->additional_data ?? [],
        ];
    }
}
