<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserFeed extends Model
{
    protected $table = 'user_feeds';
    public function feedsData()
    {
        return $this->belongsTo('App\Feed','feed_id');
    }
}
