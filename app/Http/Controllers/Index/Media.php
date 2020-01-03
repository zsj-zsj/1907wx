<?php

namespace App\Http\Controllers\Index;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Media extends Controller
{
    public function index(){
        return view('admin.media.index');
    }



    public function create(){
        return view('admin.media.media');
    }





    
}
