<?php

namespace App\Http\Middleware;

use App\Models\ActivityLog;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogActivity
{

    public function handle(Request $request, Closure $next)
    {
        $user = Auth::guard('admin')->user() ?? Auth::guard('vendor')->user();

        if ($user) {
            ActivityLog::create([
                'userable_id'   => $user->id,
                'userable_type' => get_class($user),
                'action'        => 'تم الدخول إلى: ' . $request->path(),
                'ip_address'    => $request->ip(),
            ]);
        }

        return $next($request);
    }
}
