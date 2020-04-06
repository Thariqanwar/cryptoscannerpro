<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use App\UserFeed;
use App\FeedsCategory;

class Feed extends Model
{
    protected $table = 'feeds';

    public function feeds()
    {
        return $this->hasMany('App\FeedData')->orderBy('created_at','DESC');
    }

    public function isFeedEnabled()
    {
    	if(UserFeed::where('feed_id',$this->id)->where('user_id',Auth::user()->id)->exists())
    	{
    		return true;
    	}
        else
        { 
            return false; 
        }
    }
    public function className()
    {
        if(strpos($this->name, ' '))
        {
            return $class = preg_replace('/\s+/', '_', $this->name);
        }
        elseif(strpos($this->name, '.'))
        {
            return $class = preg_replace('/\./', '_', $this->name);
        }
        else
        {
            return $this->name;
        }
    }
    public function getCategory()
    {
        return $this->belongsTo('App\FeedsCategory','category');
    }
}
