<?php

namespace App\Tools;
use Illuminate\Support\Facades\Cache;

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
}
