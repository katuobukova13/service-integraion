<?php

namespace Tests\Feature;

use App\Modules\Integration\Domain\Getcourse\GetcourseResource;
use League\Flysystem\Exception;
use Tests\TestCase;

class IntegrationModuleGetcourseResourceTest extends TestCase
{
  /**
   * @throws Exception
   */
  public function testEndPoint(): void
  {
    $resource = new GetcourseResource;

    $this->assertEquals("https://get.advance-club.ru/pl/api", $resource->endpoint());
  }

  public function testDataJson(): void
  {
    $resource = new GetcourseResource;

    $this->assertEquals('JSON', $resource->dataType()->name);
  }
}
