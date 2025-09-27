<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendaftaranModel extends Model
{
    use HasFactory;

    protected $table = 'a_pendaftaran';

    protected $primaryKey = 'id_pendaftaran';

    protected $fillable = [
        'tahun',
        'id_tahun',
        'tahun',
        'nisn',
        'tgl_daftar',
        'nama_lengkap',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'agama',
        'alamat',
        'status_siswa',
        'kelas',
        'jurusan',
        'asal_sekolah',
        'tahun_masuk',
        'kategori_biaya',
        'pengurangan_biaya',
        'biaya_lain',
        'biaya_pendaftaran',
        'no_hp',
        'email',
    ];

    // Aktifkan timestamps
    public $timestamps = true;

    // Relasi ke parameter SPP
    public function spp()
    {
        // Jika kategori_biaya = kode_spp di par_spp
        return $this->belongsTo(ParSPPModel::class, 'kategori_biaya', 'tahun');
        // Kalau kategori_biaya = id di par_spp, ganti jadi:
        // return $this->belongsTo(Parspp::class, 'kategori_biaya', 'id');
    }

    public function siswa()
    {
        return $this->hasOne(SiswaModel::class, 'nisn', 'nisn');
    }

    public function tagihanLain()
    {
        return $this->hasMany(TagihanLainModel::class, 'id_pendaftaran', 'id_pendaftaran');
    }
}
