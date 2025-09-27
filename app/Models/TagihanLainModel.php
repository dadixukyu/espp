<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TagihanLainModel extends Model
{
    protected $table = 'b_tagihan_lain';   // nama tabel di database
    protected $primaryKey = 'id_tagihan';  // primary key tabel

    // kolom yang boleh diisi secara mass assignment
    protected $fillable = [
        'id_pendaftaran',
        'id_biaya',
        'tagihan',
        'sisa_tagihan',
        'status',
    ];

    public function biaya()
    {
        return $this->belongsTo(ParBiayaModel::class, 'id_biaya', 'id_biaya');
    }
    // relasi ke tabel detail pembayaran
    public function detail()
    {
        return $this->hasMany(TagihanLainDetailModel::class, 'id_tagihan');
    }
}