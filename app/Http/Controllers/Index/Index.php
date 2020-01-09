<?php

namespace App\Http\Controllers\Index;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Tools\Curl;

class Index extends Controller
{
    //主页
    public function index()
    {
        return view('admin/admin/index');
    }

    public function weater(){
        return view('admin/admin/weater');
    }

    public function getWeater(){
        $city=request()->city;
        $url="http://api.k780.com/?app=weather.future&weaid=".$city."&&appkey=47849&sign=e81267f4e38b5f4ab04eab868bfdd1f7&format=json";
        
        $weater=Curl::CurlGet($url);
        return $weater;

        // $urls=json_decode($weater,true);
        // dd($urls);
        
        //  if($urls['success']==0){
        //      return redirect('admin/weater')->with('msg','没有此城市');
        // }

        // $week="";   //星期
        // $temp_high="";   //天气前
        // // $temp_low="";   //天气后
        // foreach($urls['result'] as $v){
        //     $week .="'".$v['week']."',";
        //     $temp_high .="[".$v['temp_high'].",".$v['temp_low']."],";
        // }
        // $week=rtrim($week,',');
        // $temp_high=rtrim($temp_high,',');
        // $arr=['week'=>$week,'temp_high'=>$temp_high];
        // echo json_encode($arr);
        // dd($temp_high);
    }
}
