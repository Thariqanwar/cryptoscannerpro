<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubscriptionAmount extends Model
{
    protected $table = 'subscription_amount';
    public $timestamps = false;

    public function plan()
    {
        return $this->belongsTo('App\UserCategory', 'user_type');
    }
    public function plan_period()
    {
        return $this->belongsTo('App\SubscriptionPeriod', 'period');
    }
}
