<?php

namespace App\Tools;

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
}
