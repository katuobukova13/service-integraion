<?php

namespace App\Modules\Integration\Domain\Getcourse;

use App\Modules\Integration\Core\Concerns\DataType;
use App\Modules\Integration\Core\Facades\RequestOptions;
use App\Modules\Integration\Core\Facades\Resource;
use League\Flysystem\Exception;

class GetcourseResource extends Resource
{
  public function fetch(string $url = '', RequestOptions $options = new RequestOptions): mixed
  {
    $secretKey = config('services.getcourse.advance.secret_key');

    if (!$secretKey) {
      throw new Exception('Invalid secret key');
    }

    return parent::fetch($url, new RequestOptions(
      method: $options->method,
      headers: collect($options->headers)->merge(['Accept' => 'application/json'])->all(),
      body: collect($options->body)->merge(['key' => $secretKey,])->all(),
      bodyFormat: $options->bodyFormat
    ));
  }

  /**
   * @throws Exception
   */
  public function endpoint(): string
  {
    $hostname = config('services.getcourse.advance.hostname');

    if (!$hostname) {
      throw new Exception('Check hostname');
    }

    return "https://$hostname/pl/api";
  }

  public function dataType(): DataType
  {
    return DataType::JSON;
  }
}
