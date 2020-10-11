<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransaksiBuku extends Model
{
    protected $table = 'transaksibuku';
    protected $primaryKey = 'noTransaksi'; 
    public $incrementing = false;
    public $timestamps = false;
}