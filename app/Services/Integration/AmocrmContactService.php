<?php

namespace App\Services\Integration;

use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMMissedTokenException;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use App\Modules\Integration\Domain\Amocrm\Contact\ContactModel as AmocrmContact;
use Exception;
use JetBrains\PhpStorm\ArrayShape;

class AmocrmContactService
{
  /**
   * @param string $firstName
   * @param string $lastName
   * @param array $phone
   * @param array $email
   * @param string|null $city
   * @param string|null $country
   * @param string|null $position
   * @param string|null $partner
   * @return array
   * @throws AmoCRMApiException
   * @throws AmoCRMMissedTokenException
   * @throws AmoCRMoAuthApiException
   */

  #[ArrayShape(['contact' => "array"])]
  public function create(
    string $firstName,
    string $lastName,
    array  $phone,
    array  $email,
    string $city = null,
    string $country = null,
    string $position = null,
    string $partner = null,
    int    $responsibleUserId = null,
  ): array
  {
    $contact = AmocrmContact::create([
      'first_name' => $firstName,
      'last_name' => $lastName,
      'name' => $firstName . ' ' . $lastName,
      'responsible_user_id' => $responsibleUserId,
      'cf_email' => $email,
      'cf_phone' => $phone,
      'cf_city' => $city,
      'cf_country' => $country,
      'cf_position' => $position,
      'cf_partner' => $partner,
    ]);

    return ['contact' => $contact->attributes];
  }

  /**
   * @param int $id
   * @return array
   */
  #[ArrayShape(['contact' => "array"])]
  public function find(int $id): array
  {
    $contact = AmocrmContact::find($id);
    return ['contact' => $contact->attributes];
  }

  /**
   * @throws AmoCRMApiException
   * @throws AmoCRMoAuthApiException
   * @throws AmoCRMMissedTokenException
   */

  #[ArrayShape(['contact' => "array"])]
  public function update(
    int    $id,
    string $firstName = null,
    array  $phone = null,
    array  $email = null,
    string $lastName = null,
    string $city = null,
    string $country = null,
    string $position = null,
    string $partner = null,
    int    $responsibleUserId = null,
  ): array
  {
    $contact = AmocrmContact::find($id);

    $contact->update([
      'first_name' => $firstName,
      'last_name' => $lastName,
      'name' => $firstName . ' ' . $lastName,
      'responsible_user_id' => $responsibleUserId,
      'cf_email' => $email,
      'cf_phone' => $phone,
      'cf_city' => $city,
      'cf_country' => $country,
      'cf_position' => $position,
      'cf_partner' => $partner,
    ]);

    $contactUpdated = AmocrmContact::find($contact->attributes['id']);

    return ['contact' => $contactUpdated->attributes];
  }

  /**
   * @throws Exception
   */
  #[ArrayShape(['contacts' => "\Illuminate\Support\Collection"])]
  public function list(
    array $with = [],
    array $filter = [],
    int   $page = null,
    int   $limit = null,
  ): array
  {
    $collection = AmocrmContact::list(with: $with, filter: $filter, page: $page, limit: $limit);

    return ['contacts' => $collection];
  }
}
