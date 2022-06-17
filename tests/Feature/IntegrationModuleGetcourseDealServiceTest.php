<?php

namespace Tests\Feature;

use App\Services\Integration\GetcourseDealService;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Throwable;

class IntegrationModuleGetcourseDealServiceTest extends TestCase
{
  use WithFaker;

  /**
   * @throws Throwable
   */
  public function testCreateOrUpdate(): void
  {
    $email = "teskkik@testik.ru";
    $title = $this->faker->realText(10);
    $status = 'cancelled';
    $quantity = 2;
    $cost = 1;
    $comment = "testikService";
    $paymentType = 'CARD';
    $paymentStatus = 'expected';

    $getcourseDealService = $this->app->make(GetcourseDealService::class);

    $deal = $getcourseDealService->createOrUpdate(
      email: $email,
      title: $title,
      status: $status,
      quantity: $quantity,
      cost: $cost,
      comment: $comment,
      paymentType: $paymentType,
      paymentStatus: $paymentStatus,
    );

    $this->assertIsArray($deal);
    $this->assertEquals($title, $deal['title']);
    $this->assertEquals($status, $deal["status"]);
    $this->assertEquals($quantity, $deal["quantity"]);
    $this->assertEquals($email, $deal['email']);
    $this->assertEquals($cost, $deal['cost']);
    $this->assertEquals($paymentType, $deal['payment_type']);
    $this->assertEquals($paymentStatus, $deal['payment_status']);
  }
}
