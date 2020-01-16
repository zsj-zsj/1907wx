<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('phpinfo',function(){
    phpinfo();
});

//微信
Route::get('wx','Wx\WxController@checkSignature');
Route::post('wx','Wx\WxController@wxdo');
Route::get('menu','Wx\WxController@menu');    //菜单
Route::any('gitpull','Wx\WxController@gilPull');    //git自动拉取


Route::get('downloadImg','Wx\WxController@downloadImg');    //保存用户发过来的消息
Route::get('semdAllOpenid','Wx\WxController@semdAllOpenid');    //保存用户发过来的消息


Route::get('code','Wx\WxController@code');   //获取用户授权code 
Route::get('auth','Wx\WxController@auth');   //接受code


//绑定账号
Route::get('openid/login','Wx\Openid@index');   //接受code
Route::get('openid/code','Wx\Openid@code');   //接受code
Route::get('openid/docode','Wx\Openid@docode');   //接受code



//后台
Route::get('login','Login\Login@login'); //登录页面
Route::get('reg','Login\Login@reg');   //注册页面
Route::post('doreg','Login\Login@doreg');   //执行注册
Route::post('dologin','Login\Login@dologin'); //执行登录


Route::prefix('/admin')->group(function(){
    //主页
    Route::get('index','Index\Index@index');  //展示主页
    Route::get('weater','Index\Index@weater');  //展示主页
    Route::get('getWeater','Index\Index@getWeater');  //展示主页
    //素材管理
    Route::get('media','Index\Media@create');      //展示素材添加
    Route::post('domedia','Index\Media@store');    //执行添加
    Route::get('medialist','Index\Media@index');      //素材展示

    //渠道管理
    Route::get('ticket','Index\Ticket@create');      //展示 
    Route::post('addticket','Index\Ticket@store');   //执行添加
    Route::get('ticketindex','Index\Ticket@index');  //列表
    Route::get('table','Index\Ticket@table');        //统计图
}); 







//练习
Route::get('newcreate','News\NewController@create');
Route::post('newstore','News\NewController@store');
Route::get('newindex','News\NewController@index');

Route::get('newdel/{n_id}','News\NewController@delete');

Route::get('newupd/{n_id}','News\NewController@edit');
Route::post('newupdate/{n_id}','News\NewController@update');


Route::get('wxnew','News\WxNew@checkSignature');
Route::post('wxnew','News\WxNew@WxNew');



