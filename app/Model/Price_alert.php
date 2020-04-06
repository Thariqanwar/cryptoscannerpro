<?php
namespace App\Model;
use Eloquent;
class Price_alert extends Eloquent 
{
    protected $table = 'bigchief_tbl_price_alert';
    protected $primaryKey = 'id';
    public $timestamps = false;        
}
?>