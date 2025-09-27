<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParSPPModel extends Model
{
    public $table = 'par_spp';
    protected $primaryKey = "id";
    protected $fillable = ['tahun','nominal','keterangan'];
    public $timestamps = false;
}