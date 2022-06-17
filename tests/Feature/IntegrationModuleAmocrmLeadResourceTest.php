<?php

namespace Tests\Feature;

use App\Modules\Integration\Core\Concerns\RequestMethod;
use App\Modules\Integration\Core\Facades\RequestOptions;
use App\Modules\Integration\Domain\Amocrm\Lead\LeadResource;
use Exception;
use Tests\TestCase;

class IntegrationModuleAmocrmLeadResourceTest extends TestCase
{
  public function testAmocrmEndPoint(): void
  {
    $resource = new LeadResource;

    $this->assertEquals('https://advancetest.amocrm.ru/api/v4/leads', $resource->endpoint());
  }

  public function testAmocrmDataJson(): void
  {
    $resource = new LeadResource;

    $this->assertEquals('JSON', $resource->dataType()->name);
  }

  /**
   * @body []
   * @fetch
   * @throws Exception
   */
  public function testAmocrmFetch(): void
  {
    $resource = (new LeadResource)->fetch('', new RequestOptions(
      method: RequestMethod::GET,
    ));

    $this->assertIsArray($resource);
  }
}
