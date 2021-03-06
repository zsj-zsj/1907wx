<?php

namespace App\Tools;


class Curl 
{
    public static function CurlGet($url)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url); //设置请求地址
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 返回数据格式
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);//关闭https验证
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);//关闭https验证
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }

    public static function CurlPost($url,$PostData){
        $curl=curl_init();         //初始化
        //设置
        curl_setopt($curl,CURLOPT_URL,$url);  //设置抓取的curl
        curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);  //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl,CURLOPT_POST,1);    //设置post提交方式
        curl_setopt($curl,CURLOPT_POSTFIELDS,$PostData);   //设置post数据
        curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,false);  //http有没有s

        $data=curl_exec($curl);   //执行
       
        curl_close($curl);     //关闭

        return $data;   //返回数据
    }
}
