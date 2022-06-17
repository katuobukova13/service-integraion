<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Response;

class ApiAuthException extends Exception implements Renderable
{
  public function render(): Response
  {
    return response(['message' => 'Unauthorized', 'error' => 'Unauthorized'], 401);
  }
}
