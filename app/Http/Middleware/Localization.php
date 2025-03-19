<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class Localization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if the language is set in the session
        if (Session::has('locale')) {
            // Set the application locale to the one stored in the session
            App::setLocale(Session::get('locale'));
        } else {
            // If no locale is set in the session, set it to the default locale
            App::setLocale(config('app.locale'));
        }
        
        return $next($request);
    }
} 