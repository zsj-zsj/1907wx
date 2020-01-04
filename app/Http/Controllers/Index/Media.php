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
        $data=MediaModel::get();


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
        // $ccc=$m_url->store('');
        // dd($ccc);
        
        $file=$m_url->getClientOriginalExtension ();   //文件后缀名名
        $filename=md5(uniqid()).'.'.$file;   //加密  
        $path=$m_url->storeAs('upload',$filename);    //入库的路径  
        // dd($path);
          
    //     $access_token=Wechat::getAccessToken();
    //     $type=$post['format'];     //   三种格式   voice   image  video
    //     $url='https://api.weixin.qq.com/cgi-bin/media/upload?access_token='.$access_token.'&type='.$type;
        
    //     $pathObj= new \CURLFile($path);    //处理curl 发送的文件
    // // dd($path);
    //     $PostData['media']=$pathObj;
    //     // dd($PostData);
    //     $res=Curl::CurlPost($url,$PostData);
    //     // dd($res);die;
    //     $json=json_decode($res,true);
    //     // dd($json);
    //     // $post=$json['media_id'];
        // $type=$post->format; 
        // dd($type);
        $json=Wechat::media($post,$path);
        
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
