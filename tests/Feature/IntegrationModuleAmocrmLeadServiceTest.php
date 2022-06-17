<?php

namespace Tests\Feature;

use App\Services\Integration\AmocrmLeadService;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\App;
use Tests\TestCase;

class IntegrationModuleAmocrmLeadServiceTest extends TestCase
{
  use WithFaker;

  public function testCreate(): void
  {
    $title = $this->faker->text(15);
    $payDate = $this->faker->date(format: 'd.m.Y');
    $price = $this->faker->numberBetween(100, 7300);

    $amocrmLeadService = App::make(AmocrmLeadService::class);

    $lead = $amocrmLeadService->create(
      title: $title,
      payDate: $payDate,
      price: $price
    );

    $this->assertIsArray($lead);
    $this->assertEquals($lead['name'], $title);
    $this->assertEquals($price, $lead['price']);
    $this->assertEquals($lead['pay_date'], $payDate);
  }

  public function testFind(): void
  {
    $title = $this->faker->text(15);
    $payDate = $this->faker->date(format: 'd.m.Y');
    $price = $this->faker->numberBetween(100, 7300);

    $amocrmLeadService = App::make(AmocrmLeadService::class);

    $test = $amocrmLeadService->create(
      title: $title,
      payDate: $payDate,
      price: $price
    );

    $lead = $amocrmLeadService->find($test['id']);

    $this->assertIsArray($lead);
    $this->assertEquals($test['id'], $lead['id']);
  }

  public function testUpdate(): void
  {
    $title = $this->faker->text(15);
    $payDate = $this->faker->date(format: 'd.m.Y');
    $price = $this->faker->numberBetween(100, 7300);
    $priceNew = $this->faker->numberBetween(8000, 10000);

    $amocrmLeadService = App::make(AmocrmLeadService::class);

    $test = $amocrmLeadService->create(
      title: $title,
      payDate: $payDate,
      price: $price
    );

    $leadId = $test['id'];

    $leadUpdated = $amocrmLeadService->update(
      id: $leadId,
      price: $priceNew
    );

    $this->assertIsArray($leadUpdated);
    $this->assertEquals($priceNew, $leadUpdated['price']);
  }

  /**
   * @throws BindingResolutionException
   */
  public function testListFilter(): void
  {
    $title = $this->faker->text(30);
    $price1 = $this->faker->numberBetween(100, 1000);
    $payDate1 = $this->faker->date(format: 'd.m.Y');
    $price2 = $this->faker->numberBetween(100, 1000);
    $payDate2 = $this->faker->date(format: 'd.m.Y');

    $amocrmLeadService = $this->app->make(AmocrmLeadService::class);

    $lead1 = $amocrmLeadService->create(
      title: $title,
      payDate: $payDate1,
      price: $price1
    );

    $lead2 = $amocrmLeadService->create(
      title: $title,
      payDate: $payDate2,
      price: $price2
    );

    $listFilter = $amocrmLeadService->list(filter: ['id' => [
      $lead1['id'], $lead2['id']]]);

    $this->assertIsArray($listFilter->all());
    $this->assertEquals(2, $listFilter->count());
    $this->assertEquals($lead1['id'], $listFilter[0]->attributes['id']);
    $this->assertEquals($lead2['id'], $listFilter[1]->attributes['id']);
  }

  /**
   * @throws BindingResolutionException
   */
  public function testListLimit()
  {
    $amocrmLeadService = $this->app->make(AmocrmLeadService::class);

    $listLimit = $amocrmLeadService->list(limit: 5);

    $this->assertIsArray($listLimit->all());
    $this->assertCount(5, $listLimit->all());
  }

  /**
   * @throws BindingResolutionException
   */
  public function testListPage()
  {
    $amocrmLeadService = $this->app->make(AmocrmLeadService::class);

    $listPage = $amocrmLeadService->list(page: 1);
    $this->assertIsArray($listPage->all());
  }
}
