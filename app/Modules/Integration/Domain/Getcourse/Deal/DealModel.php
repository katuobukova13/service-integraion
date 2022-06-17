<?php

namespace App\Modules\Integration\Domain\Getcourse\Deal;

use App\Modules\Integration\Core\Concerns\RequestBodyFormat;
use App\Modules\Integration\Core\Concerns\RequestMethod;
use App\Modules\Integration\Core\Facades\BaseModel;
use App\Modules\Integration\Core\Facades\RequestOptions;
use App\Modules\Integration\Domain\Getcourse\GetcourseResource;
use Illuminate\Support\Facades\App;
use Throwable;

final class DealModel extends BaseModel
{
  public function __construct(
    public GetcourseResource $resource
  )
  {
  }

  /**
   * @throws Throwable
   */
  public static function createOrUpdate(array $attributes)
  {
    $model = App::make(self::class);

    $response = $model->resource->fetch('deals', new RequestOptions(
      method: RequestMethod::POST,
      body: [
        'action' => 'add',
        'params' => base64_encode(collect([
          'user' => ['email' => $attributes['email']],
          'deal' => collect([
            'product_title' => $attributes['title']])
            ->merge([
              'deal_number' => $attributes['number'] ?? [],
              'deal_status' => $attributes['status'] ?? [],
              'deal_cost' => $attributes['cost'] ?? [],
              'deal_is_paid' => $attributes['is_paid'] ?? [],
              'quantity' => $attributes['quantity'] ?? [],
              'manager_email' => $attributes['manager_email'] ?? [],
              'deal_comment' => $attributes['comment'] ?? [],
              'payment_type' => $attributes['payment_type'] ?? [],
              'payment_status' => $attributes['payment_status'],
              'deal_currency' => $attributes['currency'] ?? [],
            ]),
          'system' => [
            'refresh_if_exists' => 1,
          ]
        ])->toJson())
      ],
      bodyFormat: RequestBodyFormat::FORM_PARAMS
    ));

    $attributes['id'] = $response['result']['deal_id'];

    $model->setAttributes($attributes);

    return $model;
  }
}
