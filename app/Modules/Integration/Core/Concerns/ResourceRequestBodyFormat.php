<?php

namespace App\Modules\Integration\Core\Concerns;

enum ResourceRequestBodyFormat
{
  case FORM_PARAMS;
  case JSON;
  case QUERY;
}
