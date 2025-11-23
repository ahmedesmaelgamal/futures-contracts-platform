<?php

namespace App\Http\Middleware\Custom;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Vendor extends Middleware
{
    public function handle($request, Closure $next, ...$guards)
    {
        $this->authenticate($request, ['vendor']);

        $routeName = $request->route()?->getName();

        if (in_array($routeName, ['vendor.login', 'vendor.register'])) {
            return redirect()->route('vendorHome');
        }

        return $next($request);
    }

    protected function redirectTo($request)
    {
        return route('vendor.login');
    }
}
