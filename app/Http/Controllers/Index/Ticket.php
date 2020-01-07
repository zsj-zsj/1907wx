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
}
