<?php

namespace App\Http\Controllers\News;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Tools\Wechat;
use App\Model\News;

class WxNew extends Controller
{
    public function checkSignature()
    {
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        $echostr=$_GET["echostr"];
        $token = '12345678asdfgh';
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

    public function WxNew(){
        $file=file_get_contents("php://input");
        $data=date('Y-m-d H:i:s').$file;
        file_put_contents('new.log',$data,FILE_APPEND);
        $xml=simplexml_load_string($file);


        $ToUserName=$xml->ToUserName;  //开发者的公众号ID
        $openid=$xml->FromUserName;    //获取用户的openid
        $Event=$xml->Event;   //类型  关注去管的

        $MsgType=$xml->MsgType;    // 消息类型

        $Content=$xml->Content;

        $user=Wechat::getUserInfoByOpenid($openid);    //用户信息
        // dd($user);
 
        if($user['sex']==1){
            $sex='先生';
        }elseif($user['sex']==2){
            $sex='女士';
        }else{
            echo "";
        }

        if($Event=='subscribe'){
            $nickname=$user['nickname']; //性别
            $xinxi=$nickname.$sex;
             $aaa='欢迎'.$xinxi.'关注';
            $huifu='<xml>
              <ToUserName><![CDATA['.$openid.']]></ToUserName>
              <FromUserName><![CDATA['.$ToUserName.']]></FromUserName>
              <CreateTime>'.time().'</CreateTime>
              <MsgType><![CDATA[text]]></MsgType>
              <Content><![CDATA['.$aaa.']]></Content>
            </xml>';
            echo $huifu;
        }

  
        if($MsgType=='text'){
            if($Content=='新闻'){
                $n=News::get();
                $new=json_decode($n,true);
                $biaoti=array_column($new,'n_bt');
                $bt=implode(",",$biaoti);

                $Content="新闻有：".$bt."。这几个标题";
                Wechat::echomsg($openid,$ToUserName,$Content);
            }elseif($Content=='最新新闻'){
                $n=News::orderBy('n_time','desc')->first();
                $Content="新闻标题:".$n->n_bt."\n"."新闻内容:".$n->n_nr."\n"."新闻作者:".$n->n_zz;
                
                Wechat::echomsg($openid,$ToUserName,$Content);
            }elseif(mb_strpos($Content,"新闻")!==false){
                $new=rtrim($Content,"新闻");

                $bt=News::where([['n_bt','like',"%$new%"]])->get()->toArray();
                
                if(!empty($bt)){
                    $Content="";
                    foreach ($bt as $v){
                        News::where('n_id',$v['n_id'])->increment('n_num');
                        $Content="新闻标题:".$v['n_bt']."\n"."新闻内容:".$v['n_nr']."\n"."新闻作者:".$v['n_zz'];
                    }

                    Wechat::echomsg($openid,$ToUserName,$Content);

                }else{
                    $Content="无新闻";
                    Wechat::echomsg($openid,$ToUserName,$Content);
                }
            }
        }
    }

}
