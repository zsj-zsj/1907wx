@extends('admin.layout.layout')

@section('title', '登录')
@section('sidebar')
@section('content')

    <div class="middle-box text-center loginscreen  animated fadeInDown">
        <div>
            <div>
                <h1 class="logo-name">h</h1>

            </div>
            <form class="m-t" role="form" method="post" action="">
                @csrf
                
                <div class="form-group">
                    <input type="email" class="form-control" name="u_name" placeholder="用户名" required="">
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" name="u_pwd" placeholder="密码" required="">
                </div>
                <button type="submit" class="btn btn-primary block full-width m-b">绑定</button>
                </p>

            </form>
        </div>
    </div>
    @endsection