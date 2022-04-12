<?php /** @noinspection Annotator */

namespace App\Modules\Integration\Core\Facades;

use App\Modules\Integration\Core\Concerns\ResourceDataType;
use App\Modules\Integration\Core\Concerns\ResourceRequestOptions;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Exception;
use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Support\Str;

abstract class Resource
{
  abstract protected function endpoint(): string;
  abstract protected function dataType(): ResourceDataType;

  public static function buildUrl($origin, $pathName): string
  {
    $urlGenerator = url();
    $urlGenerator->forceRootUrl($origin);

    return $urlGenerator->to($pathName, [], Str::startsWith($origin, 'https'));
  }

  /**
   * @param string $url
   * @param ResourceRequestOptions $options
   * @return mixed
   * @throws Exception
   */
  public function fetch(string $url, ResourceRequestOptions $options = new ResourceRequestOptions): mixed
  {
    /**
     * @var  PromiseInterface|Response $response
     * @var string $method
     * @var array $headers
     * @var array $body
     */

    $finalUrl = self::buildUrl($this->endpoint(), $url);

    $method = mb_strtolower($options->getMethod()->name);

    $headers = $options->getHeaders();
    $body = $options->getBody();
    $bodyFormat = mb_strtolower($options->getBodyFormat()->name);

    $response = Http::withHeaders($headers)->send($method, $finalUrl, [
      $bodyFormat => $body,
    ]);

    return match ($response->status()) {
      200, 201, 204 => $this->handleResponse($response),
      default => throw new Exception($response),
    };
  }

  private function handleResponse($response)
  {
    /**
     * @var  PromiseInterface|Response $response
     */

    return match ($this->dataType()) {
      ResourceDataType::JSON => $response->json(),
      default => $response->body(),
    };
  }
}
