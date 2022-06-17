<?php

namespace App\Services\Integration;

use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMMissedTokenException;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use App\Modules\Integration\Domain\Amocrm\AmocrmSamplingClause;
use App\Modules\Integration\Domain\Amocrm\Lead\LeadModel as AmocrmLead;
use Exception;
use Illuminate\Support\Collection;

class AmocrmLeadService
{
  /**
   * @param string $title
   * @param int|null $price
   * @param int|null $groupId
   * @param int|null $sourceId
   * @param string|null $payDate
   * @param string|null $city
   * @param int|null $orderId
   * @param int|null $orderNum
   * @param string|null $integrator
   * @param int|null $responsibleUserId
   * @return array
   * @throws Exception
   */
  public function create(
    string $title,
    int    $price = null,
    int    $groupId = null,
    int    $sourceId = null,
    string $payDate = null,
    string $city = null,
    int    $orderId = null,
    int    $orderNum = null,
    string $integrator = null,
    int    $responsibleUserId = null,
  ): array
  {
    $lead = AmocrmLead::create([
      'name' => $title,
      'price' => $price,
      'group_id' => $groupId,
      'city' => $city,
      'responsible_user_id' => $responsibleUserId,
      'source_id' => $sourceId,
      'pay_date' => $payDate,
      'order_id' => $orderId,
      'order_num' => $orderNum,
      'integrator' => 'Advance Integration Service v1.0',
    ]);

    return $lead->attributes;
  }

  /**
   * @param int $id
   * @return array
   * @throws Exception
   */
  public function find(int $id): array
  {
    $lead = AmocrmLead::find($id);

    return $lead->attributes;
  }

  /**
   * @throws AmoCRMApiException
   * @throws AmoCRMoAuthApiException
   * @throws AmoCRMMissedTokenException
   * @throws Exception
   */

  public function update(
    int    $id,
    string $integrator = null,
    string $title = null,
    int    $price = null,
    int    $groupId = null,
    int    $sourceId = null,
    string $payDate = null,
    string $city = null,
    int    $orderId = null,
    int    $orderNum = null,
    int    $responsibleUserId = null,
  ): array
  {
    $lead = AmocrmLead::find($id);

    $lead->update([
      'name' => $title,
      'price' => $price,
      'group_id' => $groupId,
      'city' => $city,
      'responsible_user_id' => $responsibleUserId,
      'source_id' => $sourceId,
      'pay_date' => $payDate,
      'order_id' => $orderId,
      'order_num' => $orderNum,
      'integrator' => 'Advance Integration Service v1.0',
    ]);

    $leadUpdated = AmocrmLead::find($lead->attributes['id']);

    return $leadUpdated->attributes;
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
    return AmocrmLead::list(
      new AmocrmSamplingClause(
        with: $with,
        page: $page,
        limit: $limit,
        filter: $filter
      )
    );
  }
}
