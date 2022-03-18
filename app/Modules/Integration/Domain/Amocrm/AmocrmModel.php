<?php

namespace App\Modules\Integration\Domain\Amocrm;

use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use AmoCRM\Models\BaseApiModel;
use App\Modules\Integration\Core\Facades\ResourceModel;
use Illuminate\Support\Facades\App;
use Exception;

abstract class AmocrmModel extends ResourceModel
{
  public BaseApiModel $sdkModel;

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

  /**
   *
   * @param array|null $select
   * @param array|null $with
   * @param array|null $filter
   * @param int|null $page
   * @param int|null $limit
   * @return array
   * @throws Exception
   */
  protected static function fetchList(?array $select = [], ?array $with = [], ?array $filter = [], ?int $page = null, ?int $limit = null): array
  {
    /**
     * @var AmocrmModel $model
     */
    $model = App::make(static::class);

    $parameters = [
      'select' => $select,
      'with' => $with,
      'filter' => $filter,
      'limit' => $limit,
      'page' => $page];

    $queryString = http_build_query($parameters);

    $response = $model->resource->fetch('?' . $queryString);

    return $response === null ? [] : $response;
  }
}
