<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $table = 'users';
    protected $fillable = [
        'nama',
        'nim',
        'prodi',
        'jenis_kelamin',
        'fakultas',
        'username',
        'password',
    ];
    protected $hidden = [
        'password',
    ];
    public $timestamps=false;
}
