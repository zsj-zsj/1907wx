@extends('admin.layout.layout')
@section('title', '素材--展示')
@section('sidebar')
@section('content')

<form action="{{url('newstore')}}" method="post">
    @csrf
    <table>
        <tr>
            <td>新闻标题</td>
            <td><input type="text" name="n_bt"></td>
        </tr>
        <tr>
            <td>新闻内容</td>
            <td><input type="text" name="n_nr"></td>
        </tr>
        <tr>
            <td>新闻作者</td>
            <td><input type="text" name="n_zz"></td>
        </tr>
        <tr>
            <td></td>
            <td><input type="submit" value="添加"></td>
        </tr>
    </table>
</form>


@endsection