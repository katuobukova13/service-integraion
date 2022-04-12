<?php

namespace App\Modules\Integration\Domain\Getcourse\Deal;

use App\Models\GetcourseDeal;
use App\Models\GetcourseUser;
use App\Modules\Integration\Core\Concerns\ResourceRequestBodyFormat;
use App\Modules\Integration\Core\Concerns\ResourceRequestMethod;
use App\Modules\Integration\Core\Concerns\ResourceRequestOptions;
use App\Modules\Integration\Domain\Getcourse\GetcourseModel;
use App\Modules\Integration\Domain\Getcourse\GetcourseResource;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Throwable;

class DealModel extends GetcourseModel
{
  public function __construct(GetcourseResource $getcourseResource)
  {
    parent::__construct($getcourseResource);
  }

  public static function create(array $attributes)
  {
    $model = App::make(static::class);

    $user = GetcourseUser::where('email', $attributes['email'])->firstOrFail();

    DB::beginTransaction();

    try {
      $deal = GetcourseDeal::create(['number' => $attributes['number'] ?? '',
        'title' => $attributes['title'],
        'status' => $attributes['status'] ?? '',
        'user_id' => $user['id'],
        'sum' => $attributes['sum'] ?? 0,
        'paid' => $attributes['paid'] ?? '',
        'paid_at' => $attributes['paid_at'] ?? now()]);

      $response = $model->resource->fetch('deals', new ResourceRequestOptions(
        method: ResourceRequestMethod::POST,
        body: [
          'action' => 'add',
          'params' => base64_encode(collect([
           'user' => ['email' => $attributes['email']],
            'deal' => collect([
              'product_title' => $attributes['title']])
              ->merge([
                'deal_number' => $attributes['number'] ?? '',
                'deal_status' => $attributes['status'] ?? '',
                'user_id' => $attributes['user_id'] ?? '',
                'deal_cost' => $attributes['sum'] ?? '',
                'deal_is_paid' => $attributes['paid'] ?? '',
                'deal_finished_at' => $attributes['paid_at'] ?? '',
                'product_description' => $attributes['product_description'] ?? '',
                'quantity' => $attributes['quantity'] ?? '',
                'manager_email' => $attributes['manager_email'] ?? '',
                'deal_comment' => $attributes['deal_comment'] ?? '',
                'payment_type' => $attributes['payment_type'] ?? '',
                'payment_status' => $attributes['payment_status'] ?? '',
                'partner_email' => $attributes['partner_email'] ?? '',
                'deal_currency' => $attributes['deal_currency'] ?? '',
              ]),
            'system' => [
              'refresh_if_exists' => 1,
            ]
          ])->toJson())
        ],
        bodyFormat: ResourceRequestBodyFormat::FORM_PARAMS
      ));

      $attributes['id'] = $response['result']['deal_id'];

      $model->setAttributes($attributes);

      $deal->update(['number' => $response['result']['deal_id']]);

    } catch (Throwable $e) {
      DB::rollBack();
      throw $e;
    }

    DB::commit();

    return $model;
  }

  public static function find($id): static
  {
    $model = App::make(static::class);

    $deal = GetcourseDeal::where('number', $id)
      ->get();

    $model->setAttributes($deal->toArray());

    return $model;
  }
}
