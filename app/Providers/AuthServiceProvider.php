<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Http\Request;

class AuthServiceProvider extends ServiceProvider
{
  /**
   * The policy mappings for the application.
   *
   * @var array
   */
  protected $policies = [
    // 'App\Models\Model' => 'App\Policies\ModelPolicy',
  ];

  /**
   * Register any authentication / authorization services.
   *
   * @return void
   */
  public function boot(Request $request)
  {
    $this->registerPolicies();

//    if ($request->query('token')) {
//      $personalAccessToken = PersonalAccessToken::findToken($request->query('token'));
//      $user = $personalAccessToken->tokenable;
//      auth()->login($user);
//    }
  }
}
