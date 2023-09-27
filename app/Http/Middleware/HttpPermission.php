<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Exceptions\HttpResponseException;

class HttpPermission
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
        self::config();
        return $next($request);
    }

    public static function config(){
        $auth = auth()->user();
        $name = request()->route()->getPermission();

        if ($name && $auth->hasPermissionTo($name, 'web')) {
            return true;
        }
        throw new HttpResponseException( response()->json(['error'=> 'You do not have permission to access this api.' ], 422 ) );
    }

}
