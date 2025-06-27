<?php

namespace App\Jobs;

use App\Mail\WelcomeMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class WelcomeMailJob implements ShouldQueue
{
    use Queueable;

    private $newUser;
    public function __construct($newUser)
    {
        $this->newUser = $newUser;
        
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->newUser->email)->send(new WelcomeMail([$this->newUser]));
    }
}
