<?php

namespace App\Modules\Integration\Domain\Amocrm;

use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use App\Modules\Integration\Core\Concerns\ResourceDataType;
use App\Modules\Integration\Core\Facades\Resource;
use League\Flysystem\Exception;

abstract class AmocrmResource extends Resource
{
  public string $endpoint = 'https://advancetest.amocrm.ru/api/v4';
  public ResourceDataType $dataType = ResourceDataType::JSON;

  /**
   * @throws AmoCRMoAuthApiException
   * @throws Exception
   */
  public function __construct()
  {
    $subdomain = config('services.amocrm.advance.subdomain');
    $clientId = config('services.amocrm.advance.client_id');
    $clientSecret = config('services.amocrm.advance.client_secret');
    $authCode = config('services.amocrm.advance.auth_code');
    $redirectUri = config('services.amocrm.advance.redirect_uri');

    $this->apiClient = new AmocrmAPIClient($subdomain, $clientId, $clientSecret, $authCode, $redirectUri);
  }

  public function fetch(string $url, array $options = [])
  {
    $endpoint = !$url ?
      $this->endpoint : (str_starts_with($url, '?') ? $this->endpoint . $url : $this->endpoint . '/' . $url);

    $tokenData = $this->apiClient::getTokenData('advancetest.amocrm.ru');

    $options = collect([
      "headers" => [
        "Authorization" => "Bearer " . $tokenData["access_token"],
        "Content-Type" => "application/json",
        "Access-Control-Allow-Origin" => "*",
      ]
    ])
      ->merge($options)
      ->all();

    return parent::fetch($endpoint, $options);
  }
}
