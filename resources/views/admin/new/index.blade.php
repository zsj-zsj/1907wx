@extends('admin.layout.layout')
@section('title', '素材--展示')
@section('sidebar')
@section('content')


<table border="1">
    <tr>
        <td>ID</td>
        <td>新闻标题</td>
        <td>新闻内容</td>
        <td>作者</td>
        <td>时间</td>
        <td>访问量</td>
        <td>操作</td>
    </tr>
    @foreach ($res as $v)
    <tr>
        <td>{{$v->n_id}}</td>
        <td>{{$v->n_bt}}</td>
        <td>{{$v->n_nr}}</td>
        <td>{{$v->n_zz}}</td>
        <td>{{date('Y-m-d H:i:s',$v->n_time)}}</td>
        <td>{{$v->n_num}}</td>
        <td>
            <a href="{{url('newdel/'.$v->n_id)}}">删除</a>
            <a href="{{url('newupd/'.$v->n_id)}}">修改</a>
        </td>
    </tr>
    @endforeach
</table>
{{$res->links()}}

@endsection