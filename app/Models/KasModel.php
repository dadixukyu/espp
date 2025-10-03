<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KasModel extends Model
{
    protected $table = 'c_kas';

    protected $primaryKey = 'id_kas';

    protected $fillable = [
        'id_pendaftaran',
        'id_siswa',
        // 'bulan',
        'tahun',
        'tanggal',
        'kd_kas',
        'keterangan',
        'jumlah',
    ];

    public $timestamps = true;

    protected $casts = [
        'tanggal' => 'date',
        'jumlah' => 'decimal:2',
        // 'bulan' => 'array', // cast JSON ke array
    ];
}
