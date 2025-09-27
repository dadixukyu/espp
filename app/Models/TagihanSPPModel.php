<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TagihanSPPModel extends Model
{
    use HasFactory;

    protected $table = 'b_tagihan_spp';

    protected $primaryKey = 'id_tagihan';

    protected $fillable = [
        'id_siswa',
        'bulan',
        'tahun',
        'nominal',
        'status_bayar',
        'tgl_bayar',
    ];

    // Relasi ke siswa
    public function siswa()
    {
        // pastikan nama model siswa sesuai (SiswaModel atau Siswa)
        return $this->belongsTo(SiswaModel::class, 'id_siswa', 'id_siswa');
    }

    // Relasi ke detail pembayaran
    public function detail()
    {
        return $this->hasMany(TagihanSPPDetailModel::class, 'id_tagihan', 'id_tagihan');
    }

    // ✅ Hitung total bayar dari semua detail
    public function getTotalBayarAttribute()
    {
        return $this->detail->sum('nominal_bayar');
    }

    // ✅ Hitung sisa tagihan
    public function getSisaTagihanAttribute()
    {
        return max(0, $this->nominal - $this->total_bayar);
    }

    // ✅ Scope untuk filter tahun
    public function scopeByTahun($query, $tahun)
    {
        return $query->where('tahun', $tahun);
    }

    // ✅ Scope untuk filter bulan
    public function scopeByBulan($query, $bulan)
    {
        return $query->where('bulan', $bulan);
    }

    // ✅ Scope untuk filter semester
    public function scopeBySemester($query, $semester)
    {
        if ($semester == 1) {
            return $query->whereBetween('bulan', [1, 6]); // Jan - Jun
        }

        return $query->whereBetween('bulan', [7, 12]); // Jul - Des
    }
}
