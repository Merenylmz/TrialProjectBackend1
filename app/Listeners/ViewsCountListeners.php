<?php

namespace App\Listeners;

use App\Events\ViewsCountEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ViewsCountListeners
{
    public function __construct()
    {
        //
    }

    public function handle(ViewsCountEvent $event): void
    {
        $event->model->views += 1;
        $event->model->save();
    }
}
