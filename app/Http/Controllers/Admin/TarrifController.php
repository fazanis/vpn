<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tariff;
use Illuminate\Http\Request;

class TarrifController extends Controller
{
    public function index ()
    {
        $tarrifs= Tariff::paginate(20);
    
        return view('admin.terrifs.index',compact('tarrifs'));
    }

    public function create()
    {
        return view('admin.terrifs.create');
    }

    public function store(Request $request)
    {
        Tariff::create($request->all());
        return redirect()->route('tarrifs.index');
    }
    public function edit(Tariff $tarrif)
    {
        return view('admin.terrifs.create',compact('tarrif'));
    }

    public function update(Request $request,Tariff $tarrif)
    {
        $tarrif->update($request->all());
        return redirect()->route('tarrifs.index');
    }
}
