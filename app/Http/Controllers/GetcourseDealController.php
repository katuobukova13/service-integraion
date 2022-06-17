<?php

namespace App\Http\Controllers;

use App\Http\Requests\Getcourse\Deal\DealStoreRequest;
use App\Services\Integration\GetcourseDealService;
use Throwable;

class GetcourseDealController extends Controller
{
  /**
   * @throws Throwable
   */
  public function store(DealStoreRequest $request, GetcourseDealService $dealService): array
  {
    $attributes = $request->validated();

    $deal = $dealService->createOrUpdate(
      email: $attributes['email'],
      title: $attributes['title'],
      number: $attributes['number'] ?? null,
      quantity: $attributes['quantity'] ?? null,
      cost: $attributes['cost'] ?? null,
      status: $attributes['status'] ?? '',
      dealPaid: $attributes['is_paid'] ?? '',
      managerEmail: $attributes['manager_email'] ?? '',
      comment: $attributes['comment'] ?? '',
      paymentType: $attributes['payment_type'] ?? '',
      paymentStatus: $attributes['payment_status'] ?? '',
      currency: $attributes['deal_currency'] ?? 'RUB',
    );

    $updatedOrCreatedDealFields = [];
    foreach ($deal as $key => $value) {
      if ($value !== null && $value !== '') {
        $updatedOrCreatedDealFields[$key] = $value;
      }
    }

    return $updatedOrCreatedDealFields;
  }
}
