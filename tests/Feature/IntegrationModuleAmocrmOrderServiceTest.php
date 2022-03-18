<?php

namespace Tests\Feature;

use App\Services\Integration\AmocrmOrderService;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\App;
use Tests\TestCase;

class IntegrationModuleAmocrmOrderServiceTest extends TestCase
{
  use WithFaker;

  public function testCreate(): void
  {
    $contactFirstName = $this->faker->firstName;
    $contactLastName = $this->faker->lastName;
    $contactPhone[] = $this->faker->phoneNumber;
    $contactEmail[] = $this->faker->email;
    $title = $this->faker->text(15);
    $price = $this->faker->numberBetween(100, 5959);
    $payDate = $this->faker->date('d.m.Y');

    $amocrmOrderService = App::make(AmocrmOrderService::class);

    $order = $amocrmOrderService->create(
      contactFirstName: $contactFirstName,
      contactLastName: $contactLastName,
      contactPhone: $contactPhone,
      contactEmail: $contactEmail,
      title: $title,
      price: $price,
      payDate: $payDate,
    );

    $this->assertIsArray($order);

    $this->assertEquals($order['contact']['first_name'], $contactFirstName);
    $this->assertEquals($order['contact']['last_name'], $contactLastName);
    $this->assertEquals($order['contact']['name'], $contactFirstName . ' ' . $contactLastName);
    $this->assertEquals($order['contact']["custom_fields_values"][0]['field_id'], config('services.amocrm.advance.custom_fields.contacts.email'));
    $this->assertContains($contactEmail[0], $order['contact']["custom_fields_values"][0]['values'][0]);
    $this->assertEquals($order['contact']["custom_fields_values"][1]['field_id'], config('services.amocrm.advance.custom_fields.contacts.phone'));
    $this->assertContains($contactPhone[0], $order['contact']["custom_fields_values"][1]['values'][0]);

    $this->assertEquals($order['lead']['name'], $title);
    $this->assertEquals($order['lead']['price'], $price);
    $this->assertEquals($payDate, $order['lead']["custom_fields_values"][0]['values'][0]['value']->format('d.m.Y'));

    $this->assertEquals("contacts", $order['link'][0]["to_entity_type"]);
    $this->assertEquals($order['link'][0]["to_entity_id"], $order['contact']['id']);
  }
}
