@extends('admin.layout.layout')

@section('title', '注册')
@section('sidebar')
@section('content')
    
    <div class="middle-box text-center loginscreen   animated fadeInDown">
        <div>
            <div>

                <h1 class="logo-name">H+</h1>

            </div>
            <h3>欢迎注册 H+</h3>
            <p>创建一个H+新账户</p>
            <form class="m-t" role="form" action="{{url('doreg')}}" method="post">
                @csrf
                <div class="form-group">
                    <input type="text" class="form-control" name="u_name" placeholder="请输入用户名" required="">
                    <b style="color:red">@php echo $errors->first('u_name');@endphp</b>
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" name="u_pwd" placeholder="请输入密码" required="">
                    <b style="color:red">@php echo $errors->first('u_pwd');@endphp</b>
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" name="u_pwds" placeholder="请再次输入密码" required="">
                    <b style="color:red">@php echo $errors->first('u_pwds');@endphp</b>
                </div>
                <div class="form-group text-left">
                    <div class="checkbox i-checks">
                        <label class="no-padding">
                            <input type="checkbox"><i></i> 我同意注册协议</label>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary block full-width m-b">注 册</button>

                <p class="text-muted text-center"><small>已经有账户了？</small><a href="{{url('login')}}">点此登录</a>
                </p>

            </form>
        </div>
    </div>

    @endsection
