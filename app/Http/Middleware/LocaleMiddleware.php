<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class LocaleMiddleware {
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response {

        $getLocale = function() use ($request){

            $locale = session('locale');
            if (!empty($locale) && in_array($locale, ['ru', 'en'])) {
                return $locale;
            }

            if (\Auth::user() && !empty(\Auth::user()->locale) && in_array(\Auth::user()->locale, ['ru', 'en'])) {
                return \Auth::user()->locale;
            }

            $locale = $request->getPreferredLanguage();
            if (!empty($locale) && in_array($locale, ['ru', 'en'])) {
                return $locale;
            }

            return env('APP_LOCALE');
        };
        $locale = $getLocale();

        app()->setLocale($locale);

        return $next($request);
    }
}
