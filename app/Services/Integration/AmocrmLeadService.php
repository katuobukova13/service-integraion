<?php

namespace App\Services\Integration;

use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMMissedTokenException;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use App\Modules\Integration\Domain\Amocrm\Lead\LeadModel as AmocrmLead;
use Exception;
use JetBrains\PhpStorm\ArrayShape;

class AmocrmLeadService
{
  /**
   * @param string $title
   * @param int $price
   * @param int|null $groupId
   * @param int|null $sourceId
   * @param string|null $payDate
   * @param int|null $order
   * @param string|null $integrator
   * @param int|null $responsibleUserId
   * @return array
   */
  #[ArrayShape(['lead' => "array"])]
  public function create(
    string $title,
    int  $price,
    int  $groupId = null,
    int $sourceId = null,
    string $payDate = null,
    int $order = null,
    string $integrator = null,
    int $responsibleUserId = null,
  ): array
  {
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

    return ['lead' => $lead->attributes];
  }

  /**
   * @param int $id
   * @return array
   */
  #[ArrayShape(['lead' => "array"])]
  public function find(int $id): array
  {
    $lead = AmocrmLead::find($id)->attributes;

    return ['lead' => $lead];
  }

  /**
   * @throws AmoCRMApiException
   * @throws AmoCRMoAuthApiException
   * @throws AmoCRMMissedTokenException
   */

  #[ArrayShape(['lead' => "array"])]
  public function update(
    int    $id,
    string $title = null,
    int  $price = null,
    int  $groupId = null,
    int $sourceId = null,
    string $payDate = null,
    int $order = null,
    string $integrator = null,
    int $responsibleUserId = null,
  ): array
  {
    $lead = AmocrmLead::find($id);

    $lead->update([
      'name' => $title,
      'price' => $price,
      'group_id' => $groupId,
      'responsible_user_id' => $responsibleUserId,
      'source_id' => $sourceId,
      'cf_pay_date' => $payDate,
      'cf_order' => $order,
      'cf_integrator' => $integrator,
    ]);

    $leadUpdated = AmocrmLead::find($lead->attributes['id']);

    return ['lead' => $leadUpdated->attributes];
  }

  /**
   * @throws Exception
   */

  #[ArrayShape(['leads' => "\App\Modules\Integration\Domain\Amocrm\Lead\LeadModel[]|\Illuminate\Support\Collection"])]
  public function list(
    array $with = [],
    array $filter = [],
    int $page = null,
    int $limit = null,
  ): array
  {
    $collection = AmocrmLead::list(with: $with, filter: $filter, page: $page, limit: $limit);

    return ['leads' => $collection];
  }
}
