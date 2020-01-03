@extends('admin.layout.layout')
@section('title', '素材--展示')
@section('sidebar')
@section('content')
<h1><a href="{{url('admin/index')}}">主页</a></h1>
<h3 style="text-align:center"  >素材展示</h3>   
<table class="table table-hover table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>素材名称</th>
                <th>素材类型</th>
                <th>素材文件</th>
                <th>素材格式</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            <tr class="active" >

            </tr>
        </tbody>
    </table>


@endsection