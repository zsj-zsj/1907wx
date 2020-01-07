<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    public $primarkey="channel_id";
    protected $table="channel";
    public $timestamps= false;
    protected $guarded=[];   //黑名单
}
