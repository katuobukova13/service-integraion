<?php

namespace Tests\Feature;

use App\Services\Integration\AmocrmLeadService;
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
    $this->assertEquals($lead["custom_fields_values"][0]['field_id'], config('services.amocrm.advance.custom_fields.leads.pay_date'));
    $this->assertEquals($payDate, $lead["custom_fields_values"][0]['values'][0]['value']->format('d.m.Y'));
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

    $this->assertIsArray($listFilter);
    $this->assertEquals(2, $listFilter['leads']->count());
    $this->assertEquals($lead1['id'], $listFilter['leads'][0]->attributes['id']);
    $this->assertEquals($lead2['id'], $listFilter['leads'][1]->attributes['id']);
  }

  public function testListLimit()
  {
    $amocrmLeadService = $this->app->make(AmocrmLeadService::class);

    $listLimit = $amocrmLeadService->list(limit: 5);

    $this->assertIsArray($listLimit);
    $this->assertEquals(5, $listLimit['leads']->count());
  }

  public function testListPage()
  {
    $amocrmLeadService = $this->app->make(AmocrmLeadService::class);

    $listPage = $amocrmLeadService->list(page: 1);
    $this->assertIsArray($listPage);
  }

  public function testListWith()
  {
    $amocrmLeadService = $this->app->make(AmocrmLeadService::class);

    $listWith = $amocrmLeadService->list(with: ['leads']);
    $this->assertIsArray($listWith);
  }
}
