<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParBiayaModel extends Model
{
    use HasFactory;

    public $table = 'par_biaya'; 
    protected $primaryKey = 'id_biaya';      

    protected $fillable = [
        'tahun',
        'nama_biaya',
        'nominal',
        'keterangan',
 
    ];

    // Jika ingin otomatis timestamps
    public $timestamps = true;
}