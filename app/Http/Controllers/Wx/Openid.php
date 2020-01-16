<?php

namespace App\Http\Controllers\Wx;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\UserModel;
use Illuminate\Support\Facades\Hash;


class Openid extends Controller
{
    public function aaa(){
        $url='https://open.weixin.qq.com/connect/oauth2/authorize?appid='.env('APPID').'&redirect_uri='.urlEncode('http://www.zsjshaojie.top/openid/code').'&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect';
        echo $url;
    }

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
        // print_r($openid);
        // UserModel::create($openid);
        // $this->doindex($openid);
        session(['openid'=>$openid]);
        return view('admin/openid/login'); 
    }

    public function index(){
        return view('admin/openid/login');   
    }

    public function doindex(){

        $post=request()->except('_token');
        // dd($post);
        $openids=session('openid');
        $where[]=['u_name','=',$post['u_name']];
        $res=UserModel::where($where)->first();
            if($res){
                if(Hash::check($post['u_pwd'],$res['u_pwd'])){
                    //密码相等
                    UserModel::where($where)->update(['openid'=>$openids]);
                    echo "绑定成功";
                }else{
                    //密码不相等 
                    return redirect('openid/index')->with('aaa','密码不正确');
                }
            }else{
                return redirect('openid/index')->with('bbb','用户不存在');
            }
    }
    
}
