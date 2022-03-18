<?php

namespace App\Modules\Integration\Domain\Amocrm\Contact;

use App\Modules\Integration\Domain\Amocrm\AmocrmResource;

class ContactResource extends AmocrmResource
{
  public function __construct()
  {
    $this->endpoint = $this->endpoint . '/contacts';

    return parent::__construct();
  }
}
