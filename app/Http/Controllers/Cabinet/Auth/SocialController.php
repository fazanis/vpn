<?php

namespace App\Http\Controllers\Cabinet\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Laravel\Socialite\Socialite;

class SocialController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback(string $provider)
    {
        try{
            $socialite = Socialite::driver($provider)->user();
       
            $user = User::updateOrCreate([
                'provider_id' => $socialite->getId(),
                'provider_name' => $provider,
            ], [
                'name' => $socialite->getName(),
                'email' => $socialite->getEmail(),
                'provider_token' => $socialite->token,
                'provider_refresh_token' => $socialite->refreshToken,
            ]);
            $ref = User::where('referral_code',request()->cookie('ref'))->first();
          
            if($ref){
                $user->update(['referred_by'=>request()->cookie('ref')]);
            }
            
            auth()->login($user);

        
            return redirect('/cabinet');
        }catch(Exception $e){
            return redirect()->route('cabinet.login')->withErrors('error',$e->getMessage());
        }
    }
}
