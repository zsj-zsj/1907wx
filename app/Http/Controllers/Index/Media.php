<?php

namespace App\Http\Controllers\Index;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Tools\Wechat;
use App\Tools\Curl;

use App\Model\MediaModel;

class Media extends Controller
{
    public function index(){
        $data=MediaModel::paginate(1);
        return view('admin.media.index',['data'=>$data]);
    }



    public function create(){
        return view('admin.media.media');
    }

    public function store(){
        $post=request()->except('_token');
        
        $m_url=request()->m_url;
 

        if(!request()->hasFile('m_url')){     
            return redirect('admin/media')->with('msg','请上传文件谢谢');
        }
        
        
        $file=$m_url->getClientOriginalExtension ();   //文件后缀名名


        // $tupian=['png','jpeg','jpg','gif'];
         
        // if(in_array($file,$tupian)){
        //     echo 1;die;
        // }
       

        $filename=md5(uniqid()).'.'.$file;   //加密  
        $path=$m_url->storeAs('upload',$filename);    //入库的路径  

        $json=Wechat::media($post,$path);

        if(!isset($json['media_id'])){
            return redirect('admin/media')->with('sss','素材文件要与素材格式一致');  
        }

        $data=[
            'media_id'=>$json['media_id'],
            'm_url'=>$path,
            'time'=>time(),
            'm_name'=>$post['m_name'],
            'format'=>$post['format'],
            'm_type'=>$post['m_type'],
        ];
        $data=MediaModel::create($data);


        return redirect('admin/medialist');

    }
}
