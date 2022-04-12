<?php

namespace App\Modules\Integration\Domain\Adveduplat;

use App\Modules\Integration\Core\Facades\Resource;

class AdveduplatResource extends Resource
{
  function __construct()
  {
    $this->endpoint = config('services.adveduplat.domain');
  }

  public function fetch(string $url, array $options = []): mixed
  {
    $endpoint = $url ? $this->endpoint . '/' . $url : $this->endpoint;

    return parent::fetch($endpoint, $options);
  }
}
