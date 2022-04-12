<?php

namespace Tests\Feature;

use App\Modules\Integration\Core\Concerns\ResourceRequestMethod;
use App\Modules\Integration\Core\Concerns\ResourceRequestOptions;
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
    $resource = (new LeadResource)->fetch('', new ResourceRequestOptions(
      method: ResourceRequestMethod::GET,
    ));

    $this->assertIsArray($resource);
  }
}
