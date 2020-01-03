<?php

namespace App\Http\Controllers\Wx;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Model\WxUserModel;  //用户
use App\Model\MediaModel;   //素材

//微信公共方法
use App\Tools\Wechat;

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
        file_put_contents('1907wx.log',$data,FILE_APPEND);
        $xml=simplexml_load_string($file);


        $ToUserName=$xml->ToUserName;  //开发者的公众号ID
        $openid=$xml->FromUserName;    //获取用户的openid

        $MsgType=$xml->MsgType;     //消息类型
        $MediaId=$xml->MediaId;     //通过素材管理中的接口上传多媒体文件，得到的id。
        $Content=trim($xml->Content);   //回复消息内容
        $Event=$xml->Event;      //事件类型   关注取关的
   

        $user=Wechat::getUserInfoByOpenid($openid);         //调用的方法  获取的用户信息是数组 返回要等于 

        $data=[
          'nickname'=>$user['nickname'],
          'sex'=>$user['sex'],
          'head'=>$user['headimgurl'],
          'openid'=>$user['openid'],
          'time'=>$user['subscribe_time'],
          'city'=>$user['city'],
        ];

        // dd($data);
        // echo 1;
        $u=WxUserModel::where('openid','=',$openid)->first();
        // echo 2;
        // dd($u);
        if($Event=='subscribe'){
          if($u){
            Wechat::echomsg($openid,$ToUserName,"欢迎回来");
          }else{
            $u=WxUserModel::insert($data);
            Wechat::echomsg($openid,$ToUserName,date('Y-m-d H:i:s').'：欢迎关注~@'.$user['nickname']);
          }
        }
        
        //  判断消息类型   回复消息  
        $student=["1","2","3","4","5"];
        if($MsgType=='text'){
          if($Content=='1'){
            $Content=implode(',',$student);
            Wechat::echomsg($openid,$ToUserName,$Content);
          }elseif($Content=='2'){
            shuffle($student);
            $Content=$student[0];
            Wechat::echomsg($openid,$ToUserName,$Content);
          }elseif(mb_strpos($Content,"天气" ) !== false ){
            //正确城市天气   
            $city=rtrim($Content,"天气"); 
            if(empty($city)){
              $city=$user['city'];
            }
            //获取天气的接口
            $url='http://api.k780.com/?app=weather.future&weaid='.$city.'&&appkey=47849&sign=e81267f4e38b5f4ab04eab868bfdd1f7&format=json';
            $weater=file_get_contents($url);   //发送get请求  接受xml数据
            $arr=json_decode($weater,true);    //转换数组
            
            //没有这个城市,  天气数据  返回0   回复消息:发什么回什么  
            //有 有效城市  返回1    回复城市天气
            if($arr['success']==0){
              Wechat::echomsg($openid,$ToUserName,date('Y-m-d H:i:s')."：".$Content."：请输入正确的城市+天气，然后可以获取当地天气");die;
            }elseif($arr['success']==1){
              $Content="";
              foreach($arr['result'] as $k=>$v){
                $Content .="日期：".$v['days']." " .$v['week']."，城市：".$v['citynm']."，气温：".$v['temperature']."\n";
              }
            }
            Wechat::echomsg($openid,$ToUserName,$Content); 
          }elseif($MsgType=='text'){
            Wechat::echomsg($openid,$ToUserName,date('Y-m-d H:i:s')."：".$Content);
          }
        }elseif($MsgType=='image'){
            $img=MediaModel::get();
            $sss=json_decode($img,true);
            
            
            $aaa=array_column($sss,'media_id');
            //$ccc = implode("|", $aaa);
            
            $ll=array_rand($aaa);
            $image='<xml>
            <ToUserName><![CDATA['.$openid.']]></ToUserName>
            <FromUserName><![CDATA['.$ToUserName.']]></FromUserName>
            <CreateTime>'.time().'</CreateTime>
            <MsgType><![CDATA[image]]></MsgType>
            <Image>
              <MediaId><![CDATA['.$ll.']]></MediaId>
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
