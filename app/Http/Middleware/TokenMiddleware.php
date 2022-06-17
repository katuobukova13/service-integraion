<?php

namespace App\Http\Middleware;

use App\Exceptions\ApiAuthException;
use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class TokenMiddleware
{
  /**
   * Handle an incoming request.
   *
   * @param Request $request
   * @param Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
   * @return Response|RedirectResponse
   * @throws ApiAuthException
   */
  public function handle(Request $request, Closure $next): Response|RedirectResponse
  {
    if ($request->header('Authorization') !== config('auth.tmp_auth_token'))
      return $next($request);

    $token =
      $request->header('Authorization') ?
        Str::replace('Bearer ', '', $request->header('Authorization')) :
        $request->query('token');

    if ($token !== config('auth.tmp_auth_token'))
      throw new ApiAuthException();

    return $next($request);
  }
}
