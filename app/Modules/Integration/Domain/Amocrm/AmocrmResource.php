<?php

namespace App\Modules\Integration\Domain\Amocrm;

use App\Modules\Integration\Core\Concerns\DataType;
use App\Modules\Integration\Core\Concerns\RequestBodyFormat;
use App\Modules\Integration\Core\Concerns\RequestMethod;
use App\Modules\Integration\Core\Facades\RequestOptions;
use App\Modules\Integration\Core\Facades\Resource;
use App\Modules\Integration\Core\Facades\SamplingClause;
use Exception;

class AmocrmResource extends Resource
{
  /**
   * @throws Exception
   */
  public function fetchList(SamplingClause $clause = new SamplingClause()): array
  {
    $response = $this->fetch('', new RequestOptions(
      method: RequestMethod::GET,
      body: $clause->toArray(),
      bodyFormat: RequestBodyFormat::QUERY
    ));

    return $response ?? [];
  }

  public function fetch(string $url = '', RequestOptions $options = null): mixed
  {
    $clientId = config('services.amocrm.advance.client_id');
    $secretKey = AmocrmAPIClient::getTokenData($clientId);

    return parent::fetch($url, new RequestOptions(
      method: $options->method,
      headers: collect($options->headers)->merge([
        'Accept' => 'application/json',
        "Authorization" => "Bearer " . $secretKey["access_token"],
      ])->all(),
      body: $options->body,
      bodyFormat: $options->bodyFormat
    ));
  }

  protected function endpoint(): string
  {
    $hostname = config('services.amocrm.advance.subdomain');

    return "https://$hostname.amocrm.ru/api/v4";
  }

  protected function dataType(): DataType
  {
    return DataType::JSON;
  }
}
