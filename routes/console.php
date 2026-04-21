<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


// Jalankan perintah pengecekan ini setiap hari pukul 08:00 pagi
Schedule::command('app:cek-masa-jabatan')->dailyAt('08:00');