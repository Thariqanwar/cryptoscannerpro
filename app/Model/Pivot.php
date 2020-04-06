<?php
namespace App\Model;
use Eloquent;
class Pivot extends Eloquent 
{
    protected $table = 'bigchief_tbl_pivot';
    protected $primaryKey = 'id';
    public $timestamps = false;        
}
?>