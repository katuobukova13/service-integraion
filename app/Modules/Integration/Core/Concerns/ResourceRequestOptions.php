<?php

namespace App\Modules\Integration\Core\Concerns;

final class ResourceRequestOptions
{
  public function __construct(
    private ResourceRequestMethod $method = ResourceRequestMethod::GET,
    private array $headers = [],
    private array $body = [],
    private ResourceRequestBodyFormat $bodyFormat = ResourceRequestBodyFormat::JSON
  )
  {
  }

  public function getMethod(): ResourceRequestMethod
  {
    return $this->method;
  }

  public function getHeaders(): array
  {
    return $this->headers;
  }

  public function getBody(): array
  {
    return $this->body;
  }

  public function getBodyFormat(): ResourceRequestBodyFormat
  {
    return $this->bodyFormat;
  }
}
