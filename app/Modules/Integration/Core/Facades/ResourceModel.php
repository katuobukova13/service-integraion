<?php

namespace App\Modules\Integration\Core\Facades;

use App\Modules\Integration\Core\Concerns\Resourceable;
use Exception;
use Illuminate\Support\Facades\App;

abstract class ResourceModel extends BaseModel implements Resourceable
{
  public Resource $resource;
  public array $attributes = [];

  /**
   * @throws Exception
   */
  public function __get($key)
  {
    if (!isset($this->$$key)) {
      throw new Exception('Child class ' . static::class . ' ' . $key . ' property must be initialized. Use dependency injection for retrieving property value.');
    }

    return static::$$key;
  }

  /**
   * @throws Exception
   */
  public static function find(int $id): static
  {
    $model = App::make(static::class);

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
