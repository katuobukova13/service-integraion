<?php

namespace App\Modules\Integration\Domain\Adveduplat\User;

use App\Modules\Integration\Domain\Adveduplat\AdveduplatResource;

final class UserResource extends AdveduplatResource
{
  protected function endpoint(): string
  {
    return self::buildUrl(parent::endpoint(), '/api/admin/users');
  }
}
