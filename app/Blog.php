<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    protected $table = 'blog';
    public static function image_exist($filename)
    {
        $exist=Blog::where('image',$filename)->count();
        return $exist;

    }
    public function tagString()
    {
        
        $tag_array=unserialize($this->tags);
        $tags=implode(',', $tag_array);
        return $tags;

    }
}
