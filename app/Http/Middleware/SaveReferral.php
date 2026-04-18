<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SaveReferral
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->has('ref')) {
            $ref = $request->get('ref');
             if (User::where('referral_code', $ref)->exists()) {
                cookie()->queue('ref', $ref, 60 * 24 * 30);
             }
        }
        return $next($request);
    }
}
