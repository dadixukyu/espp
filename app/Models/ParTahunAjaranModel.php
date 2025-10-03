<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParTahunAjaranModel extends Model
{
    use HasFactory;

    protected $table = 'par_tahun_ajaran';

    protected $primaryKey = 'id_tahun';

    public $timestamps = true;

    protected $fillable = [
        'tahun',
        'tahun_awal',
        'tahun_akhir',
        'nama_ta',
        'status',
    ];

    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif')->orderBy('id_tahun', 'desc');
    }

    public function siswa()
    {
        return $this->hasMany(SiswaModel::class, 'id_tahun', 'id_tahun');
    }
}
