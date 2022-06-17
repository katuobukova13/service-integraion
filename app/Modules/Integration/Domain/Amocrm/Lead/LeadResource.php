<?php

namespace App\Modules\Integration\Domain\Amocrm\Lead;

use App\Modules\Integration\Core\Concerns\DataType;
use App\Modules\Integration\Domain\Amocrm\AmocrmResource;

class LeadResource extends AmocrmResource
{
  function endpoint(): string
  {
    return self::buildUrl(parent::endpoint(), 'leads');
  }

  function dataType(): DataType
  {
    return parent::dataType();
  }
}
