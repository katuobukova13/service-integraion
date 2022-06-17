<?php

namespace App\Http\Controllers;

use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMMissedTokenException;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use App\Http\Requests\Amocrm\Lead\LeadIndexRequest;
use App\Http\Requests\Amocrm\Lead\LeadStoreRequest;
use App\Http\Requests\Amocrm\Lead\LeadUpdateRequest;
use App\Services\Integration\AmocrmLeadService;
use Exception;
use Illuminate\Support\Collection;

class AmocrmLeadController extends Controller
{
  /**
   * @param LeadStoreRequest $request
   * @param AmocrmLeadService $leadService
   * @return array
   * @throws Exception
   */
  public function store(LeadStoreRequest $request, AmocrmLeadService $leadService): array
  {
    $attributes = $request->all();

    return $leadService->create(
      title: $attributes['title'],
      price: $attributes['price'] ?? null,
      groupId: $attributes['group_id'] ?? null,
      sourceId: $attributes['source_id'] ?? null,
      payDate: $attributes['pay_date'] ?? null,
      city: $attributes['city'] ?? null,
      orderId: $attributes['order_id'] ?? null,
      orderNum: $attributes['order_num'] ?? null,
      integrator: $attributes['integrator'] ?? '',
      responsibleUserId: $attributes['responsible_user_id'] ?? null,
    );
  }

  /**
   * @throws AmoCRMApiException
   * @throws AmoCRMoAuthApiException
   * @throws AmoCRMMissedTokenException
   */
  public function update(LeadUpdateRequest $request, AmocrmLeadService $leadService, int $id): array
  {
    $attributes = $request->all();

    return $leadService->update(
      id: $id,
      integrator: $attributes['integrator'] ?? null,
      title: $attributes['title'] ?? null,
      price: $attributes['price'] ?? null,
      groupId: $attributes['group_id'] ?? null,
      sourceId: $attributes['source_id'] ?? null,
      payDate: $attributes['pay_date'] ?? null,
      city: $attributes['city'] ?? null,
      orderId: $attributes['order_id'] ?? null,
      orderNum: $attributes['order_num'] ?? null,
      responsibleUserId: $attributes['responsible_user_id'] ?? null,
    );
  }

  public function show(AmocrmLeadService $leadService, int $id): array
  {
    return $leadService->find($id);
  }

  /**
   * @throws Exception
   */
  public function index(LeadIndexRequest $request, AmocrmLeadService $leadService): Collection
  {
    $attributes = $request->validated();

    return $leadService->list(
      with: $attributes['with'] ?? [],
      filter: $attributes['filter'] ?? [],
      page: $attributes['page'] ?? 1,
      limit: $attributes['limit'] ?? 50,
    )->map(fn($leadModel) => $leadModel->attributes);
  }
}
