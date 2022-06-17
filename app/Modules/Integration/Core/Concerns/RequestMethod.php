<?php

namespace App\Modules\Integration\Core\Concerns;

enum RequestMethod
{
  case GET;
  case HEAD;
  case POST;
  case PUT;
  case PATCH;
  case DELETE;
}
