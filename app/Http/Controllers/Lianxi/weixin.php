<?php

namespace App\Http\Controllers\Lianxi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class weixin extends Controller
{
    public function weixin(){
        $echourl=$_GET['echostr'];
        echo $echourl;die;
    }
}
