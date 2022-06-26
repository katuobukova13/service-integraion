<?php

namespace App\Modules\Integration\Core\Facades;

use App\Modules\Integration\Core\Concerns\RequestBodyFormat;
use App\Modules\Integration\Core\Concerns\RequestMethod;

final class RequestOptions
{
  public function __construct(
    public readonly RequestMethod     $method = RequestMethod::GET,
    public readonly array             $headers = [],
    public readonly array             $body = [],
    public readonly RequestBodyFormat $bodyFormat = RequestBodyFormat::JSON
  )
  {
  }
}
