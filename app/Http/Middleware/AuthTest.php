<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
use Symfony\Component\HttpFoundation\Response;
use App\Until;
class AuthTest
{

    public $routeWhite = ['api/login','api/register'];
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {

        $url =$request->route()->uri;
        if (!in_array($url,$this->routeWhite)){
            if (is_null($token = $request->header('authorization'))) {
                 return Until\Helper::error(403,[],'Authorization Failed, missing authorization');
            }

            $cache = Redis::get($token);
            if (empty($cache)){
                return Until\Helper::error(403,[],'令牌失效，请重新登陆');
            }

        }

        return $next($request);
    }
}
