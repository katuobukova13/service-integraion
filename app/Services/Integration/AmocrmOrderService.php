<?php

namespace App\Services\Integration;

use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMMissedTokenException;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use AmoCRM\Exceptions\InvalidArgumentException;
use App\Modules\Integration\Domain\Amocrm\AmocrmSamplingClause;
use App\Modules\Integration\Domain\Amocrm\Contact\ContactModel as AmocrmContact;
use App\Modules\Integration\Domain\Amocrm\Lead\LeadModel as AmocrmLead;
use App\Modules\Integration\Domain\Amocrm\Link\LinkModel;
use Exception;
use JetBrains\PhpStorm\ArrayShape;

class AmocrmOrderService
{
  /**
   * @param string $contactFirstName
   * @param string $contactLastName
   * @param array $contactPhone
   * @param array $contactEmail
   * @param string $title
   * @param int|null $price
   * @param string|null $payDate
   * @param string|null $contactCity
   * @param string|null $contactCountry
   * @param string|null $contactPosition
   * @param string|null $contactPartner
   * @param int|null $groupId
   * @param int|null $responsibleUserId
   * @param int|null $sourceId
   * @param int|null $orderId
   * @param int|null $orderNum
   * @param string|null $integrator
   * @return array
   * @throws AmoCRMApiException
   */
  public function create(
    string $contactFirstName,
    string $contactLastName,
    array  $contactPhone,
    array  $contactEmail,
    string $title,
    int    $price = null,
    string $payDate = null,
    string $contactCity = null,
    string $contactCountry = null,
    string $contactPosition = null,
    string $contactPartner = null,
    int    $groupId = null,
    int    $responsibleUserId = null,
    int    $sourceId = null,
    int    $orderId = null,
    int    $orderNum = null,
    string $integrator = null,
  ): array
  {
    $contact =
      AmocrmContact::list(new AmocrmSamplingClause(filter: ['emails' => $contactEmail]))->first() ??
      AmocrmContact::list(new AmocrmSamplingClause(filter: ['phones' => $contactPhone]))->first() ??
      AmocrmContact::create([
        'first_name' => $contactFirstName,
        'last_name' => $contactLastName,
        'name' => $contactFirstName . ' ' . $contactLastName,
        'emails' => $contactEmail,
        'phones' => $contactPhone,
        'city' => $contactCity,
        'country' => $contactCountry,
        'position' => $contactPosition,
        'partner' => $contactPartner,
      ]);

    $lead = AmocrmLead::create([
      'name' => $title,
      'price' => $price,
      'group_id' => $groupId,
      'responsible_user_id' => $responsibleUserId,
      'source_id' => $sourceId,
      'pay_date' => $payDate,
      'order_id' => $orderId,
      'order_num' => $orderNum,
      'integrator' => "Advance Integration Service v1.0",
    ]);

    $link = LinkModel::link($lead, $contact);

    return [
      'contact' => $contact->attributes,
      'lead' => $lead->attributes,
      'link' => $link->attributes[0],
    ];
  }
}
