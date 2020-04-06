<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BillingAddress extends Model
{
    protected $table = 'billing_address';
    public function BillData()
    {
        return $this->belongsTo('App\User','user_id');
    }
}
