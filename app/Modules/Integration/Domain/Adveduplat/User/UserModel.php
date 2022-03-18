<?php

namespace App\Modules\Integration\Domain\Adveduplat\User;

use App\Modules\Integration\Core\Facades\ResourceModel as SyncModel;
use Illuminate\Support\Facades\App;

class UserModel extends SyncModel
{
  public static function find(int $id): static
  {
    $model = App::make(static::class);

    $response = App::make(UserResource::class)->fetch($id);

    $model->setAttributes($response);

    return $model;
  }

  public static function create(array $attributes)
  {
    $model = App::make(static::class);

    $response = App::make(UserResource::class)->fetch('', options: [
      'method' => 'POST',
      'body' => $attributes
    ]);

    $model->setAttributes($response);

    return $model;
  }

  public function update($attributes)
  {
    $model = App::make(static::class);

    $response = App::make(UserResource::class)->fetch($attributes['id'], options: [
      'method' => 'PUT',
      'body' => $attributes
    ]);

    $model->setAttributes($response);

    return $model;
  }

  public function delete(): void
  {
    //пока невозможно
  }
}
