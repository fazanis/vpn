<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function index()
    {
        return view('admin.login');
    }

    public function singin(Request $request)
    {
        $user = User::query()->where('email',$request->email)->first();

        if (!$user || !Hash::check($request->password,$user->password)) {
            return back()->withErrors(['error'=>'Неверный логин или пароль']);
        }
        auth()->login($user);

        return redirect()->route('admin.dashboard');
    }
}
