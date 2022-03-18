<?php

namespace App\Http\Controllers;

use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMMissedTokenException;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use App\Http\Requests\LeadRequest;
use App\Services\Integration\AmocrmLeadService;
use Exception;
use JetBrains\PhpStorm\ArrayShape;

class AmocrmLeadController extends Controller
{
  /**
   * @throws AmoCRMApiException
   * @throws AmoCRMoAuthApiException
   * @throws AmoCRMMissedTokenException
   */

  #[ArrayShape(['lead' => "\App\Modules\Integration\Domain\Amocrm\Lead\LeadModel"])]
  public function store(LeadRequest $request, AmocrmLeadService $leadService): array
  {
    $attributes = $request->all();

    return $leadService->create(
      title: $attributes['title'],
      price: $attributes['price'],
      groupId: $attributes['group_id'] ?? null,
      sourceId: $attributes['source_id'] ?? null,
      payDate: $attributes['pay_date'] ?? null,
      order: $attributes['order'] ?? null,
      integrator: $attributes['integrator'] ?? null,
      responsibleUserId: $attributes['responsible_user_id'] ?? null,
    );
  }

  /**
   * @throws AmoCRMApiException
   * @throws AmoCRMoAuthApiException
   * @throws AmoCRMMissedTokenException
   */
  #[ArrayShape(['lead' => "\App\Modules\Integration\Domain\Amocrm\Lead\LeadModel"])]
  public function update(LeadRequest $request, AmocrmLeadService $leadService, int $id): array
  {
    $attributes = $request->all();

    return $leadService->update(
      id: $id,
      title: $attributes['title'] ?? null,
      price: $attributes['price'] ?? null,
      groupId: $attributes['group_id'] ?? null,
      sourceId: $attributes['source_id'] ?? null,
      payDate: $attributes['pay_date'] ?? null,
      order: $attributes['order'] ?? null,
      integrator: $attributes['integrator'] ?? null,
      responsibleUserId: $attributes['responsible_user_id'] ?? null,
    );
  }


  /**
   * @throws AmoCRMoAuthApiException
   * @throws AmoCRMApiException
   * @throws AmoCRMMissedTokenException
   */
  #[ArrayShape(['lead' => "array"])]
  public function show(AmocrmLeadService $leadService, int $id): array
  {
    return $leadService->find($id);
  }

  /**
   * @throws Exception
   */
  #[ArrayShape(['leads' => "\App\Modules\Integration\Domain\Amocrm\Lead\LeadModel[]|\Illuminate\Support\Collection"])]
  public function index(LeadRequest $request, AmocrmLeadService $leadService): array
  {
    $attributes = $request->request->all();

    return $leadService->list(
      with: $attributes['with'] ?? [],
      filter: $attributes['filter'] ?? [],
      page: $attributes['page'] ?? null,
      limit: $attributes['limit'] ?? null);
  }
}
