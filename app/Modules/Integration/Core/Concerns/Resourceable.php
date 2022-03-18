<?php

namespace App\Modules\Integration\Core\Concerns;

interface Resourceable
{
  public static function find(int $id);

  public static function create(array $attributes);

  public function update(array $attributes);
}
