<?php

namespace App\Modules\Integration\Core\Concerns;

enum RequestBodyFormat
{
  case FORM_PARAMS;
  case JSON;
  case QUERY;
}
