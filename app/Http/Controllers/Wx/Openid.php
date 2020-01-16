<?php

namespace App\Http\Controllers\Wx;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Openid extends Controller
{
    public function index(){
        $url='https://open.weixin.qq.com/connect/oauth2/authorize?appid='.env('APPID').'&redirect_uri='.urlEncode('http://www.zsjshaojie.top/openid/code').'&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect';
        echo $url;
        
    }

    public function code(){
        $code=$_GET['code'];
        dd($code);
        $url='https://api.weixin.qq.com/sns/oauth2/access_token?appid='.env('APPID').'&secret='.env('APPSECRET').'&code='.$code.'&grant_type=authorization_code';
        
        return view('admin/openid/login');
    }
}
