<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class MsgModel extends Model
{
    public $primarkey="id";
    protected $table="wx_msg";
    public $timestamps= false;
    protected $guarded=[];   //黑名单
}
