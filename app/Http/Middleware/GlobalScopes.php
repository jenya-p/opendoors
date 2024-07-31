<?php

namespace App\Http\Middleware;

use App\Models\Content\Faq;
use App\Models\Content\FaqCategory;
use App\Models\EduLevel;
use App\Models\Profile;
use App\Models\Scopes\Active;
use App\Models\Track;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GlobalScopes {
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response {

        if (!$request->routeIs('admin.*')) {
            FaqCategory::addGlobalScope(Active::class);
            Faq::addGlobalScope(Active::class);
            Profile::addGlobalScope(Active::class);
            EduLevel::addGlobalScope(Active::class);
            Track::addGlobalScope(Active::class);
        }


        return $next($request);
    }
}
