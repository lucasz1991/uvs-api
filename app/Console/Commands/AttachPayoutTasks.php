<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Payout;
use App\Models\AdminTask;

class AttachPayoutTasks extends Command
{
    protected $signature = 'tasks:attach-payouts';
    protected $description = 'Erstellt AdminTasks für bereits vorhandene Auszahlungen, falls diese noch keinen zugehörigen Task haben.';

    public function handle()
    {
        $this->info("Starte das Erstellen von AdminTasks für bestehende Payouts...");

        $payouts = Payout::whereNotNull('shelf_rental_id')->get();
        $taskCount = 0;

        foreach ($payouts as $payout) {
            // Prüfen, ob bereits ein AdminTask für diese Auszahlung existiert
            $existingTask = AdminTask::where('shelf_rental_id', $payout->shelf_rental_id)
                ->where('task_type', 'Auszahlung')
                ->exists();

            if (!$existingTask) {
                AdminTask::create([
                    'task_type' => 'Auszahlung',
                    'description' => "Auszahlung für Regalbuchung #{$payout->shelf_rental_id} in Höhe von " . number_format($payout->amount, 2, ',', '.') . " € angefordert",
                    'status' => 0, // 0 = offen
                    'assigned_to' => null,
                    'shelf_rental_id' => $payout->shelf_rental_id,
                ]);

                $this->info("AdminTask für Regalbuchung #{$payout->shelf_rental_id} erstellt.");
                $taskCount++;
            }
        }

        if ($taskCount > 0) {
            $this->info("Insgesamt wurden {$taskCount} AdminTasks erstellt.");
        } else {
            $this->info("Es wurden keine neuen AdminTasks erstellt. Alle Payouts haben bereits eine Aufgabe.");
        }

        return Command::SUCCESS;
    }
}
