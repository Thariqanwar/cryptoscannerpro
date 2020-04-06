<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentDetails extends Model
{
    protected $table = 'payment_details';
    public $timestamps = true;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id','email', 'payment_status', 'txn_id', 'start_time', 'expire_time', 'amount', 'payment_address','payback_address','subscription_type','subscription_period','coin_type','status'
    ];

     public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
    public function user_category()
    {
        return $this->belongsTo('App\UserCategory', 'subscription_type');
    }
    public function period()
    {
        return $this->belongsTo('App\SubscriptionPeriod', 'subscription_period');
    }
}
