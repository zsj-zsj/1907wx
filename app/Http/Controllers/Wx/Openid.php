<?php

namespace App\Http\Controllers\Wx;

use Illuminate\Support\Facades\Cache;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\UserModel;
use Illuminate\Support\Facades\Hash;
use App\Tools\Wechat;
use App\Tools\Curl;

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
        return redirect('openid/index');
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




    //展示二维码
    public function loginewm(){
        $status=time().rand(111,999);
        $url="http://www.zsjshaojie.top/openid/sscan?wysq=".$status;
        return view('admin/login/ewm',['url'=>$url,'status'=>$status]);
    }

    public function wysq(){
        $id=request('status');
        $redirect_uri=urlEncode('http://www.zsjshaojie.top/openid/sscan');
      $url='https://open.weixin.qq.com/connect/oauth2/authorize?appid='.env('APPID').'&redirect_uri='.$redirect_uri.'&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect';
    }

    public function sscan(){
        $id=request('status');
        // $redirect_uri=urlEncode('http://www.zsjshaojie.top/openid/sscan');
    //   $url='https://open.weixin.qq.com/connect/oauth2/authorize?appid='.env('APPID').'&redirect_uri='.$redirect_uri.'&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect';
        // dd($url);
      $code=$_GET['code'];

      $url='https://api.weixin.qq.com/sns/oauth2/access_token?appid='.env('APPID').'&secret='.env('APPSECRET').'&code='.$code.'&grant_type=authorization_code';
      $json=file_get_contents($url);
      $data=json_decode($json,true);
      // dd($data);

      //拉取用户信息
      $urls='https://api.weixin.qq.com/sns/userinfo?access_token='.$data['access_token'].'&openid='.$data['openid'].'&lang=zh_CN';
      $jsons=file_get_contents($urls);
      $arr=json_decode($jsons,true);      //用户信息
    //   $arr['openid'];
      Cache::put('WxLogin_'.$id,$arr['openid'],10);
        return '扫码成功,请等待PC端跳转';
    }



    public function weixinlogin(){
        $status=request('status');
      
        // $user=UserModel::get()->toArray();
        
        // $openid=array_column($user,'openid');
        $openid=Cache::get('WxLogin_'.$status);

        if(!$openid){
            return json_encode(['ret'=>0,'msg'=>'请先绑定']);
        }
        return json_encode(['ret'=>1,'msg'=>'正在登陆中']);

    }

    
}
