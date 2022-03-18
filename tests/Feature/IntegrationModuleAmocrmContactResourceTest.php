<?php

namespace Tests\Feature;

use App\Modules\Integration\Domain\Amocrm\Contact\ContactResource;
use Tests\TestCase;

class IntegrationModuleAmocrmContactResourceTest extends TestCase
{
  public function testAmocrmEndPoint(): void
  {
    $resource = new ContactResource;

    $this->assertEquals('https://advancetest.amocrm.ru/api/v4/contacts', $resource->endpoint);
  }

  public function testAmocrmDataJson(): void
  {
    $resource = new ContactResource;

    $this->assertEquals('JSON', $resource->dataType->name);
  }

  /**
   * @body []
   * @fetch
   * @throws \Exception
   */
  public function testAmocrmFetch(): void
  {
    $resource = (new ContactResource)->fetch('',);

    $this->assertIsArray($resource);
  }
}
