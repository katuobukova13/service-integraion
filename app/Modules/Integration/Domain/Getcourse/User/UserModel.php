<?php

namespace App\Modules\Integration\Domain\Getcourse\User;

use App\Modules\Integration\Core\Concerns\RequestBodyFormat;
use App\Modules\Integration\Core\Concerns\RequestMethod;
use App\Modules\Integration\Core\Facades\BaseModel;
use App\Modules\Integration\Core\Facades\RequestOptions;
use App\Modules\Integration\Domain\Getcourse\GetcourseResource;
use Illuminate\Support\Facades\App;
use Throwable;

final class UserModel extends BaseModel
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

    $response = $model->resource->fetch('users', new RequestOptions(
      method: RequestMethod::POST,
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
              'group_name' => $attributes['group'] ?? [],
            ]),
          'system' => [
            'refresh_if_exists' => 1,
          ]
        ])->toJson())
      ],
      bodyFormat: RequestBodyFormat::FORM_PARAMS
    ));

    $attributes['id'] = $response['result']['user_id'];

    $model->setAttributes($attributes);

    return $model;
  }

//  public static function find($id): self
//  {
//    $model = App::make(self::class);
//
//    $user = GetcourseUser::where('id_getcourse', $id)
//      ->get();
//
//    $model->setAttributes($user->toArray());
//
//    return $model;
//  }
//
//  public static function list(?array $select = [], ?array $with = [], ?array $filter = [],
//                              ?int   $page = null, ?int $limit = null): array
//  {
//    if ($filter !== []) {
//      foreach ($filter as $key => $value) {
//        switch ($key) {
//          case 'name':
//          case 'phone':
//          case 'email':
//          case 'city':
//          case 'country':
//          case 'id_getcourse':
//            foreach ($value as $item) {
//              $array[] = GetcourseUser::where($key, $item)
//                ->get();
//            }
//            break;
//          case 'created_at':
//          case 'updated_at':
//            if (is_array($value)) {
//              $array[] = GetcourseUser::whereBetween($key, [
//                date('Y-m-d', strtotime($value[0])),
//                date('Y-m-d', strtotime($value[1]))
//              ])
//                ->get();
//            } else {
//              $array[] = GetcourseUser::whereDate($key,
//                date('Y-m-d', strtotime($value)))
//                ->get();
//            }
//            break;
//          default:
//            break;
//        }
//      }
//    } else if ($limit) {
//      $array[] = GetcourseUser::take($limit)->get();
//    } else {
//      foreach (GetcourseUser::all() as $user) {
//        $array[] = $user;
//      }
//    }
//
//    return $array;
//  }
}
