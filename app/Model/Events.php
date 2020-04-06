<?php
namespace App\Model;
use Eloquent;
class Events extends Eloquent 
{
    protected $table = 'bigchief_tbl_events';
    protected $primaryKey = 'id';
    public $timestamps = false;        
    protected $dates = ['event_created_on'];
}
?>