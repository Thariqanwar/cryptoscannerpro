<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Subscription;

class TimeFrame extends Model
{
    protected $table = 'time_frames';
    public $timestamps = false;

    public function subscription()
    {
    	return $this->hasMany('App\Subscription','time_frame');
    }
}
