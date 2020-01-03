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
        $m_url=request()->m_url;

        if(!request()->hasFile('m_url')){     
                 echo "请上传图片谢谢！";die;
        }
        $path=$m_url->store('upload');
        // dd($path);

        $access_token=Wechat::getAccessToken();
        $url='https://api.weixin.qq.com/cgi-bin/media/upload?access_token='.$access_token.'&type=image';
        
        $pathObj= new \CURLFile($path);    //处理curl 发送的文件
    // dd($path);
        $PostData['media']=$pathObj;
        // dd($PostData);
        $res=Curl::CurlPost($url,$PostData);
        // dd($res);die;
        $json=json_decode($res,true);
        // dd($json);
        $post=$json['media_id'];
        $post=request()->except('_token');
        
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
