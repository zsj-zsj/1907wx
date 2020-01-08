<?php

namespace App\Http\Controllers\Index;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Ticket as TicketModel;
use App\Tools\Wechat;

use App\Model\UserModel;

class Ticket extends Controller
{
    public function index(){
        $data=TicketModel::get();
        return view('admin/ticket/index',['data'=>$data]);
    }

    public function create(){
        return view('admin/ticket/create');
    }

    public function store(){
    
        $post=request()->except('_token');
        $channel_status=$post['channel_status'];

        $ticket=Wechat::ewm($channel_status);

        $data=[
            'channel_status'=>$channel_status,
            'channel_name'=>$post['channel_name'],
            'ticket'=>$ticket
        ];

        

        $res=TicketModel::create($data);
        return redirect('admin/ticketindex');
    }


    public function table(){
        $data=TicketModel::select('channel_status','num')->get()->toArray();
        
        $num="";
        $channel_status="";
        foreach($data as $v){
            $channel_status .="'".$v['channel_status']."',";
            $num .=$v['num'].",";
        }
        // dd($num);
        //  $channel_status=rtrim($channel_status,',');
         $channel_status=substr($channel_status,0,-1);
        
        return view('admin/ticket/table',['channel_status'=>$channel_status,'num'=>$num]);
    }
}
