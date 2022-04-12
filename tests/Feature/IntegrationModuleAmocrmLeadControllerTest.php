<?php

namespace Tests\Feature;

use App\Http\Controllers\AmocrmLeadController;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class IntegrationModuleAmocrmLeadControllerTest extends TestCase
{
  use WithFaker;

  public function testStore(): void
  {
    $title = $this->faker->text(15);
    $payDate = $this->faker->date();
    $price = $this->faker->numberBetween(100, 7300);

    $response = $this->post('/api/amocrm/leads', [
      'title' => $title,
      'pay_date' => $payDate,
      'price' => $price,
    ]);

    $this->assertEquals($response['name'], $title);
    $this->assertEquals($response["custom_fields_values"][0]['field_id'], config('services.amocrm.advance.custom_fields.leads.pay_date'));
    $this->assertEquals($payDate, substr($response["custom_fields_values"][0]['values'][0]['value'], 0, 10));
    $this->assertEquals($response['price'], $price);
  }

  public function testShow(): void
  {
    $title = $this->faker->text(15);
    $payDate = $this->faker->date();
    $price = $this->faker->numberBetween(100, 7300);

    $response = $this->post('/api/amocrm/leads', [
      'title' => $title,
      'pay_date' => $payDate,
      'price' => $price,
    ]);

    $id = $response['id'];

    $lead = $this->get('/api/amocrm/leads/' . $id);

    $this->assertEquals($response['id'], $lead['id']);
  }

  public function testUpdate(): void
  {
    $title = $this->faker->text(15);
    $payDate = $this->faker->date(format: 'd.m.Y');
    $price = $this->faker->numberBetween(100, 7300);
    $titleUpdated = $this->faker->text(5);

    $response = $this->post('/api/amocrm/leads', [
      'title' => $title,
      'pay_date' => $payDate,
      'price' => $price,
    ]);

    $id = $response['id'];

    $lead = $this->put('/api/amocrm/leads/' . $id, [
        'title' => $titleUpdated,
      ]
    );

    $this->assertEquals($lead['id'], $response['id']);
    $this->assertEquals($titleUpdated, $lead['name']);
  }

  public function testListFilter(): void
  {
    $payDate = $this->faker->date(format: 'd.m.Y');
    $price = $this->faker->numberBetween(100, 7300);
    $title1 = $this->faker->text(15);
    $title2 = $this->faker->text(15);
    $title3 = $this->faker->text(15);

    $lead1 = $this->post('/api/amocrm/leads', [
      'title' => $title1,
      'pay_date' => $payDate,
      'price' => $price,
    ]);

    $lead2 = $this->post('/api/amocrm/leads', [
      'title' => $title2,
      'pay_date' => $payDate,
      'price' => $price,
    ]);


    $lead3 = $this->post('/api/amocrm/leads', [
      'title' => $title3,
      'pay_date' => $payDate,
      'price' => $price,
    ]);

    $listFilter = $this->get(action(
      [AmocrmLeadController::class, 'index'],
      ['filter' => ['id' => [
        $lead1['id'],
        $lead2['id'],
        $lead3['id']]]
      ]));

    $this->assertCount(3, $listFilter['leads']);
    $this->assertContains($lead1['id'], $listFilter['leads'][0]['attributes']);
    $this->assertContains($lead2['id'], $listFilter['leads'][1]['attributes']);
    $this->assertContains($lead3['id'], $listFilter['leads'][2]['attributes']);
  }

  public function testListLimit()
  {
    $listLimit = $this->get(action(
      [AmocrmLeadController::class, 'index'],
      ['limit' => 2]));

    $this->assertCount(2, $listLimit['leads']);
  }

  public function testListPage()
  {
    $listPage = $this->get(action(
      [AmocrmLeadController::class, 'index'],
      ['page' => 1]));

    $this->assertIsArray($listPage['leads']);
  }

  public function testListWith()
  {
    $listWith = $this->get(action(
      [AmocrmLeadController::class, 'index'],
      ['with' => 'contacts']));

    $this->assertIsArray($listWith['leads']);
  }
}
