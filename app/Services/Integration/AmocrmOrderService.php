<?php

namespace App\Services\Integration;

use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMMissedTokenException;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use AmoCRM\Exceptions\InvalidArgumentException;
use App\Modules\Integration\Domain\Amocrm\Contact\ContactModel as AmocrmContact;
use App\Modules\Integration\Domain\Amocrm\Lead\LeadModel as AmocrmLead;
use App\Modules\Integration\Domain\Amocrm\Link\LinkModel;
use JetBrains\PhpStorm\ArrayShape;

class AmocrmOrderService
{
  /**
   * @param string $contactFirstName
   * @param string $contactLastName
   * @param array $contactPhone
   * @param array $contactEmail
   * @param string|null $contactCity
   * @param string|null $contactCountry
   * @param string|null $contactPosition
   * @param string|null $contactPartner
   * @param string $title
   * @param int $price
   * @param int|null $groupId
   * @param int|null $responsibleUserId
   * @param int|null $sourceId
   * @param string $payDate
   * @param int|null $order
   * @param string|null $integrator
   * @return array
   * @throws AmoCRMApiException
   * @throws AmoCRMMissedTokenException
   * @throws AmoCRMoAuthApiException
   * @throws InvalidArgumentException
   */

  #[ArrayShape(['contact' => "array", 'lead' => "array", 'link' => "array"])]
  public function create(
    string $contactFirstName,
    string $contactLastName,
    array  $contactPhone,
    array  $contactEmail,
    string $title,
    int    $price,
    string $payDate,
    string $contactCity = null,
    string $contactCountry = null,
    string $contactPosition = null,
    string $contactPartner = null,
    int    $groupId = null,
    int    $responsibleUserId = null,
    int    $sourceId = null,
    int    $order = null,
    string $integrator = null,
  ): array
  {
    $contact = AmocrmContact::create([
      'first_name' => $contactFirstName,
      'last_name' => $contactLastName,
      'name' => $contactFirstName . ' ' . $contactLastName,
      'cf_email' => $contactEmail,
      'cf_phone' => $contactPhone,
      'cf_city' => $contactCity,
      'cf_country' => $contactCountry,
      'cf_position' => $contactPosition,
      'cf_partner' => $contactPartner,
    ]);

    $lead = AmocrmLead::create([
      'name' => $title,
      'price' => $price,
      'group_id' => $groupId,
      'responsible_user_id' => $responsibleUserId,
      'source_id' => $sourceId,
      'cf_pay_date' => $payDate,
      'cf_order' => $order,
      'cf_integrator' => $integrator,
    ]);

    $link = LinkModel::link($lead, $contact);

    return [
      'contact' => $contact->attributes,
      'lead' => $lead->attributes,
      'link' => $link->attributes,
    ];
  }
}
