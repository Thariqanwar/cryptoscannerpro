<?php

namespace App\Model;
use Illuminate\Database\Eloquent\Model;
use Eloquent;

class Telegram_chat extends Eloquent 
{
    protected $table = 'tbl_telegram_chat';
    protected $primaryKey = 'id';
    public $timestamps = false;
}

