<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;


class FeedsCategory extends Model
{
    protected $table = 'feeds_category';

    public function feeds()
    {
        return $this->hasMany('App\Feed','category');
    }
}

