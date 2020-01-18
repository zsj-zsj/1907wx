@extends('admin.layout.layout')

@section('title', '登录')
@section('sidebar')
@section('content')

    <div class="middle-box text-center loginscreen  animated fadeInDown">

        <div>
            <div>

                <h1 class="logo-name">h</h1>

            </div>
            <h3>欢迎使用扫码登录</h3>
            <img src="http://qr.liantu.com/api.php?text={{$url}}">
        </div>
    </div>

    <script>
        var t=setInterval("check();",2000);
        var status="{{$status}}";
        function check(){
            $.ajax({
                url:"{{url('openid/weixinlogin')}}",
                dataType:"json",
                data:{status:status},
                success:function(res){
                    if(res.ret==1){
                        clearInterval(t);
                        alert(res.msg);
                        location.href="{{url('admin/index')}}";
                    }
                }
            })
        }
    </script>


@endsection