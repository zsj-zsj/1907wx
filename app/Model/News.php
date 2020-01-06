<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    public $primarkey="n_id";
    protected $table="new";
    public $timestamps= false;
    protected $guarded=[];   //黑名单
}
