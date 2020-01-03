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
                <th>添加时间</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $v)
            <tr class="active" >
                <td>{{$v->m_id}}</td>
                <td>{{$v->m_name}}</td>
                <td>@if($v->m_type==1) 临时素材 @else 永久素材 @endif</td>
                <td> <img src="{{env('APP_URL')}}{{$v->m_url}} " width="100" height="40"></td>
                <td>{{$v->format}}</td>
                <td>{{date('Y-m-d H:i:s',$v->time)}}</td>
                <td>
                      <a href="">删除</a>  
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>


@endsection