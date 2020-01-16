<?php

namespace App\Http\Controllers\Wx;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Openid extends Controller
{
    public function index(){
        return view('admin/openid/login');
    }
}
