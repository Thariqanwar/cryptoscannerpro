<?php

namespace App\Model;
use Illuminate\Database\Eloquent\Model;
use Eloquent;

class Wall extends Eloquent 
{
    protected $table = 'bigchief_tbl_wall';
    protected $primaryKey = 'id';
    public $timestamps = false;
}

