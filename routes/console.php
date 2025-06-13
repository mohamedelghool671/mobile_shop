<?php

use App\Models\User;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::call(function () {
  $users = User::where('created_at','<',now()->subDays(7))
  ->whereNotNull('email_verfiy_code')
  ->get();
  foreach($users as $user) {
        Log::info('un verified users deleted success '.$user->name);
        $user->delete();
  }
})->weekly();
