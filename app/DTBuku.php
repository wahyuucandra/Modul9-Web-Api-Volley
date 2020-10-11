<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\BaseModel;

class DTBuku extends BaseModel
{
    protected $table = 'dtbuku';
    protected $primaryKey = ['noTransaksi', 'idBuku'];
    public $incrementing = false;
    public $timestamps = false;
}