<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class UserModel extends Model
{
    
    public $primaryKey='u_id';
    protected $table="user";

    protected $guarded=[];

}
