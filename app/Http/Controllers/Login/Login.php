<?php

namespace App\Http\Controllers\Login;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\UserModel;
use Illuminate\Support\Facades\Hash;

use App\Tools\Wechat;
use App\Tools\Curl;

class Login extends Controller
{
    //展示注册
    public function reg(){
        return view('admin.login.register');
    }

    //执行注册
    public function doreg(){
        request()->validate([
            'u_name'=>'required|unique:user|email',
            'u_pwd'=>'required',
            'u_pwds'=>'same:u_pwd'
        ],[
            'u_name.email'=>'邮箱格式不对',
            'u_name.required'=>'用户名不能为空',
            'u_name.unique'=>'用户名已存在',
            'u_pwds.same'=>'密码不一致',
            'u_pwd.required'=>'密码不能为空'
        ]);

        $post=request()->except('_token');
        $post['u_pwd']=bcrypt($post['u_pwd']);
        unset($post['u_pwds']);
        $res=UserModel::create($post);
        
        return redirect('login');

    }

    //展示登录
    public function login(){
        return view('admin.login.login');
    }

    //执行登录
    public function dologin(){
        $post=request()->except('_token');
        
        $where[]=['u_name','=',$post['u_name']];
        $res=UserModel::where($where)->first();

        $error_num=$res['error_num'];
        $error_num++;

        if($res){
            if(Hash::check($post['u_pwd'],$res['u_pwd'])){
                //密码相等
                if($error_num>=3 && time()-$res['error_time']<=3600){
                    $time=ceil((3600-(time()-$res['error_time']))/60);
                    $aaa='用户已锁定,您还有'.$time.'分钟后解锁';
                    return redirect('login')->with('aaa',$aaa);
                }
                $data=[
                    'error_num'=>0,
                    'error_time'=>0
                ];
                $session=session('code');
                if($session!=$post['code']){
                    return redirect('login')->with('hhhh','请输入相对正确的微信验证码');
                }
                UserModel::where(['u_name'=>$post['u_name']])->update($data);

                return redirect('admin/index');
            }else{
                //密码不相等   错3次  锁定一小时
                if($error_num>=3 && time()-$res['error_time']>=3600 ){
                    $data=[
                        'error_num'=>1,
                        'error_time'=>time()
                    ];
                    UserModel::where(['u_name'=>$post['u_name']])->update($data);
                    $aaa="密码错误，您还有2次机会";
                    
                }else{
                    //错三次锁定
                    if($error_num>=3){
                        $aaa="用户已锁定一小时";
                        return redirect('login')->with('aaa',$aaa);
                    }
                    $data=[
                        'error_num'=>$error_num,
                        'error_time'=>time()
                    ];
                    UserModel::where(['u_name'=>$post['u_name']])->update($data);
                    $num=3-$error_num;
                    $aaa='密码错误，您还有'.$num.'次机会';
                    return redirect('login')->with('aaa',$aaa);
                }
            }
        }else{
            return redirect('login')->with('aaa','用户不存在');
        }

        
        
        
    }

    public function getcode(){
        $name=request()->name;
        $pwd=request()->pwd;
        $userinfo=UserModel::where(['u_name'=>$name])->first();
        // dd($userinfo);
        if(Hash::check($pwd,$userinfo->u_pwd)){
            $openid=$userinfo->openid;
            $code=rand(111111,999999);
            session(['code'=>$code]);
            $this->sendCode($name,$openid,$code);
        }
    }
    
    public function sendCode($name,$openid,$code){
        $access_token=Wechat::getAccessToken();
        $url='https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.$access_token;
        $data=[
            'touser'=>$openid,
            'template_id'=>'4Is6A6iJjqOTqMepw6LSgwgWarzldrcE3zN5QGxgn30',
            'data'=>[
                'name'=>[
                    'value'=>$name,
                    'color'=>'#000000'
                ],
                'code'=>[
                    'value'=>$code,
                    'color'=>'#000000'
                ],
            ]
        ];
        $arr=json_encode($data,JSON_UNESCAPED_UNICODE);
        $code=Curl::CurlPost($url,$arr);
        print_r($code);

    }
}
