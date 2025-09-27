<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TagihanSPPDetailModel extends Model
{
    use HasFactory;

    protected $table = 'b_tagihan_spp_detail';

    protected $primaryKey = 'id_detail';

    protected $fillable = [
        'id_tagihan',
        'bulan',
        'tahun',
        'tgl_bayar',
        'nominal_bayar',
        'metode_bayar',
        'keterangan',
    ];

    // Relasi ke tagihan (header)
    public function tagihan()
    {
        return $this->belongsTo(TagihanSPPModel::class, 'id_tagihan', 'id_tagihan');
    }

    // Scope filter by tahun
    public function scopeByTahun($query, $tahun)
    {
        return $query->where('tahun', $tahun);
    }

    // Scope filter by bulan
    public function scopeByBulan($query, $bulan)
    {
        return $query->where('bulan', $bulan);
    }

    // Scope filter by semester
    public function scopeBySemester($query, $semester)
    {
        if ($semester == 1) {
            return $query->whereBetween('bulan', [1, 6]);
        }

        return $query->whereBetween('bulan', [7, 12]);
    }
}
