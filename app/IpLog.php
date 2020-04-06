<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IpLog extends Model
{
    protected $table = 'ip_log';

    public $timestamps=true;
    public function logData()
    {
        return $this->belongsTo('App\User','user_id');
    }
}
