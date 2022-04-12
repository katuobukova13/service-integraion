<?php

namespace App\Modules\Integration\Domain\Getcourse;

use App\Modules\Integration\Core\Facades\Resource;
use App\Modules\Integration\Core\Facades\ResourceModel;

class GetcourseModel extends ResourceModel
{
  public Resource $resource;

  public function __construct($resource)
  {
    $this->resource = $resource;
  }
}
