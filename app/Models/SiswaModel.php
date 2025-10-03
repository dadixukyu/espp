<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiswaModel extends Model
{
    use HasFactory;

    protected $table = 'a_siswa';

    protected $primaryKey = 'id_siswa';

    protected $fillable = [
        'id_tahun',
        'kd_data',
        'nisn',
        'nama_lengkap',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'agama',
        'alamat',
        'tahun_masuk',
        'kelas',
        'jurusan',
        'status_siswa',
        'nama_ayah',
        'pekerjaan_ayah',
        'nama_ibu',
        'pekerjaan_ibu',
        'nama_wali',
        'no_hp_wali',
        'alamat_wali',
        'kategori_biaya',
        'pengurangan_biaya',
        'biaya_lain',
        'email',
        'no_hp',
    ];

    public $timestamps = true;

    // Relasi ke parameter SPP
    public function spp()
    {
        // Jika kategori_biaya = kode_spp di par_spp
        return $this->belongsTo(ParSPPModel::class, 'kategori_biaya', 'tahun');
        // Kalau kategori_biaya = id di par_spp, ganti jadi:
        // return $this->belongsTo(Parspp::class, 'kategori_biaya', 'id');
    }

    public static function getByTahun($tahun, $idSiswa = null)
    {
        $query = self::where('tahun_masuk', $tahun);

        if (! empty($idSiswa)) {
            $query->where('id_siswa', $idSiswa);
        }

        return $query->get();
    }

    public function tagihanSpp()
    {
        return $this->hasMany(TagihanSPPModel::class, 'id_siswa', 'id_siswa');
    }
}
