<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pejabat extends Model
{
    // Menentukan kolom mana saja yang boleh diisi
    protected $fillable = [
        'nip',
        'email',
        'nama',
        'jabatan_sekarang',
        'rundown',
        'tmt_jabatan'
    ];
}