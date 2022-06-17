<?php

namespace App\Services\Integration;

use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMMissedTokenException;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use App\Modules\Integration\Domain\Amocrm\AmocrmSamplingClause;
use App\Modules\Integration\Domain\Amocrm\Contact\ContactModel as AmocrmContact;
use Exception;
use Illuminate\Support\Collection;

class AmocrmContactService
{
  /**
   * @param string $firstName
   * @param string $lastName
   * @param array $phones
   * @param array $emails
   * @param string|null $city
   * @param string|null $country
   * @param string|null $position
   * @param string|null $partner
   * @param int|null $responsibleUserId
   * @return array
   * @throws Exception
   */
  public function create(
    string $firstName,
    string $lastName,
    array  $phones,
    array  $emails,
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
      'emails' => $emails,
      'phones' => $phones,
      'city' => $city,
      'country' => $country,
      'position' => $position,
      'partner' => $partner,
    ]);

    return $contact->attributes;
  }

  /**
   * @param int $id
   * @return array
   * @throws Exception
   */
  public function find(int $id): array
  {
    $contact = AmocrmContact::find($id);

    return $contact->attributes;
  }

  /**
   * @throws AmoCRMApiException
   * @throws AmoCRMoAuthApiException
   * @throws AmoCRMMissedTokenException
   * @throws Exception
   */
  public function update(
    int    $id,
    string $firstName = null,
    array  $phones = null,
    array  $emails = null,
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
      'emails' => $emails,
      'phones' => $phones,
      'city' => $city,
      'country' => $country,
      'position' => $position,
      'partner' => $partner,
    ]);

    $contactUpdated = AmocrmContact::find($contact->attributes['id']);

    return $contactUpdated->attributes;
  }

  /**
   * @throws Exception
   */
  public function list(
    array $with = [],
    array $filter = [],
    int   $page = 1,
    int   $limit = 50,
  ): Collection
  {
    return AmocrmContact::list(new AmocrmSamplingClause(with: $with, page: $page, limit: $limit, filter: $filter));
  }
}
