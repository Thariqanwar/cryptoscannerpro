<?php
namespace App\Model;
use Eloquent;
class Icos extends Eloquent 
{
    protected $table = 'bigchief_tbl_ico';
    protected $primaryKey = 'id';
    public $timestamps = false;
}
?>