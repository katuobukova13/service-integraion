<?php

namespace App\Modules\Integration\Domain\Amocrm\Lead;

use App\Modules\Integration\Domain\Amocrm\AmocrmResource;

class LeadResource extends AmocrmResource
{
  public function __construct()
  {
    $this->endpoint = $this->endpoint . '/leads';
    return parent::__construct();
  }
}
