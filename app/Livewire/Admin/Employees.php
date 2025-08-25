<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;

class Employees extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind'; // Verwende Tailwind für die Pagination

    public function render()
    {
        // Lade alle Benutzer mit der Rolle Admin (Rolle wird hier als Beispiel angenommen)
        $employees = User::where('role', 'admin') // Filter für Admin-Rolle
            ->orderBy('created_at', 'desc') // Sortiere nach Erstellungsdatum, neueste zuerst
            ->paginate(10); // 10 Einträge pro Seite

        return view('livewire.admin.employees', [
            'employees' => $employees,
        ])->layout('layouts.master');
    }
}
