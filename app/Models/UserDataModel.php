<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDataModel extends Model
{
    use HasFactory;

    public $table = 'users';

    protected $primaryKey = 'id';

    protected $fillable = ['email', 'password', 'level'];
}
