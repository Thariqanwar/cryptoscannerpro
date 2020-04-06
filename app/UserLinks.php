<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserLinks extends Model
{
    protected $table = 'user_links';
    public function feedsData()
    {
        return $this->belongsTo('App\User','user_id');
    }
}
