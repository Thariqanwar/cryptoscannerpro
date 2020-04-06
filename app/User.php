<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\UserFeed;
use Auth;
class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'user_type', 'status','login_status', 'password_string', 'subscription_period','subscription_start','subscription_end','subscription_type','google2fa_secret','last_login_ip','last_login_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token','google2fa_secret'
    ];

    protected $primaryKey = 'id';

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

     public function isAdmin()
    {
        return ($this->user_type == 'admin');
    }
     public function isUser()
    {
        return ($this->user_type == 'user');
    }
    public function sub_period()
    {
        return $this->belongsTo('App\SubscriptionPeriod', 'subscription_period');
    }
    public function telegram_chat()
    {
        return $this->belongsTo('App\Model\Telegram_chat', 'telegram_chat_id');
    }
    public function feed()
    {
        return $this->HasMany('App\UserFeed', 'user_id');
    }
    public function feed_exist($id)
    {
        $count=UserFeed::where('feed_id',$id)->where('user_id',Auth::user()->id)->count();
        return $count;
    }
    public function subscription_details()
    {
        return $this->belongsTo('App\UserCategory', 'subscription_type');
    }
    public function widget_settings()
    {
        return $this->HasMany('App\UserWidgetSetting', 'user_id');
    }

        /**
     * Ecrypt the user's google_2fa secret.
     *
     * @param  string  $value
     * @return string
     */
    public function setGoogle2faSecretAttribute($value)
    {
         $this->attributes['google2fa_secret'] = encrypt($value);
    }

    /**
     * Decrypt the user's google_2fa secret.
     *
     * @param  string  $value
     * @return string
     */
    public function getGoogle2faSecretAttribute($value)
    {
        return decrypt($value);
    }
    public function price_convert($a)
    {
        //$a = "$ 1234567891";
        $a=explode('.',$a);
        $a=$a[0];
        $b = str_replace( ',', '', $a );
        $b = str_replace( '$', '', $b );
        $txt = str_replace( ' ', '', $b );

        if(strlen($txt)>3 && strlen($txt)<7)
        {
          
          $b=$txt/1000;
          $b=round($b,2).' K';
        }
        if(strlen($txt)>6 && strlen($txt)<10)
        {
          
          $b=$txt/1000000;
          $b=round($b,2).' M';
        }
        if(strlen($txt)>9 && strlen($txt)<13)
        {
          
          $b=$b/1000000000;
          $b=round($b,2).' B';
        }
        if(strlen($txt)>12 && strlen($txt)<16)
        {
          
          $b=$txt/1000000000000;
          $b=round($b,2).' T';
        }
        if(strlen($txt)>15 )
        {
          
          $b=$txt/1000000000000000;
          $b=round($b,2).' QD';
        }


        return $b;
    }

   
}
