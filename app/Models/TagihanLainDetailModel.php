<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TagihanLainDetailModel extends Model
{
    protected $table = 'b_tagihan_lain_detail';

    protected $primaryKey = 'id_detail';

    protected $fillable = [
        'id_tagihan',
        'tgl_bayar',
        'nominal_bayar',
        'metode_bayar',
        'keterangan',
        'kode_transaksi',
    ];

    public function tagihan()
    {
        return $this->belongsTo(TagihanLainModel::class, 'id_tagihan');
    }
}
