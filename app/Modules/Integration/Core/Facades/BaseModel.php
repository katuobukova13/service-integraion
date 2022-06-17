<?php

namespace App\Modules\Integration\Core\Facades;

abstract class BaseModel
{
  public readonly array $attributes;

  public function setAttributes(array $attributes): static
  {
    $this->attributes = $attributes;

    return $this;
  }

  public function getAttribute(string $attribute)
  {
    return $this->attributes[$attribute];
  }
}
