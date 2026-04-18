<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::query()->where('is_admin', false)->paginate(20);
        return view('admin.users.index',compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        User::create($request->all());
        return redirect()->route('admin.users.index');
    }

    public function edit(User $user)
    {
        return view('admin.users.create',compact('user'));
    }
    public function update(User $user, Request $request)
    {
        $user->update($request->all());
        return redirect()->route('admin.users.index');
    }
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->back();
    }
}
