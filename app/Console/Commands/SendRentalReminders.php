<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ShelfRental;
use App\Notifications\RentalReminderNotification;
use Carbon\Carbon;

class SendRentalReminders extends Command
{
    /**
     * Der Name und die Signatur des Artisan-Kommandos.
     *
     * @var string
     */
    protected $signature = 'rental:send-reminders';

    /**
     * Die Beschreibung des Kommandos.
     *
     * @var string
     */
    protected $description = 'Sende Erinnerungen für Regalbuchungen basierend auf dem Start- oder Enddatum';

    /**
     * Ausführungslogik des Kommandos.
     */
    public function handle()
    {
        $this->info('Senden von Erinnerungen für Regalbuchungen gestartet...');

        $now = Carbon::now();

        // 3 Tage vor Beginn
        $this->sendRemindersForStart($now->copy()->addDays(3), 'Deine Regalbuchung beginnt in 3 Tagen!');

        // 1 Tag vor Beginn
        $this->sendRemindersForStart($now->copy()->addDay(1), 'Deine Regalbuchung beginnt morgen!');

        // 1 Tag vor Ende
        $this->sendRemindersForEnd($now->copy()->addDay(1), 'Deine Regalbuchung endet morgen!');

        $this->info('Erinnerungen wurden erfolgreich gesendet.');
    }

    /**
     * Sende Erinnerungen für Regalbuchungen vor dem Startdatum.
     *
     * @param Carbon $date
     * @param string $message
     */
    private function sendRemindersForStart(Carbon $date, $message)
    {
        $rentals = ShelfRental::whereDate('rental_start', $date->toDateString())
            ->whereIn('status', [1, 5]) // Bevorstehend nicht eingecheckt oder Bevorstehend eingecheckt
            ->get();

        foreach ($rentals as $rental) {
            try {
                $rental->customer->user->notify(new RentalReminderNotification($rental, $message));
                $this->info("Erinnerung für Regalbuchung ID {$rental->id} gesendet: $message");
            } catch (\Exception $e) {
                $this->error("Fehler beim Senden der Erinnerung für Regalbuchung ID {$rental->id}: " . $e->getMessage());
            }
        }
    }

    /**
     * Sende Erinnerungen für Regalbuchungen vor dem Enddatum.
     *
     * @param Carbon $date
     * @param string $message
     */
    private function sendRemindersForEnd(Carbon $date, $message)
    {
        $rentals = ShelfRental::whereDate('rental_end', $date->toDateString())
            ->whereIn('status', [2, 6]) // Aktiv oder Aktiv nicht eingecheckt
            ->get();

        foreach ($rentals as $rental) {
            try {
                $rental->customer->user->notify(new RentalReminderNotification($rental, $message));
                $this->info("Erinnerung für Regalbuchung ID {$rental->id} gesendet: $message");
            } catch (\Exception $e) {
                $this->error("Fehler beim Senden der Erinnerung für Regalbuchung ID {$rental->id}: " . $e->getMessage());
            }
        }
    }
}
