<?php

namespace App\Model;
use Illuminate\Database\Eloquent\Model;
use Eloquent;

class Cron_status extends Eloquent 
{
    protected $table = 'cron_status';
    protected $primaryKey = 'id';
    public $timestamps = false;
}

