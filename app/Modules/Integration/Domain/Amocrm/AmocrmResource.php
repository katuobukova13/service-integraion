<?php

namespace App\Modules\Integration\Domain\Amocrm;

use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use App\Modules\Integration\Core\Concerns\ResourceDataType;
use App\Modules\Integration\Core\Concerns\ResourceRequestOptions;
use App\Modules\Integration\Core\Facades\Resource;
use League\Flysystem\Exception;

class AmocrmResource extends Resource
{
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

  public function fetch(string $url = '', ResourceRequestOptions $options = null): mixed
  {
    $secretKey = $this->apiClient::getTokenData(config('services.amocrm.advance.subdomain') . '.' . 'amocrm.ru');;

    return parent::fetch($url, new ResourceRequestOptions(
      method: $options->getMethod(),
      headers: collect($options->getHeaders())->merge([
        'Accept' => 'application/json',
         "Authorization" => "Bearer " . $secretKey["access_token"],
      ])->all(),
      body: $options->getBody(),
      bodyFormat: $options->getBodyFormat()
    ));
  }

  protected function endpoint(): string
  {
    $hostname = config('services.amocrm.advance.subdomain');

    return "https://$hostname.amocrm.ru/api/v4";
  }

  protected function dataType(): ResourceDataType
  {
    return ResourceDataType::JSON;
  }
}
