<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class AuthAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            if (Auth::user()->status == 0) {
                Session::flush();
                Auth::logout();
                return redirect()->route('login')->with('error', 'Your account has been banned!');
            }

            if (Auth::user()->utype === 'ADM') {
                return $next($request);
            } else {
                Session::flush();
                return redirect()->route('login')->with('error', 'You do not have permission to access this page!');
            }
        } else {
            return redirect()->route('login');
        }
    }
}