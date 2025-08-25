<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminDashboard extends Component
{
   
    public $totalUsers;
    public $activeUsers;
    public $activeUsersHistory = []; // Array für die letzten 10 aktiven Benutzer
  



    public function mount()
    {
        $this->updateDataSet();
        $this->getActiveUsersHistory();
    }

    // Diese Methode holt die Anzahl der aktiven Benutzer in den letzten 10 Stunden
    public function getActiveUsersHistory()
    {
        $activeUsersHistory = [];

        // Hole die aktiven Benutzer für die letzten 10 Stunden
        for ($i = 0; $i < 10; $i++) {
            // Berechne den Zeitstempel für jede Stunde
            $timePoint = Carbon::now()->subHours($i)->minute(0)->second(0); // Zeitstempel für den Beginn jeder Stunde

            // Zähle die aktiven Benutzer in dieser Stunde
            $activeUsersCount = DB::table('sessions')
                ->join('users', 'sessions.user_id', '=', 'users.id')
                ->where('users.role', 'guest')
                ->whereBetween('sessions.last_activity', [
                    $timePoint->timestamp, // Beginn der Stunde
                    $timePoint->copy()->addHour()->timestamp // Ende der Stunde
                ])
                ->count();

            // Füge die Anzahl der aktiven Benutzer in das Array ein
            $activeUsersHistory[] = $activeUsersCount;
        }

        // Optional: Umkehren der Reihenfolge, sodass der neueste Zustand am Ende ist
        $this->activeUsersHistory = array_reverse($activeUsersHistory);
    }

    public function updateDataSet()
    {

    }


    public function render()
    {
        return view('livewire.admin-dashboard')->layout('layouts.master');
    }
}
