<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;
use App\Models\User;

class LogActivityJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user;
    public $requestData;

    /**
     * Erhält den User (oder null für Gäste) und ein Array mit Request-Daten.
     */
    public function __construct($user, array $requestData)
    {
        $this->user = $user;
        $this->requestData = $requestData;
    }

    /**
     * Führt den Job aus.
     */
    public function handle()
    {
        $user = $this->user;
        $data = $this->requestData;

        // Beschreibung: Admin, User oder Gast
        $description = $user 
            ? ($user->isadmin() ? 'Admin' : 'User') 
            : 'Gast';

        // Event-Slug: z. B. POST-api-participants
        $eventSlug = $data['method'].'-'.Str::slug($data['path']);

        // Optional: Du kannst auch `log_name` setzen, z. B. "api"
        activity('api') // ← setzt `log_name`
            ->causedBy($user) // setzt causer_type + causer_id
            ->withProperties($data) // setzt JSON properties
            ->tap(function ($activity) use ($user, $eventSlug) {
                if ($user) {
                    $activity->subject_type = get_class($user);
                    $activity->subject_id = $user->id;
                }

                $activity->event = $eventSlug;
            })
            ->log("{$description} - used URL - {$data['full_url']} - {$data['method']}");
    }

}
