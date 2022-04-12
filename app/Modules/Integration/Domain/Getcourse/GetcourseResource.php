<?php

namespace App\Modules\Integration\Domain\Getcourse;

use App\Modules\Integration\Core\Concerns\ResourceDataType;
use App\Modules\Integration\Core\Concerns\ResourceRequestOptions;
use App\Modules\Integration\Core\Facades\Resource;
use League\Flysystem\Exception;

class GetcourseResource extends Resource
{
  public function fetch(string $url = '', ResourceRequestOptions $options = new ResourceRequestOptions): mixed
  {
    $secretKey = config('services.getcourse.advance.secret_key');

    if (!$secretKey) {
      throw new Exception('Invalid secret key');
    }

    return parent::fetch($url, new ResourceRequestOptions(
      method: $options->getMethod(),
      headers: collect($options->getHeaders())->merge(['Accept' => 'application/json'])->all(),
      body: collect($options->getBody())->merge(['key' => $secretKey,])->all(),
      bodyFormat: $options->getBodyFormat()
    ));
  }

  public function endpoint(): string
  {
    $hostname = config('services.getcourse.advance.hostname');

    if (!$hostname) {
      throw new Exception('Check hostname');
    }

    return "https://$hostname/pl/api";
  }

  protected function dataType(): ResourceDataType
  {
    return ResourceDataType::JSON;
  }
}
