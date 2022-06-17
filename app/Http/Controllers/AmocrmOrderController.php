<?php

namespace App\Http\Controllers;

use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMMissedTokenException;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use AmoCRM\Exceptions\InvalidArgumentException;
use App\Http\Requests\Amocrm\Order\OrderStoreRequest;
use App\Services\Integration\AmocrmOrderService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use JetBrains\PhpStorm\ArrayShape;

class AmocrmOrderController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function index()
  {
    //
  }

  /**
   * @throws InvalidArgumentException
   * @throws AmoCRMApiException
   * @throws AmoCRMMissedTokenException
   * @throws AmoCRMoAuthApiException
   */
  #[ArrayShape(['contact' => "array", 'lead' => "array", 'link' => "array"])]
  public function store(OrderStoreRequest $request, AmocrmOrderService $orderService): array
  {
    $attributes = $request->validated();

    return $orderService->create(
      contactFirstName: $attributes['first_name'],
      contactLastName: $attributes['last_name'],
      contactPhone: $attributes['phones'],
      contactEmail: $attributes['emails'],
      title: $attributes['title'],
      price: $attributes['price'] ?? null,
      payDate: $attributes['pay_date'] ?? null,
      contactCity: $attributes['city'] ?? null,
      contactCountry: $attributes['country'] ?? null,
      contactPosition: $attributes['position'] ?? null,
      contactPartner: $attributes['partner'] ?? null,
      groupId: $attributes['group_id'] ?? null,
      responsibleUserId: $attributes['responsible_user_id'] ?? null,
      sourceId: $attributes['source_id'] ?? null,
      orderId: $attributes['order_id'] ?? null,
      orderNum: $attributes['order_num'] ?? null,
      integrator: $attributes['integrator'] ?? ''
    );
  }

  /**
   * Display the specified resource.
   *
   * @param int $id
   * @return Response
   */
  public function show($id)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param Request $request
   * @param int $id
   * @return Response
   */
  public function update(Request $request, int $id)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param int $id
   * @return Response
   */
  public function destroy(int $id)
  {
    //
  }
}
