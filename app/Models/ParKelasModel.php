<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParKelasModel extends Model
{
    use HasFactory;

    public $table = 'par_kelas'; 
    protected $primaryKey = 'id_kelas';      

    protected $fillable = [
        'kelas',
        'jurusan',
 
    ];

    // Jika ingin otomatis timestamps
    public $timestamps = true;
}