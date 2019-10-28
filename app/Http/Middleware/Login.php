<?php

namespace App\Http\Middleware;

use Closure;
use App\User ;

class Login
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
      $token = str_replace('Bearer ','',$request->header('Authorization'));
      $user = User::where('token',$token)->first() ;
      if ( !empty($user) || $user != null ) {
      $request->attributes->add(['user' => $user]);
        return $next($request)
          ->header('Access-Control-Allow-Origin', '*')
          ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS'); 
      } else {
        return response('Not valid token provider.', 401) ;
      }
    }
}
