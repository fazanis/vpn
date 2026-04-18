<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Server;
use App\Models\ServerInbound;
use Illuminate\Http\Request;

class ServerInboundController extends Controller
{
    public function index(Server $server)
    {

        $inbounds = $server->inbounds()->paginate(15);
        return view('admin.inbounds.index',compact('inbounds'));
    }
}
