<?php

namespace App\Http\Controllers\Wx;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;


use App\Model\WxUserModel;  //用户
use App\Model\MediaModel;   //素材

use GuzzleHttp\Client;
use App\Tools\Curl;

use App\Model\Ticket;
//微信公共方法
use App\Tools\Wechat;

class WxController extends Controller
{
    //连接测试号
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

    //关注回复  图片语音视频
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
        // dd($user);
        $u=WxUserModel::where('openid','=',$openid)->first();  //根据openid 查一条 
        // dd($u);
        
        // channel_status 标识          接 标识 的字段 qr_scene_str   他俩一样  判断
        
        //关注事件
        if($Event=='subscribe'){
          if($u){
            $eventKey=$xml->EventKey;         //接受 <EventKey><![CDATA[qrscene_222]]></EventKey>  类型   
            $channel_status=$user['qr_scene_str']; 
            WxUserModel::where('openid','=',$openid)->update(['is_del'=>1,'channel_status'=>$channel_status]);
            Ticket::where('channel_status','=',$channel_status)->increment('num');
            Wechat::echomsg($openid,$ToUserName,"欢迎回来");
          }else{
            $data=[                //入库的数据
              'nickname'=>$user['nickname'],
              'sex'=>$user['sex'],
              'head'=>$user['headimgurl'],
              'openid'=>$user['openid'],       
              'time'=>$user['subscribe_time'],
              'city'=>$user['city'],
              'channel_status'=>$user['qr_scene_str'],
              'is_del'=>1     //关注是1
            ];
            $eventKey=$xml->EventKey;         //接受 <EventKey><![CDATA[qrscene_222]]></EventKey>  类型   
            $channel_status=$user['qr_scene_str']; 
            $u=WxUserModel::insert($data);
            Ticket::where('channel_status','=',$channel_status)->increment('num');
            Wechat::echomsg($openid,$ToUserName,date('Y-m-d H:i:s').'：欢迎关注~@'.$user['nickname']);
          }
        }
        //取关
        if($Event=='unsubscribe'){
          //根据openid 查一列 的一个字段 
          $u=WxUserModel::where('openid','=',$openid)->get('channel_status')->first()->toArray();    
          Ticket::where('channel_status','=',$u)->decrement('num');    //自减
          WxUserModel::where('openid','=',$openid)->update(['is_del'=>2]); 
          // $delete=WxUserModel::where('openid','=',$openid)->delete();
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
            $this->downloadImg($MediaId,$MsgType);       //调方法  保存用户发过来的素材

            $img=MediaModel::where('format','=','image')->get();      //根据库里 format字段 查image类型
            $sss=json_decode($img,true);             //转数组
            $aaa=array_column($sss,'media_id');      // 去这个数组  media_id 这一列
            $ll=array_rand($aaa);      //随机取
            $kkk=$aaa[$ll];        //去这一列的键
            $image='<xml>
            <ToUserName><![CDATA['.$openid.']]></ToUserName>
            <FromUserName><![CDATA['.$ToUserName.']]></FromUserName>
            <CreateTime>'.time().'</CreateTime>
            <MsgType><![CDATA[image]]></MsgType>
            <Image>
              <MediaId><![CDATA['.$kkk.']]></MediaId>
            </Image>
          </xml>';
          echo $image;
        }elseif($MsgType=='voice'){
          $this->downloadImg($MediaId,$MsgType);       //调方法  保存用户发过来的素材
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
        }elseif($MsgType=='video'){
          $this->downloadImg($MediaId,$MsgType);       //调方法  保存用户发过来的素材
        }
    }


    //保存图片     下载用户发送过来的图片
    protected function downloadImg($MediaId,$MsgType){
      $access_token=Wechat::getAccessToken();
      $url='https://api.weixin.qq.com/cgi-bin/media/get?access_token='.$access_token.'&media_id='.$MediaId;
      $img=file_get_contents($url);

      if($MsgType=='image'){
        $imgname=date('YmdHis').rand(111,999).'.jpg';
        $imgurl='material/img/'.$imgname;
        file_put_contents($imgurl,$img);
      }elseif($MsgType=='voice'){
        $voicename=date('YmdHis').rand(111,999).'.amr';
        $voiceurl='material/voice/'.$voicename;
        file_put_contents($voiceurl,$img);
      }elseif($MsgType=='video'){
        $video=date('YmdHis').rand(111,999).'.mp4';
        $videourl='material/video/'.$video;
        file_put_contents($videourl,$img);
      }
    }


    //创建菜单
    public function menu(){
      $access_token=Wechat::getAccessToken();
      $url='https://api.weixin.qq.com/cgi-bin/menu/create?access_token='.$access_token;
      

      $menu=[
        'button'=>[
            [
              'type'=>'location_select',
              'name'=>'发送位置',
              'key'=>'asdffg'
            ],
            [
              'type'=>'scancode_waitmsg',
              'name'=>'扫码',
              'key'=>'rselfmenu_0_0'
            ],
        
            [
              'name'=>'菜单',
              'sub_button'=>[
                [
                  'type'=>'view',
                  'name'=>'签到',
                  'url'=>'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.env('APPID').'&redirect_uri='.urlEncode('http://www.zsjshaojie.top/auth').'&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect'
                ],
                [
                  'type'=>'scancode_waitmsg',
                  'name'=>'扫码',
                  'key'=>'rselfmenu_0_0'
                ],
                [
                  'type'=>'pic_sysphoto',
                  'name'=>'拍照',
                  'key'=>'uytyr'
                ]
              ]     
          ]
        ] 
      ];
      $json=json_encode($menu,JSON_UNESCAPED_UNICODE);
      $menuget=Curl::CurlPost($url,$json);
      
      $menus=json_decode($menuget,true);
      dd($menus);

    }

    //群发
    public function semdAllOpenid(){
      $user=WxUserModel::get()->toArray();
      $openid=array_column($user,'openid');
      // dd($openid);

      $data=[
          'touser'=>$openid,
            'msgtype'=>'text',
            'text'=>[
              'content'=>date('Y-m-d H:i:s')."大家好"
            ]
      ];
      // dd($data);
      $datas=json_encode($data,JSON_UNESCAPED_UNICODE);
      // dd($datas);

      $access_token=Wechat::getAccessToken();
      $url='https://api.weixin.qq.com/cgi-bin/message/mass/send?access_token='.$access_token;

      $send=Curl::CurlPost($url,$datas);

      echo $send;
    }

    //git自动拉取
    public function gilPull(){
      $git="cd /data/wwwroot/default/weixin1907 && git pull ";
      shell_exec($git);
    }


    //获取  用户同意授权，获取code
    public function code(){
      $redis_key='checkin:'.date('Y-m-d');   //测试  看看存了个什么  
      // echo $redis_key;die;

      $redirect_uri=urlEncode('http://www.zsjshaojie.top/auth');
      $url='https://open.weixin.qq.com/connect/oauth2/authorize?appid='.env('APPID').'&redirect_uri='.$redirect_uri.'&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect';
      // dd($url);
      echo $url;
    }

    public function auth(){
      //通过code换取网页授权access_token
      $code=$_GET['code'];
      $url='https://api.weixin.qq.com/sns/oauth2/access_token?appid='.env('APPID').'&secret='.env('APPSECRET').'&code='.$code.'&grant_type=authorization_code';
      $json=file_get_contents($url);
      $data=json_decode($json,true);
      // dd($data);

      //拉取用户信息
      $urls='https://api.weixin.qq.com/sns/userinfo?access_token='.$data['access_token'].'&openid='.$data['openid'].'&lang=zh_CN';
      $jsons=file_get_contents($urls);
      $arr=json_decode($jsons,true);      //用户信息
      print_r($arr);


      //实现签到 记录用户签到
      $redis_key='checkin:'.date('Y-m-d');  //设置redis
      Redis::Zadd($redis_key,time(),$arr['openid']);   //将openid加入有序集合
      echo $arr['nickname']."~签到成功：".date('Y-m-d H:i:s');



    }





  }