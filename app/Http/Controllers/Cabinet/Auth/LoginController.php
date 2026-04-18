<?php

namespace App\Http\Controllers\Cabinet\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

use function Pest\Laravel\session;

class LoginController extends Controller
{
    public function index(Request $request)
    {        
    
        return view('cabinet.login');
    }
    public function singin(Request $request)
    {        
       
        $this->validate($request,[
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required'],
        ]);
       $user = User::query()->where('email',$request->email)->first();
        
        if (!$user || !Hash::check($request->password,$user->password)) {
            return back()->with(['error'=>'Неверный логин или пароль']);
        }

        if (!$user->hasVerifiedEmail()) {
        return redirect()->route('login')->with([
            'error' => 'Подтвердите email перед входом',
            'resend' => true,
            'email'=>$user->email,
        ]);
    }
        auth()->login($user);
     
        return redirect()->route('cabinet.dashboard');
    }

    public function register(Request $request)
    {
        $this->validate($request,[
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', 'min:6'],
        ]);
  
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            ]);
        
        $ref = User::where('referral_code',request()->cookie('ref'))->first();
          
        if($ref){
            $user->update(['referred_by'=>request()->cookie('ref')]);
        }
        event(new Registered($user));
        
        return redirect()->route('login')
            ->with(['success'=>"Проверьте email {$user->email} для подтверждения аккаунта"]);
    }

    public function verify(Request $request)
    {
        $user = User::findOrFail($request->route('id'));
  
    

        if (! hash_equals(
            (string) $request->route('hash'),
            sha1($user->getEmailForVerification())
        )) {
            abort(403, 'Invalid verification link');
        }

        if (! $user->hasVerifiedEmail()) {
                $user->markEmailAsVerified();
                event(new Verified($user));
            }

        auth()->login($user);
        return redirect('/cabinet')->with('success','Email успешно активирован');
    }

    public function resendMail(Request $request)
    {
        $user = User::where('email',$request->email)->first();
  
    
        event(new Registered($user));
        return redirect()->route('login')
                ->with(['success'=>"Проверьте email {$user->email} для подтверждения аккаунта"]);
    }

    public function forgotPassword(Request $request)
    {
         $request->validate([
            'email' => ['required', 'email']
            ]);
         $user = User::where('email',)->first();
         Password::sendResetLink($request->only('email'));
        return back()->with('success', 'Ссылка отправлена на email');
    }

    public function showResetForm($token)
    {

        return view('cabinet.reset-password', ['token' => $token]);
    }

    public function passwordUpdate(Request $request)
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', 'min:6'],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, $password) {
                $user->update([
                    'password' => Hash::make($password)
                ]);
            }
        );

        return redirect()->route('login')
            ->with('success', 'Пароль успешно изменен');
    }
}
