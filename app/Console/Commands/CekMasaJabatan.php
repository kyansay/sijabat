<?php

namespace App\Console\Commands;

use App\Models\Pejabat;
use App\Mail\NotifKenaikanJabatan;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class CekMasaJabatan extends Command
{
    protected $signature = 'app:cek-masa-jabatan';
    protected $description = 'Cek pejabat yang sudah 4 tahun dan kirim email notifikasi';

    public function handle()
    {
        // Ambil pejabat yang masa jabatannya tepat 4 tahun lalu dari hari ini
        $tanggalTarget = Carbon::now()->subYears(4)->format('Y-m-d');

        $pejabats = Pejabat::whereDate('tmt_jabatan', $tanggalTarget)
            ->whereNotNull('email')
            ->get();

        foreach ($pejabats as $pejabat) {
            Mail::to($pejabat->email)->send(new NotifKenaikanJabatan($pejabat));
            $this->info("Email terkirim ke: {$pejabat->nama}");
        }

        $this->info('Pengecekan selesai.');
    }
}