@extends('admin.layout.layout')
@section('title', '素材--添加')
@section('sidebar')
@section('content')
<a href="{{url('admin/ticketindex')}}">展示</a>
    <h3 style="text-align:center"  >渠道添加</h3>   
    <form class="form-horizontal" action="{{url('admin/addticket')}}" method="post" role="form" >
        @csrf
            <div class="form-group has-success">
                <label class="col-sm-1 control-label" for="inputSuccess">
                    渠道名称
                </label>
                <div class="col-sm-10">
                    <input type="text" name="channel_name"  class="form-control" id="inputSuccess">
                </div>
            </div>

            <div class="form-group has-success">
                <label class="col-sm-1 control-label" for="inputSuccess">
                    渠道标识
                </label>
                <div class="col-sm-10">
                    <input type="text" name="channel_status"  class="form-control" id="inputSuccess">
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
