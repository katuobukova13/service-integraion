<?php

namespace App\Modules\Integration\Core\Facades;

use App\Modules\Integration\Core\Concerns\ResourceDataType;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Exception;
use GuzzleHttp\Promise\PromiseInterface;

abstract class Resource
{
  public string $endpoint;
  public ResourceDataType $dataType;

  /**
   * @throws Exception
   */
  public function fetch(string $url, array $options = [])
  {
    /**
     * @var  PromiseInterface|Response $response
     * @var string $endpoint
     * @var string $method
     * @var array $headers
     * @var array $body
     */

    $method = mb_strtolower($options['method'] ?? 'GET');
    $headers = $options['headers'] ?? [];
    $body = $options['body'] ?? [];

    $body != []  ?
      $response = Http::withHeaders($headers)->$method($url, $body) :
      $response = Http::withHeaders($headers)->$method($url);

    return match ($response->status()) {
      200, 201, 204 => $this->handleResponse($response),
      default => throw new Exception($response),
    };
  }

  private function handleResponse($response)
  {
    return match ($this->dataType) {
      ResourceDataType::JSON => $response->json(),
      default => $response->body(),
    };
  }
}
