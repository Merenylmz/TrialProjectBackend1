<?php

use App\Jobs\DailyReportJob;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');



Schedule::call(function(){
    $blogsCount = DB::table("blogs")->count();
    $categoryCount = DB::table("categories")->count();
    $usersCount = DB::table("users")->count();


    $informationArray = [
        "blogsCount"=>$blogsCount,
        "categoryCount"=>$categoryCount,
        "usersCount"=>$usersCount,
    ];

    DailyReportJob::dispatch($informationArray);

})->daily();

