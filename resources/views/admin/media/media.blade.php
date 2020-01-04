@extends('admin.layout.layout')
@section('title', '素材--添加')
@section('sidebar')
@section('content')
<h1><a href="{{url('admin/index')}}">主页</a></h1>
    <h3 style="text-align:center"  >素材添加</h3>   
    <form class="form-horizontal" action="{{url('admin/domedia')}}" method="post" role="form" enctype="multipart/form-data">
        @csrf
            <div class="form-group has-success">
                <label class="col-sm-1 control-label" for="inputSuccess">
                    素材名称
                </label>
                <div class="col-sm-10">
                    <input type="text" name="m_name"  class="form-control" id="inputSuccess">
                </div>
            </div>

            <div class="form-group has-success">
                <label class="col-sm-1 control-label" for="inputSuccess">
                    素材文件
                </label>
                <div class="col-sm-10">
                    <input type="file" name="m_url"  class="form-control" id="inputSuccess">
                    <b style="color:red">{{session('msg')}}</b>
                </div>
            </div>
            
            <div class="form-group has-success">
                <label class="col-sm-1 control-label" for="inputSuccess">
                    素材类型
                </label>
                <div class="col-sm-10">
                        <select name="m_type" class="form-control" >	
                                <option value="0">请选择</option>
                                <option value="1">临时素材</option>	
                                <option value="2">永久素材</option>	
                        </select>
                </div>
            </div>

            <div class="form-group has-success">
                <label class="col-sm-1 control-label" for="inputSuccess">
                    素材格式
                </label>
                <div class="col-sm-10">
                    <select name="format" class="form-control" >	
                        <option value="0">请选择</option>
                        <option value="image">图片</option>
                        <option value="voice">语音</option>	
                        <option value="video">视频</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10" >
                    <input type="submit" class="btn btn-danger" value="添加">
                </div>
            </div>
        </form>
    </form>
    
@endsection