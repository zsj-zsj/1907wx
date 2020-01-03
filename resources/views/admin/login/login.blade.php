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
                    <input type="email" class="form-control" name="u_name" placeholder="用户名" required="">
                    <b style="color:red"> @php echo $errors->first('u_name'); @endphp </b>
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" name="u_pwd" placeholder="密码" required="">
                    <b style="color:red"> @php echo $errors->first('u_pwd'); @endphp </b>
                </div>
                <button type="submit" class="btn btn-primary block full-width m-b">登 录</button>


                <p class="text-muted text-center"> <a href="login.html#"><small>忘记密码了？</small></a> | <a href="{{url('reg')}}">注册一个新账号</a>
                </p>

            </form>
        </div>
    </div>
    @endsection
