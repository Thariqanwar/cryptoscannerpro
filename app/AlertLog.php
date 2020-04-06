<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AlertLog extends Model
{
    protected $table = 'alert_logs';

    public function signal()
    {
        return $this->belongsTo('App\Signal','category');
    }
}

