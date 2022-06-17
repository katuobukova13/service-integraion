<?php

namespace Tests\Feature;

use App\Http\Requests\Getcourse\Deal\DealStoreRequest;
use App\Services\Integration\GetcourseDealService;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\App;
use Tests\TestCase;
use Throwable;

class IntegrationModuleGetcourseDealControllerTest extends TestCase
{
  use WithFaker;

  /**
   * @throws Throwable
   */
  public function testStore(): void
  {
    $email = "teskkik@testik.ru";
    $title = $this->faker->realText(10);
    $status = 'cancelled';
    $quantity = 2;
    $cost = 1;
    $comment = "testikService";
    $paymentType = 'CARD';
    $paymentStatus = 'expected';

    $deal = DealStoreRequest::create('deals', 'POST',
      App::make(GetcourseDealService::class)->createOrUpdate(
        email: $email,
        title: $title,
        status: $status,
        quantity: $quantity,
        cost: $cost,
        comment: $comment,
        paymentType: $paymentType,
        paymentStatus: $paymentStatus,
      ));
    
    $this->assertEquals($title, $deal['title']);
    $this->assertEquals($status, $deal["status"]);
    $this->assertEquals($quantity, $deal["quantity"]);
    $this->assertEquals($email, $deal['email']);
    $this->assertEquals($cost, $deal['cost']);
    $this->assertEquals($paymentType, $deal['payment_type']);
    $this->assertEquals($paymentStatus, $deal['payment_status']);
  }
}
