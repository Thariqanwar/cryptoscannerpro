<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Auth;

class SignalCategory extends Model
{
    protected $table = 'signal_category';
    public $timestamps = false;
    
    // public function feeds()
    // {
    //     return $this->hasMany('App\Feed','category');
    // }
}
