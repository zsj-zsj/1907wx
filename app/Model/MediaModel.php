<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class MediaModel extends Model
{
    public $primarkey="m_id";
    protected $table="media";
    public $timestamps= false;
    protected $guarded=[];   //黑名单
}
