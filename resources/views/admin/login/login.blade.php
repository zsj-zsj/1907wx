@extends('admin.layout.layout')

@section('title', '登录')
@section('sidebar')
@section('content')

    <div class="middle-box text-center loginscreen  animated fadeInDown">

        <div>
            <div>

                <h1 class="logo-name">h</h1>

            </div>
            <h3>欢迎使用 hAdmin</h3>
             <b style="color:red">{{session('aaa')}}</b>  
            <form class="m-t" role="form" method="post" action="{{url('dologin')}}">
                @csrf
                
                <div class="form-group">
                    <input type="text" class="form-control" id="name" name="u_name" placeholder="用户名" required="">
                    <b style="color:red"> @php echo $errors->first('u_name'); @endphp </b>
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" id="pwd" name="u_pwd" placeholder="密码" required="">
                    <b style="color:red"> @php echo $errors->first('u_pwd'); @endphp </b>
                </div>

                <div class="form-group">
                    <input type="text" class="form-control" name="code"  placeholder="验证码" required="">
                    <b style="color:red">{{session('hhhh')}}</b>  
                    <button type="button" id="yzm" class="btn btn-primary block full-width m-b">微信验证码</button>
                </div>

                <button type="submit" class="btn btn-primary block full-width m-b">登 录</button>

                <p class="text-muted text-center"><a href="{{url('reg')}}">注册一个新账号</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="{{url('openid/loginewm')}}">扫码登陆</a>
                </p>

            </form>
        </div>
    </div>

    {{-- 获取验证码 --}}
    <script>
        
            $(document).on('click','#yzm',function(){
                $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
                // alert(1);
                var name=$("#name").val();
                var pwd=$("#pwd").val();
                $.ajax({
                    method:"POST",
                    data:{name:name,pwd:pwd},
                    url:"{{url('getcode')}}"
                }).done(function(res){
                    alert(res);
                })
            })
       
    </script>

@endsection

   
