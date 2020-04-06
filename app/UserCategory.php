<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserCategory extends Model
{
    protected $table = 'user_category';
    public $timestamps=true;
    protected $fillable = [
        'category_type','signal_category','delay','feed_category','crypto_scanner','smart_trade'];
    public function amount()
    {
        return $this->HasMany('App\SubscriptionAmount', 'user_type')->orderBy('id','DESC');
    }
        
}
