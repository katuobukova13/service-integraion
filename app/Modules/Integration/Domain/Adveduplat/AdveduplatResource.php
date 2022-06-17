<?php

namespace App\Modules\Integration\Domain\Adveduplat;

use App\Modules\Integration\Core\Concerns\DataType;
use App\Modules\Integration\Core\Facades\Resource;

class AdveduplatResource extends Resource
{
  protected function endpoint(): string
  {
    return config('services.adveduplat.domain');
  }

  protected function dataType(): DataType
  {
    return DataType::JSON;
  }
}
