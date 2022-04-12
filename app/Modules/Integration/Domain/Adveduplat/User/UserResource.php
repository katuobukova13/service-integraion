<?php

namespace App\Modules\Integration\Domain\Adveduplat\User;

use App\Modules\Integration\Core\Concerns\ResourceDataType;
use App\Modules\Integration\Domain\Adveduplat\AdveduplatResource as SyncResource;

class UserResource extends SyncResource
{
  function __construct()
  {
    parent::__construct();
    $this->endpoint = $this->endpoint . '/api/admin/users';
  }

  public function fetch(string $url, array $options = []): mixed
  {
    $options = collect([
      "headers" => [
        "Authorization" => "Bearer " . config('services.adveduplat.api_token'),
        "Content-Type" => "application/json",
        "Access-Control-Allow-Origin" => "*",
      ]
    ])
      ->merge($options)
      ->all();

    return parent::fetch($url, $options);
  }
}
