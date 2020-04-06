<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserWidgetSetting extends Model
{
    protected $table = 'user_widget_settings';

    public $timestamps=false;

    public function widget()
    {
        return $this->belongsTo('App\Widget', 'widget_id');
    }
    
}
