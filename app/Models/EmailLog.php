<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailLog extends Model
{
    protected $fillable = [
        'pejabat_id',
        'email_tujuan',
        'status',
        'keterangan'
    ];

    // Relasi ke tabel Pejabat
    public function pejabat()
    {
        return $this->belongsTo(Pejabat::class);
    }
}
