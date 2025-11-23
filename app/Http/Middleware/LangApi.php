<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class LangApi
{

    public function handle(Request $request, Closure $next)
    {

        app()->setLocale('en');

        if($request->header('Accept-Language') == 'ar' ){
            app()->setLocale('ar');
        }

        return $next($request);

    }
}
