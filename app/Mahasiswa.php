<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    protected $table = 'mahasiswa';
    protected $primaryKey = 'npm';
    protected $fillable = [
        'nama',
        'npm',
        'prodi',
        'jenis_kelamin',
        'fakultas',
        'gambar'
    ];
    public $timestamps=false;
}
