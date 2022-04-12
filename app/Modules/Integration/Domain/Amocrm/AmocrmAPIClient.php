<?php

namespace App\Modules\Integration\Domain\Amocrm;

use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\Exception;
use League\OAuth2\Client\Token\AccessToken;
use \AmoCRM\Client\AmoCRMApiClient as AmoCRMApiClientSDK;

class AmocrmAPIClient
{
 private static string $tokenDir = "services/amocrm/";

  /**
   * @throws AmoCRMoAuthApiException
   * @throws Exception
   */
  public function __construct($subdomain, $clientId, $clientSecret, $authCode, $redirectUri)
  {
    $this->apiClientSDK = new AmoCRMApiClientSDK($clientId, $clientSecret, $redirectUri);
    $this->apiClientSDK->setAccountBaseDomain($subdomain . '.amocrm.ru');
    $OAuthClient = $this->apiClientSDK->getOAuthClient();

    $tokenData = $this->getTokenData($this->apiClientSDK->getAccountBaseDomain());

    if (empty($tokenData)) {
      $accessToken = $OAuthClient->getAccessTokenByCode($authCode);

      $this->saveTokenData($accessToken, $this->apiClientSDK->getAccountBaseDomain());
    } else {
      $accessToken = new AccessToken($tokenData);

      if ($accessToken->hasExpired()) {
        $accessToken = $OAuthClient->getAccessTokenByRefreshToken($accessToken);

        $this->saveTokenData($accessToken, $this->apiClientSDK->getAccountBaseDomain());
      }
    }

    $this->apiClientSDK->setAccessToken($accessToken);

    return $this;
  }

  private static function getTokenPath(string $domain): string
  {
    return implode(DIRECTORY_SEPARATOR, [self::$tokenDir, "$domain-token.json"]);
  }

  /**
   * @throws Exception
   */
  private function saveTokenData($accessTokenObject, $baseDomain)
  {
    $tokenData = [
      'access_token' => $accessTokenObject->getToken(),
      'refresh_token' => $accessTokenObject->getRefreshToken(),
      'expires' => $accessTokenObject->getExpires(),
      'baseDomain' => $baseDomain,
    ];

    if (
      !empty($tokenData['access_token']) &&
      !empty($tokenData['refresh_token']) &&
      !empty($tokenData['expires']) &&
      !empty($tokenData['baseDomain'])
    ) {
      Storage::put(self::getTokenPath($baseDomain), json_encode($tokenData));
    } else {
      throw new Exception('Invalid access token ' . var_export($tokenData ?? [], true));
    }
  }

  public static function getTokenData(string $baseDomain)
  {
    $tokenPath = self::getTokenPath($baseDomain);
    $tokenDataString = Storage::exists($tokenPath) ? Storage::get($tokenPath) : '';

    return !empty($tokenDataString) ? json_decode($tokenDataString, true) : [];
  }
}
