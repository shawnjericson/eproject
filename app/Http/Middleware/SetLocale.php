<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Get locale from session, URL parameter, or default
        $locale = $request->get('lang') ?? Session::get('locale') ?? config('app.locale');
        
        // Validate locale
        $supportedLocales = ['en', 'vi'];
        if (!in_array($locale, $supportedLocales)) {
            $locale = config('app.locale');
        }
        
        // Set locale
        App::setLocale($locale);
        Session::put('locale', $locale);
        
        return $next($request);
    }
}
