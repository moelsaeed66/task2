<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TwoFactor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if(auth()->check() && $user->two_factor_code)
        {
            if($user->two_factor_expires_at<now()) //expired
            {
                $user->resetTwoFactorCode();
                auth()->logout();

                return redirect()->route('login')
                    ->withMessage('The two factor code has expired. Please login again.');
            }

            if(!$request->is('verify*')) //prevent enless loop, otherwise send to verify
            {
                return redirect()->route('verify.index');
            }
        }

        return $next($request);
    }
}
