<?php

namespace App\Http\Controllers;

use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMMissedTokenException;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use AmoCRM\Exceptions\InvalidArgumentException;
use App\Http\Requests\OrderRequest;
use App\Services\Integration\AmocrmOrderService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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
  public function store(OrderRequest $request, AmocrmOrderService $orderService)
  {
    $attributes = $request->validated();

    return $orderService->create(
      contactFirstName: $attributes['first_name'],
      contactLastName: $attributes['last_name'],
      contactPhone: $attributes['phone'],
      contactEmail: $attributes['email'],
      title: $attributes['title'],
      price: $attributes['price'],
      payDate: $attributes['pay_date'],
      contactCity: $attributes['city'] ?? null,
      contactCountry: $attributes['country'] ?? null,
      contactPosition: $attributes['position'] ?? null,
      contactPartner: $attributes['partner'] ?? null,
      groupId: $attributes['group_id'] ?? null,
      responsibleUserId: $attributes['responsible_user_id'] ?? null,
      sourceId: $attributes['source_id'] ?? null,
      order: $attributes['order'] ?? null,
      integrator: $attributes['integrator'] ?? null
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
