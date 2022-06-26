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

    $tokenData = self::getTokenData($clientId);

    if (empty($tokenData)) {
      $accessToken = $OAuthClient->getAccessTokenByCode($authCode);

      self::saveTokenData($clientId, [
        'access_token' => $accessToken->getToken(),
        'refresh_token' => $accessToken->getRefreshToken(),
        'expires' => $accessToken->getExpires(),
        'baseDomain' => $this->client->getAccountBaseDomain(),
      ]);
    } else {
      $accessToken = new AccessToken($tokenData);

      if ($accessToken->hasExpired()) {
        $accessToken = $OAuthClient->getAccessTokenByRefreshToken($accessToken);

        self::saveTokenData($clientId, [
          'access_token' => $accessToken->getToken(),
          'refresh_token' => $accessToken->getRefreshToken(),
          'expires' => $accessToken->getExpires(),
          'baseDomain' => $this->client->getAccountBaseDomain(),
        ]);
      }
    }

    $this->client->setAccessToken($accessToken);

    return $this;
  }

  public static function getTokenData(string $clientId)
  {
    $tokenPath = self::getTokenPath($clientId);
    $tokenDataString = Storage::exists($tokenPath) ? Storage::get($tokenPath) : '';

    return !empty($tokenDataString) ? json_decode($tokenDataString, true) : [];
  }

  private static function getTokenPath(string $clientId): string
  {
    return implode(DIRECTORY_SEPARATOR, [self::$tokenDir, "$clientId-token.json"]);
  }

  /**
   * @throws Exception
   */
  private static function saveTokenData($clientId, $tokenData): void
  {
    if (
      !empty($tokenData['access_token']) &&
      !empty($tokenData['refresh_token']) &&
      !empty($tokenData['expires']) &&
      !empty($tokenData['baseDomain'])
    ) {
      Storage::put(self::getTokenPath($clientId), json_encode($tokenData));
    } else {
      throw new Exception('Invalid access token ' . var_export($tokenData ?? [], true));
    }
  }
}
