<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Signal extends Model
{
    protected $table = 'signal';
    public $timestamps = false;

    public function category()
    {
    	return $this->belongsTo('App\SignalCategory','category_id');
    }
}
