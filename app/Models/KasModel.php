<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KasModel extends Model
{
    protected $table = 'c_kas';

    protected $primaryKey = 'id_kas';

    protected $fillable = [
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
    ];
}
