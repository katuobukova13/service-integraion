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
    $payDate = $this->faker->date('d.m.Y');
    $price = $this->faker->numberBetween(100, 7300);

    $response = $this->post('/api/v1/amocrm/leads', [
      'title' => $title,
      'pay_date' => $payDate,
      'price' => $price,
    ]);

    $this->assertEquals($response['name'], $title);
    $this->assertEquals($response['pay_date'], $payDate);
    $this->assertEquals($response['price'], $price);
  }

  public function testShow(): void
  {
    $title = $this->faker->text(15);
    $price = $this->faker->numberBetween(100, 7300);

    $response = $this->post('/api/v1/amocrm/leads', [
      'title' => $title,
      'price' => $price,
    ]);

    $id = $response['id'];

    $lead = $this->get('/api/v1/amocrm/leads/' . $id);

    $this->assertEquals($response['id'], $lead['id']);
  }

  public function testUpdate(): void
  {
    $title = $this->faker->text(15);
    $payDate = $this->faker->date(format: 'd.m.Y');
    $price = $this->faker->numberBetween(100, 7300);
    $titleUpdated = $this->faker->text(5);

    $response = $this->post('/api/v1/amocrm/leads', [
      'title' => $title,
      'pay_date' => $payDate,
      'price' => $price,
    ]);

    $id = $response['id'];

    $lead = $this->put('/api/v1/amocrm/leads/' . $id, [
        'title' => $titleUpdated,
      ]
    );

    $this->assertEquals($lead['id'], $response['id']);
    $this->assertEquals($titleUpdated, $lead['name']);
  }

  public function testListFilter(): void
  {
    $payDate = $this->faker->date('d.m.Y');
    $price = $this->faker->numberBetween(100, 7300);
    $title1 = $this->faker->text(15);
    $title2 = $this->faker->text(15);
    $title3 = $this->faker->text(15);

    $lead1 = $this->post('/api/v1/amocrm/leads', [
      'title' => $title1,
      'price' => $price,
    ]);

    $lead2 = $this->post('/api/v1/amocrm/leads', [
      'title' => $title2,
      'price' => $price,
    ]);


    $lead3 = $this->post('/api/v1/amocrm/leads', [
      'title' => $title3,
      'price' => $price,
    ]);

    $listFilter = $this->get(action(
      [AmocrmLeadController::class, 'index'],
      ['filter' => ['id' => [
        $lead1['id'],
        $lead2['id'],
        $lead3['id']]]
      ]));

    $this->assertCount(3, $listFilter->json());
    $this->assertContains($lead1['id'], $listFilter[0]);
    $this->assertContains($lead2['id'], $listFilter[1]);
    $this->assertContains($lead3['id'], $listFilter[2]);
  }

  public function testListLimit()
  {
    $listLimit = $this->get(action(
      [AmocrmLeadController::class, 'index'],
      ['limit' => 2]));

    $this->assertCount(2, $listLimit->json());
  }

  public function testListPage()
  {
    $listPage = $this->get(action(
      [AmocrmLeadController::class, 'index'],
      ['page' => 1]));

    $this->assertIsArray($listPage->json());
  }
}
