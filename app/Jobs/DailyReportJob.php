<?php

namespace App\Jobs;

use App\Mail\DailyReportMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class DailyReportJob implements ShouldQueue
{
    use Queueable;

    protected $data;
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to("m.erenyilmaz2828@gmail.com")->send(new DailyReportMail($this->data));
    }
}
