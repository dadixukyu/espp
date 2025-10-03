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
        'tahun',
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

    // di TagihanLainModel
    public function kas()
    {
        return $this->hasMany(KasModel::class, 'id_pendaftaran', 'id_pendaftaran');
    }

    public function pendaftaran()
    {
        return $this->belongsTo(PendaftaranModel::class, 'id_pendaftaran', 'id_pendaftaran');
    }
}
