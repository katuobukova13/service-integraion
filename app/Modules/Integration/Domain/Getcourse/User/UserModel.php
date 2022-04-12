<?php

namespace App\Modules\Integration\Domain\Getcourse\User;

use App\Models\GetcourseUser;
use App\Modules\Integration\Core\Concerns\ResourceRequestBodyFormat;
use App\Modules\Integration\Core\Concerns\ResourceRequestMethod;
use App\Modules\Integration\Core\Concerns\ResourceRequestOptions;
use App\Modules\Integration\Domain\Getcourse\GetcourseModel;
use App\Modules\Integration\Domain\Getcourse\GetcourseResource;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Throwable;

class UserModel extends GetcourseModel
{
  public function __construct(GetcourseResource $getcourseResource)
  {
    parent::__construct($getcourseResource);
  }

  public static function create(array $attributes)
  {
    $model = App::make(static::class);

    DB::beginTransaction();

    try {
      $firstName = $attributes['first_name'] ?? '';
      $lastName = $attributes['last_name'] ?? '';
      $attributes['name'] =
        $firstName && $lastName != '' ? $firstName . ' ' . $lastName : '';

      $user = GetcourseUser::create(['email' => $attributes['email'],
        'id_getcourse' => $attributes['id_getcourse'] ?? '',
        'name' => $attributes['name'] ?? '',
        'city' => $attributes['city'] ?? '',
        'country' => $attributes['country'] ?? '',
        'phone' => $attributes['phone'] ?? '']);

      $response = $model->resource->fetch('users', new ResourceRequestOptions(
        method: ResourceRequestMethod::POST,
        body: [
          'action' => 'add',
          'params' => base64_encode(collect([
            'user' => collect(['email' => $attributes['email']])
              ->merge([
                'first_name' => $attributes['first_name'] ?? [],
                'last_name' => $attributes['last_name'] ?? [],
                'city' => $attributes['city'] ?? [],
                'country' => $attributes['country'] ?? [],
                'phone' => $attributes['phone'] ?? [],
                'group' => $attributes['group'] ?? [],
              ]),
            'system' => [
              'refresh_if_exists' => 1,
            ]
          ])->toJson())
        ],
        bodyFormat: ResourceRequestBodyFormat::FORM_PARAMS
      ));

      $attributes['id'] = $response['result']['user_id'];

      $model->setAttributes($attributes);

      $user->update(['id_getcourse' => $response['result']['user_id']]);

    } catch (Throwable $e) {
      DB::rollBack();
      throw $e;
    }

    DB::commit();

    return $model;
  }

  public function update(array $attributes)
  {
    $model = App::make(static::class);

    DB::beginTransaction();

    try {
      $firstName = $attributes['first_name'] ?? '';
      $lastName = $attributes['last_name'] ?? '';
      $attributes['name'] =
        $firstName && $lastName != '' ? $firstName . ' ' . $lastName : '';

      $user = GetcourseUser::where('email', $attributes['email'])->firstOrFail();

      $user->name = $attributes['name'] ?? $user->name;
      $user->phone = $attributes['phone'] ?? $user->phone;
      $user->city = $attributes['city'] ?? $user->city;
      $user->country = $attributes['country'] ?? $user->country;

      $user->save();

      $model->resource->fetch('users', new ResourceRequestOptions(
        method: ResourceRequestMethod::POST,
        body: [
          'action' => 'add',
          'params' => base64_encode(collect([
            'user' => collect(['email' => $attributes['email']])
              ->merge([
                'first_name' => $attributes['first_name'] ?? [],
                'last_name' => $attributes['last_name'] ?? [],
                'city' => $attributes['city'] ?? [],
                'country' => $attributes['country'] ?? [],
                'phone' => $attributes['phone'] ?? [],
                'group' => $attributes['group'] ?? [],
              ]),
            'system' => [
              'refresh_if_exists' => 1,
            ]
          ])->toJson())
        ],
        bodyFormat: ResourceRequestBodyFormat::FORM_PARAMS
      ));

      $model->setAttributes($attributes);

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

    $user = GetcourseUser::where('id_getcourse', $id)
      ->get();

    $model->setAttributes($user->toArray());

    return $model;
  }

  public static function list(?array $select = [], ?array $with = [], ?array $filter = [],
                              ?int   $page = null, ?int $limit = null): array
  {
    if ($filter !== []) {
      foreach ($filter as $key => $value) {
        switch ($key) {
          case 'name':
          case 'phone':
          case 'email':
          case 'city':
          case 'country':
          case 'id_getcourse':
            foreach ($value as $item) {
              $array[] = GetcourseUser::where($key, $item)
                ->get();
            }
            break;
          case 'created_at':
          case 'updated_at':
            if (is_array($value)) {
              $array[] = GetcourseUser::whereBetween($key, [
                date('Y-m-d', strtotime($value[0])),
                date('Y-m-d', strtotime($value[1]))
              ])
                ->get();
            } else {
              $array[] = GetcourseUser::whereDate($key,
                date('Y-m-d', strtotime($value)))
                ->get();
            }
            break;
          default:
            break;
        }
      }
    } else if ($limit) {
      $array[] = GetcourseUser::take($limit)->get();
    } else {
      foreach (GetcourseUser::all() as $user) {
        $array[] = $user;
      }
    }

    return $array;
  }
}
