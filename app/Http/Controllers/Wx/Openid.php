<?php

namespace App\Http\Controllers\Wx;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\UserModel;


class Openid extends Controller
{
    public function code(){
        $code=$_GET['code'];
        // dd($code);
        $url='https://api.weixin.qq.com/sns/oauth2/access_token?appid='.env('APPID').'&secret='.env('APPSECRET').'&code='.$code.'&grant_type=authorization_code';
        // dd($url);
        $json=file_get_contents($url);
        $data=json_decode($json,true);
        // print_r($data);
        $urls='https://api.weixin.qq.com/sns/userinfo?access_token='.$data['access_token'].'&openid='.$data['openid'].'&lang=zh_CN';
        $jsons=file_get_contents($urls);
        $arr=json_decode($jsons,true);      //用户信息
        $openid=$arr['openid'];
        dd($openid);
        // UserModel::create($openid);
        return redirect('openid/index');
    }

    public function index(){
        return view('admin/openid/login');   
    }
}
