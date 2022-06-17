<?php

namespace App\Modules\Integration\Domain\Amocrm\Contact;

use App\Modules\Integration\Core\Concerns\DataType;
use App\Modules\Integration\Domain\Amocrm\AmocrmResource;

class ContactResource extends AmocrmResource
{
  function endpoint(): string
  {
    return self::buildUrl(parent::endpoint(), 'contacts');
  }

  function dataType(): DataType
  {
    return parent::dataType();
  }
}
