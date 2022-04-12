<?php

namespace Tests\Feature;

use App\Modules\Integration\Core\Concerns\ResourceRequestMethod;
use App\Modules\Integration\Core\Concerns\ResourceRequestOptions;
use App\Modules\Integration\Domain\Amocrm\Contact\ContactResource;
use Exception;
use Tests\TestCase;

class IntegrationModuleAmocrmContactResourceTest extends TestCase
{
  public function testAmocrmEndPoint(): void
  {
    $resource = new ContactResource;

    $this->assertEquals('https://advancetest.amocrm.ru/api/v4/contacts', $resource->endpoint());
  }

  public function testAmocrmDataJson(): void
  {
    $resource = new ContactResource;

    $this->assertEquals('JSON', $resource->dataType()->name);
  }

  /**
   * @throws Exception
   */
  public function testAmocrmFetch(): void
  {
    $resource = (new ContactResource)->fetch('', new ResourceRequestOptions(
      method: ResourceRequestMethod::GET,
    ));

    $this->assertIsArray($resource);
  }
}
