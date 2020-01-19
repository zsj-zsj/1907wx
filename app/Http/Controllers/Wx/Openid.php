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
        $url="http://www.zsjshaojie.top/openid/sscan?status=".$status;
        return view('admin/login/ewm',['url'=>$url,'status'=>$status]);
    }


    public function sscan(){
        $id=request('status');

        $openid=$this->getOpenid();

        Cache::put('WxLogin_'.$id,$openid,10);
        return '扫码成功,请等待PC端跳转';
    }

    public function weixinlogin(){
        $status=request('status');
      
        $user=UserModel::get()->toArray();
        $openidsql=array_column($user,'openid');

        // dd($openidsql);
        $openid=Cache::get('WxLogin_'.$status);

        if(!$openid && !$openidsql ){
            return json_encode(['ret'=>0,'msg'=>'扫码失败或未绑定账号']);
        }
        return json_encode(['ret'=>1,'msg'=>'正在登陆中']);

    }


    

    /**
     * 网页授权获取用户openid
     * @return [type] [description]
     */
    public static function getOpenid()
    {
        //先去session里取openid 
        $openid = session('openid');
        //var_dump($openid);die;
        if(!empty($openid)){
            return $openid;
        }
        //微信授权成功后 跳转咱们配置的地址 （回调地址）带一个code参数
        $code = request()->input('code');
        if(empty($code)){
            //没有授权 跳转到微信服务器进行授权
            $host = $_SERVER['HTTP_HOST'];  //域名
            $uri = $_SERVER['REQUEST_URI']; //路由参数
            $redirect_uri = urlencode("http://".$host.$uri);  // ?code=xx
            // dd($redirect_uri);
            $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".env('APPID')."&redirect_uri={$redirect_uri}&response_type=code&scope=snsapi_base&state=STATE#wechat_redirect";
            header("location:".$url);die;
        }else{
            //通过code换取网页授权access_token
            $url =  "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".env('APPID')."&secret=".env('APPSECRET')."&code={$code}&grant_type=authorization_code";
            $data = file_get_contents($url);
            $data = json_decode($data,true);
            $openid = $data['openid'];
            //获取到openid之后  存储到session当中
            session(['openid'=>$openid]);
            return $openid;
            //如果是非静默授权 再通过openid  access_token获取用户信息
        }   
    }
}
