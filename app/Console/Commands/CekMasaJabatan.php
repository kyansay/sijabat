<?php

namespace App\Console\Commands;

use App\Models\Pejabat;
use App\Models\EmailLog; // Tambahkan ini
use App\Mail\NotifKenaikanJabatan;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Exception;

class CekMasaJabatan extends Command
{
    protected $signature = 'app:cek-masa-jabatan';
    protected $description = 'Cek pejabat yang sudah 4 tahun dan kirim email notifikasi';

    public function handle()
    {
        $tanggalTarget = now()->subYears(4)->format('Y-m-d');

        $pejabats = Pejabat::whereDate('tmt_jabatan', $tanggalTarget)
            ->whereNotNull('email')
            ->get();

        if ($pejabats->isEmpty()) {
            $this->info('Tidak ada jadwal notifikasi hari ini.');
            return;
        }

        foreach ($pejabats as $pejabat) {
            try {
                // Proses kirim email
                Mail::to($pejabat->email)->send(new NotifKenaikanJabatan($pejabat));

                // Jika berhasil, catat ke log
                EmailLog::create([
                    'pejabat_id' => $pejabat->id,
                    'email_tujuan' => $pejabat->email,
                    'status' => 'Berhasil',
                    'keterangan' => 'Notifikasi 4 Tahun Terkirim'
                ]);

                $this->info("Berhasil: Email ke {$pejabat->nama}");

            } catch (Exception $e) {
                // Jika gagal, catat error-nya ke log
                EmailLog::create([
                    'pejabat_id' => $pejabat->id,
                    'email_tujuan' => $pejabat->email,
                    'status' => 'Gagal',
                    'keterangan' => $e->getMessage() // Pesan error dari server
                ]);

                $this->error("Gagal: Email ke {$pejabat->nama}");
            }
        }

        $this->info('Proses pengiriman dan pencatatan log selesai.');
    }
}
