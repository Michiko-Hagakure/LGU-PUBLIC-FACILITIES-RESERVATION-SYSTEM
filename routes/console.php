<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Jobs\SyncDataToCloud;
use Illuminate\Support\Facades\Schedule;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule automatic cancellation of overdue bookings

Schedule::command('bookings:cancel-overdue')
    ->hourly()
    ->withoutOverlapping()
    ->runInBackground();

Schedule::command('bookings:complete-finished')
    ->everyFifteenMinutes()
    ->withoutOverlapping()
    ->runInBackground();

Schedule::command('bookings:expire-unpaid')
    ->hourly()
    ->withoutOverlapping()
    ->runInBackground();

// This triggers the sync job every minute
Schedule::job(new SyncDataToCloud)->everyMinute();
