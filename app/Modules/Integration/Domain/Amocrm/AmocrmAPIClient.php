<?php

namespace App\Modules\Integration\Domain\Amocrm;

use AmoCRM\Client\AmoCRMApiClient as AmocrmAPIClientSDK;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\Exception;
use League\OAuth2\Client\Token\AccessToken;

final class AmocrmAPIClient
{
  private static string $tokenDir = "services/amocrm/";

  public AmocrmAPIClientSDK $client;

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

    $this->client = new AmocrmAPIClientSDK($clientId, $clientSecret, $redirectUri);
    $this->client->setAccountBaseDomain($subdomain . '.amocrm.ru');
    $OAuthClient = $this->client->getOAuthClient();

    $tokenData = self::getTokenData($this->client->getAccountBaseDomain());

    if (empty($tokenData)) {
      $accessToken = $OAuthClient->getAccessTokenByCode($authCode);

      self::saveTokenData($accessToken, $this->client->getAccountBaseDomain());
    } else {
      $accessToken = new AccessToken($tokenData);

      if ($accessToken->hasExpired()) {
        $accessToken = $OAuthClient->getAccessTokenByRefreshToken($accessToken);

        self::saveTokenData($accessToken, $this->client->getAccountBaseDomain());
      }
    }

    $this->client->setAccessToken($accessToken);

    return $this;
  }

  public static function getTokenData(string $baseDomain)
  {
    $tokenPath = self::getTokenPath($baseDomain);
    $tokenDataString = Storage::exists($tokenPath) ? Storage::get($tokenPath) : '';

    return !empty($tokenDataString) ? json_decode($tokenDataString, true) : [];
  }

  private static function getTokenPath(string $domain): string
  {
    return implode(DIRECTORY_SEPARATOR, [self::$tokenDir, "$domain-token.json"]);
  }

  /**
   * @throws Exception
   */
  private static function saveTokenData($accessTokenObject, $baseDomain): void
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
}
