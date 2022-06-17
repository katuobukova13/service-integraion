<?php

namespace Tests\Feature;

use App\Services\Integration\AmocrmContactService;
use App\Services\Integration\AmocrmOrderService;
use Illuminate\Foundation\Testing\WithFaker;
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

    $amocrmOrderService = $this->app->make(AmocrmOrderService::class);

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
    $this->assertEquals($contactEmail[0], $order['contact']['emails'][0]);
    $this->assertEquals($order['contact']['phones'][0], $contactPhone[0]);

    $this->assertEquals($order['lead']['name'], $title);
    $this->assertEquals($order['lead']['price'], $price);
    $this->assertEquals($payDate, $order['lead']['pay_date']);

    $this->assertEquals("contacts", $order['link']["to_entity_type"]);
    $this->assertEquals($order['link']["to_entity_id"], $order['contact']['id']);
  }

  public function testCreateWithDublicatedEmail(): void
  {
    $contactFirstName = $this->faker->firstName;
    $contactLastName = $this->faker->lastName;
    $contactPhone[] = $this->faker->phoneNumber;
    $contactEmail[] = $this->faker->email;
    $contactFirstName1 = $this->faker->firstName;
    $contactLastName1 = $this->faker->lastName;
    $contactPhone1[] = $this->faker->phoneNumber;
    $title = $this->faker->text(15);
    $price = $this->faker->numberBetween(100, 5959);
    $payDate = $this->faker->date('d.m.Y');

    $amocrmContactService = $this->app->make(AmocrmContactService::class);

    $contact = $amocrmContactService->create(
      firstName: $contactFirstName,
      lastName: $contactLastName,
      phones: $contactPhone,
      emails: $contactEmail);

    $amocrmOrderService = $this->app->make(AmocrmOrderService::class);

    $order = $amocrmOrderService->create(
      contactFirstName: $contactFirstName1,
      contactLastName: $contactLastName1,
      contactPhone: $contactPhone1,
      contactEmail: $contactEmail,
      title: $title,
      price: $price,
      payDate: $payDate,
    );

    $this->assertIsArray($order);
    $this->assertEquals($order['contact']['id'], $contact['id']);
    $this->assertEquals($contact['first_name'], $contactFirstName);
    $this->assertEquals($contact['last_name'], $contactLastName);
    $this->assertEquals($contact['name'], $contactFirstName . ' ' . $contactLastName);
    $this->assertEquals($contact['emails'][0], $contactEmail[0]);
    $this->assertEquals($contact['phones'][0], $contactPhone[0]);

    $this->assertEquals($order['lead']['name'], $title);
    $this->assertEquals($order['lead']['price'], $price);
    $this->assertEquals($payDate, $order['lead']['pay_date']);

    $this->assertEquals("contacts", $order['link']["to_entity_type"]);
    $this->assertEquals($order['link']["to_entity_id"], $order['contact']['id']);
  }

  public function testCreateWithDublicatedPhone(): void
  {
    $contactFirstName = $this->faker->firstName;
    $contactLastName = $this->faker->lastName;
    $contactPhone[] = $this->faker->phoneNumber;
    $contactEmail[] = $this->faker->email;
    $contactFirstName1 = $this->faker->firstName;
    $contactLastName1 = $this->faker->lastName;
    $contactEmail1[] = $this->faker->email;
    $title = $this->faker->text(15);
    $price = $this->faker->numberBetween(100, 5959);
    $payDate = $this->faker->date('d.m.Y');

    $amocrmContactService = $this->app->make(AmocrmContactService::class);

    $contact = $amocrmContactService->create(
      firstName: $contactFirstName,
      lastName: $contactLastName,
      phones: $contactPhone,
      emails: $contactEmail);

    $amocrmOrderService = $this->app->make(AmocrmOrderService::class);

    $order = $amocrmOrderService->create(
      contactFirstName: $contactFirstName1,
      contactLastName: $contactLastName1,
      contactPhone: $contactPhone,
      contactEmail: $contactEmail1,
      title: $title,
      price: $price,
      payDate: $payDate,
    );

    $this->assertIsArray($order);
    $this->assertEquals($order['contact']['id'], $contact['id']);
    $this->assertEquals($contact['first_name'], $contactFirstName);
    $this->assertEquals($contact['last_name'], $contactLastName);
    $this->assertEquals($contact['name'], $contactFirstName . ' ' . $contactLastName);
    $this->assertEquals($contact['emails'][0], $contactEmail[0]);
    $this->assertEquals($contact['phones'][0], $contactPhone[0]);

    $this->assertEquals($order['lead']['name'], $title);
    $this->assertEquals($order['lead']['price'], $price);
    $this->assertEquals($payDate, $order['lead']['pay_date']);

    $this->assertEquals("contacts", $order['link']["to_entity_type"]);
    $this->assertEquals($order['link']["to_entity_id"], $order['contact']['id']);
  }
}
