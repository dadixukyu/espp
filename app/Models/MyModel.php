<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MyModel extends Model
{
    /** @use HasFactory<\Database\Factories\MyModelFactory> */
    use HasFactory;

    public function My_Tahun_Ajaran()
    {
        return DB::table('par_tahun_ajaran')
            ->select('*')
            ->where('status', 'aktif')
            ->orderBy('id_tahun', 'desc')
            ->get();
    }


}

 