<?php

namespace Tests\Feature;

use App\Modules\Integration\Domain\Getcourse\Deal\DealModel;
use Illuminate\Foundation\Testing\WithFaker;
use League\Flysystem\Exception;
use Tests\TestCase;
use Throwable;

class IntegrationModuleGetcourseDealModelTest extends TestCase
{
  use WithFaker;

  /**
   * @throws Exception
   * @throws Throwable
   */
  public function testCreateOrUpdate(): void
  {
    $email = "teskkik@testik.ru";
    $title = $this->faker->realText(10);
    $status = 'cancelled';
    $quantity = 2;
    $cost = 1;
    $comment = "testik";
    $paymentType = 'CARD';
    $paymentStatus = 'expected';

    $model = DealModel::createOrUpdate([
      "email" => $email,
      "title" => $title,
      "status" => $status,
      "quantity" => $quantity,
      "cost" => $cost,
      "comment" => $comment,
      "payment_type" => $paymentType,
      "payment_status" => $paymentStatus,
    ]);

    $this->assertInstanceOf(DealModel::class, $model);
    $this->assertEquals($title, $model->attributes['title']);
    $this->assertEquals($status, $model->attributes["status"]);
    $this->assertEquals($quantity, $model->attributes["quantity"]);
    $this->assertEquals($email, $model->attributes['email']);
    $this->assertEquals($cost, $model->attributes['cost']);
    $this->assertEquals($paymentType, $model->attributes['payment_type']);
    $this->assertEquals($paymentStatus, $model->attributes['payment_status']);
  }
}
