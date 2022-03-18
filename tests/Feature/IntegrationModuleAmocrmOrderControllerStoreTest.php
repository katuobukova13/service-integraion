<?php

namespace Tests\Feature;

use App\Http\Requests\OrderRequest;
use App\Services\Integration\AmocrmOrderService;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\App;
use Tests\TestCase;

class IntegrationModuleAmocrmOrderControllerStoreTest extends TestCase
{
  use WithFaker;

  public function testStore(): void
  {
    $contactFirstName = $this->faker->firstName;
    $contactLastName = $this->faker->lastName;
    $contactPhone[] = $this->faker->phoneNumber;
    $contactEmail[] = $this->faker->email;
    $title = $this->faker->text(15);
    $price = $this->faker->numberBetween(100, 5959);
    $payDate = $this->faker->date('d.m.Y');

    $request = OrderRequest::create('/api/amocrm/order', 'POST', App::make(AmocrmOrderService::class)->create(
      contactFirstName: $contactFirstName,
      contactLastName: $contactLastName,
      contactPhone: $contactPhone,
      contactEmail: $contactEmail,
      title: $title,
      price: $price,
      payDate: $payDate,
    ));

    $this->assertEquals($request->request->all()['contact']['first_name'], $contactFirstName);
    $this->assertEquals($request->request->all()['contact']['last_name'], $contactLastName);
    $this->assertEquals($request->request->all()['contact']['name'], $contactFirstName . ' ' . $contactLastName);
    $this->assertEquals($request->request->all()['contact']["custom_fields_values"][0]['field_id'], config('services.amocrm.advance.custom_fields.contacts.email'));
    $this->assertContains($contactEmail[0], $request->request->all()['contact']["custom_fields_values"][0]['values'][0]);
    $this->assertEquals($request->request->all()['contact']["custom_fields_values"][1]['field_id'], config('services.amocrm.advance.custom_fields.contacts.phone'));
    $this->assertContains($contactPhone[0], $request->request->all()['contact']["custom_fields_values"][1]['values'][0]);

    $this->assertEquals($request->request->all()['lead']['name'], $title);
    $this->assertEquals($request->request->all()['lead']['price'], $price);
    $this->assertEquals($payDate, $request->request->all()['lead']["custom_fields_values"][0]['values'][0]['value']->format('d.m.Y'));

    $this->assertEquals("contacts", $request->request->all()['link'][0]["to_entity_type"]);
    $this->assertEquals($request->request->all()['link'][0]["to_entity_id"], $request->request->all()['contact']['id']);
  }
}
