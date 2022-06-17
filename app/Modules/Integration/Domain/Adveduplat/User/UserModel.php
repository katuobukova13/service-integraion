<?php

namespace App\Modules\Integration\Domain\Adveduplat\User;

use App\Modules\Integration\Core\Concerns\Crud;
use App\Modules\Integration\Core\Facades\BaseModel;
use Exception;
use Illuminate\Support\Facades\App;

final class UserModel extends BaseModel implements Crud
{
  public function __construct(public UserResource $resource)
  {
  }

  /**
   * @throws Exception
   */
  public static function find(int $id): self
  {
    /**
     * @var UserModel $model
     */

    $model = App::make(self::class);

    $response = $model->resource->fetch($id);

    $model->setAttributes($response);

    return $model;
  }


  public static function create(array $attributes)
  {
  }

  public function update(array $attributes)
  {
  }
}
