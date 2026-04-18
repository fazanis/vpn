<?php

namespace App\Http\Controllers\Admin;

use App\Console\Commands\Sinc\DevisesSincCommand;
use App\Http\Controllers\Controller;
use App\Models\Devise;
use App\Models\SincJobs;
use Illuminate\Http\Request;

class DevisController extends Controller
{

 
    public function edit(Devise $devise)
    {
        return view('admin.devises.create',compact('devise'));
    }
    public function create($user)
    {
        return view('admin.devises.create',compact('user'));
    }
    public function index($user)
    {
        
        $devises = Devise::query()
        ->where('user_id',$user)
        ->get();
        return view('admin.devises.index',compact('devises','user'));
    }
    public function store(Request $request,$user)
    {
    
        $this->validate($request,[
            'name'=>'required'
        ]);
     
        $devise = Devise::query()->create([
            'name'=>$request->name,
            'user_id'=>$user,
        ]);
        
        SincJobs::query()->create([
            'command'=>DevisesSincCommand::class,
            'status'=>0,
            'entity_id'=>$devise->id
        ]);

        return back();
    }

    public function destroy(Devise $devise)
    {
        $devise->delete();
        return back();
    }
}
