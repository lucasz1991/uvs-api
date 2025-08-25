<?php

namespace App\Jobs;

use App\Models\Mail;
use App\Notifications\MailNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $mail;

    /**
     * Create a new job instance.
     */
    public function __construct(Mail $mail)
    {
        $this->mail = $mail;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

    }
}
