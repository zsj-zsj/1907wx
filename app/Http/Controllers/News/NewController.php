<?php

namespace App\Http\Controllers\News;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Model\News;

class NewController extends Controller
{
    //展示
    public function  index(){
        $res=News::paginate(2);
        return view('admin/new/index',['res'=>$res]);
    }

    //添加页面
    public function create(){
        return view('admin/new/create');
    }

    public function store(){
        $post=request()->except('_token');
        $post['n_time']=time();

        $data=News::create($post);

        return redirect('newindex');
    }

    public function delete($id){
        $del=News::where('n_id','=',$id)->delete();
        
        return redirect('newindex');
    }

    public function edit($n_id){
        // dd($n_id);
        $res=News::where('n_id','=',$n_id)->first();
        return view('admin/new/edit',['res'=>$res]);
    }

    public function update($n_id){
        // dd($n_id);
        $post=request()->except('_token');
        $post['n_time']=time();

        $res=News::where('n_id','=',$n_id)->update($post);
        return redirect('newindex');

    }
}
