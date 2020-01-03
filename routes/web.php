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



//后台
Route::get('login','Login\Login@login'); //登录页面
Route::get('reg','Login\Login@reg');   //注册页面
Route::post('doreg','Login\Login@doreg');   //执行注册
Route::post('dologin','Login\Login@dologin'); //执行登录



Route::prefix('/admin')->group(function(){
    //主页
    Route::get('index','Index\Index@index');  //展示主页
    Route::get('zhanshi','Index\Index@zhanshi');  //主页中间部分

    //素材管理
    Route::get('media','Index\Media@create');      //展示素材添加
    Route::post('domedia','Index\Media@store');    //执行添加
    Route::get('medialist','Index\Media@index');      //素材展示

});



