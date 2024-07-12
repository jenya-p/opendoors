<?php

namespace App\Http\Middleware;

use App\Models\Backfeed;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware {


    protected $rootView = 'app';

    public function version(Request $request) {
        return $this->rootView . parent::version($request);
    }

    public function handle(Request $request, \Closure $next) {

        if($request->routeIs('admin.*')){
            $this->rootView = 'admin';

        } else {
            $this->rootView = 'app';
        }

        return parent::handle($request, $next);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array {
        $ret =  [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
            ],
        ];

        $ret['locale'] = app()->getLocale();

        if($request->routeIs('admin.*')){
            $ret['notifications'] = [

            ];
        }

        return $ret;

    }
}
