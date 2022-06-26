<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class UserToTokenMiddleware
{
  /**
   * Handle an incoming request.
   *
   * @param Request $request
   * @param Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
   * @return mixed
   */
  public function handle(Request $request, Closure $next): mixed
  {
    $token = $request->getUser();

    if(!empty($token))
      $request->headers->set('Authorization', "Bearer {$token}");

    return $next($request);
  }
}
