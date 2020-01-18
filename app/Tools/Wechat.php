<?php

namespace App\Tools;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use App\Tools\Curl;

class Wechat 
{


    //回复文本消息
    public static function echomsg($openid,$ToUserName,$Content){
        $huifu='<xml>
              <ToUserName><![CDATA['.$openid.']]></ToUserName>
              <FromUserName><![CDATA['.$ToUserName.']]></FromUserName>
              <CreateTime>'.time().'</CreateTime>
              <MsgType><![CDATA[text]]></MsgType>
              <Content><![CDATA['.$Content.']]></Content>
            </xml>';
            echo $huifu;
      }


      //获取accsee_token
      public static function getAccessToken(){
            //先判断是否有access_token
         $arr=Cache::get('access_token');
         //没有掉接口  获取   有return   直接返回
        if(empty($access_token)){
            $accesstoken='https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.env('APPID').'&secret='.env('APPSECRET').'';
            $access=file_get_contents($accesstoken);
            $access_token=json_decode($access,true);
            $arr=$access_token['access_token'];
    
            Cache::put('access_token',$arr,7200);
        }
        return  $arr;

            //存redis
            // $key='access_token';
            // $token=Redis::get($key);
            // if($token){
            //   return $token;
            // }
            // $accesstoken='https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.env('APPID').'&secret='.env('APPSECRET').'';
            // $access=file_get_contents($accesstoken);
            // $access_token=json_decode($access,true);
            // $arr=$access_token['access_token'];
            // Redis::set($key,$arr);
            // Redis::expire($key,3600);
            // return $arr;

      }


      //获取用户信息
      public static function getUserInfoByOpenid($openid){
        $arrss=Self::getAccessToken();
        // dd($arrss);
        $urls='https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$arrss.'&openid='.$openid.'&lang=zh_CN';
        // dd($urls);
        $aaa=file_get_contents($urls);
        // dd($aaa);
        $user=json_decode($aaa,true);
        // dd($user);
        return $user;
      }

      //获取素材media_id
      public static function media($post,$path){
        $access_token=Self::getAccessToken();
        $type=$post['format'];     //   三种格式   voice   image  video
        $url='https://api.weixin.qq.com/cgi-bin/media/upload?access_token='.$access_token.'&type='.$type;
        // dd($url);
        
        $pathObj= new \CURLFile($path);    //处理curl 发送的文件
    // dd($path);
        $PostData['media']=$pathObj;
        // dd($PostData);
        $res=Curl::CurlPost($url,$PostData);
        // dd($res);die;
        $json=json_decode($res,true);
        // dd($json);
        // $post=$json['media_id'];
        return $json;
      }

      public static function ewm($channel_status){
        $access_token=Self::getAccessToken();
        // dd($access_token);
        $url='https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token='.$access_token;  //地址
  
        // $Post='{"expire_seconds": 604800, "action_name": "QR_STR_SCENE", "action_info": {"scene": {"scene_str": "'.$channel_status.'"}}}';   //参数
   
        $Post=[
          'expire_seconds'=>2592000,
          'action_name'=>'QR_STR_SCENE',
          'action_info'=>[
            'scene'=>[
              'scene_str'=>$channel_status
            ]
          ]
            ];
          $Post=json_encode($Post,JSON_UNESCAPED_UNICODE);
        $res=Curl::CurlPost($url,$Post);   //post请求  掉方法  
        $json=json_decode($res,true);
        $ticket=$json['ticket'];
        $tck=UrlEncode($ticket);
        $tickets='https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.$tck;
        // return redirect($tickets);   返回一个二维码
        return $tickets;
      }
      
}
