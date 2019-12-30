<?php

namespace App\Http\Controllers\Wx;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WxController extends Controller
{
    public function checkSignature()
    {
        $token = '12345678asdfgh';
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        $echostr=$_GET["echostr"];
        
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );
        
        if( $tmpStr == $signature ){
            return $echostr;
        }else{
            die;
        }
    }

    public function wxdo(){
        $file=file_get_contents("php://input");
        $data=date('Y-m-d H:i:s').$file;
        file_put_contents('1907wx.log',$data);
        $xml=simplexml_load_string($file);


        


        $ToUserName=$xml->ToUserName;  //开发者的公众号ID
        $openid=$xml->FromUserName;    //获取用户的openid

        $MsgType=$xml->MsgType;     //消息类型
        $MediaId=$xml->MediaId;     //通过素材管理中的接口上传多媒体文件，得到的id。
        $Content=$xml->Content;
           
        if($MsgType=='text'){
            $texts=date('Y-m-d H:i:s').'：'.$Content;
            $text='<xml>
            <ToUserName><![CDATA['.$openid.']]></ToUserName>
            <FromUserName><![CDATA['.$ToUserName.']]></FromUserName>
            <CreateTime>'.time().'</CreateTime>
            <MsgType><![CDATA[text]]></MsgType>
            <Content><![CDATA['.$texts.']]></Content>
          </xml>';
          echo $text;
        }elseif($MsgType=='image'){
            $image='<xml>
            <ToUserName><![CDATA['.$openid.']]></ToUserName>
            <FromUserName><![CDATA['.$ToUserName.']]></FromUserName>
            <CreateTime>'.time().'</CreateTime>
            <MsgType><![CDATA[image]]></MsgType>
            <Image>
              <MediaId><![CDATA['.$MediaId.']]></MediaId>
            </Image>
          </xml>';
          echo $image;
        }elseif($MsgType=='voice'){
            $voice='<xml>
            <ToUserName><![CDATA['.$openid.']]></ToUserName>
            <FromUserName><![CDATA['.$ToUserName.']]></FromUserName>
            <CreateTime>'.time().'</CreateTime>
            <MsgType><![CDATA[voice]]></MsgType>
            <Voice>
              <MediaId><![CDATA['.$MediaId.']]></MediaId>
            </Voice>
          </xml>';
          echo $voice;
        }
        

    }


}
