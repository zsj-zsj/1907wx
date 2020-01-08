@extends('admin.layout.layout')
@section('title', '素材--展示')
@section('sidebar')
@section('content')
{{-- <h1><a href="{{url('admin/index')}}">主页</a></h1> --}}
<h3 style="text-align:center"  >渠道展示</h3>
<a href="{{url('admin/ticket')}}">添加</a>   
<table class="table table-hover table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>渠道名称</th>
                <th>渠道标识</th>
                <th>二维码</th>
                <th>关注人数</th>
                <th>操作</th>
            </tr>
        </thead>
        @foreach($data as $v)
        <tbody>
            
            <tr class="active" >
                <td>{{$v->channel_id}}</td>
                <td>{{$v->channel_name}}</td>
                <td>{{$v->channel_status}}</td>
                <td><img src="{{$v->ticket}}" width="100px" ></td>
                <td>{{$v->num}}</td>
                <td>
                      <a href="">删除</a>  
                </td>
            </tr>
        </tbody>
        @endforeach
    </table>

@endsection