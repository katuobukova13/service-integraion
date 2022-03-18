<?php

namespace Tests\Feature;

use App\Http\Requests\LeadRequest;
use App\Services\Integration\AmocrmLeadService;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\App;
use Tests\TestCase;

class IntegrationModuleAmocrmLeadControllerTest extends TestCase
{
  use WithFaker;

  public function testStore(): void
  {
    $title = $this->faker->text(15);
    $payDate = $this->faker->date(format: 'd.m.Y');
    $price = $this->faker->numberBetween(100, 7300);

    $request = LeadRequest::create('/api/amocrm/leads', 'POST',
      App::make(AmocrmLeadService::class)->create(
        title: $title,
        payDate: $payDate,
        price: $price
      ));

    $this->assertInstanceOf('App\Http\Requests\LeadRequest', $request);
    $this->assertEquals($request->request->all()['lead']['name'], $title);
    $this->assertEquals($request->request->all()['lead']["custom_fields_values"][0]['field_id'], config('services.amocrm.advance.custom_fields.leads.pay_date'));
    $this->assertEquals($payDate, $request->request->all()['lead']["custom_fields_values"][0]['values'][0]['value']->format('d.m.Y'));
    $this->assertEquals($request->request->all()['lead']['price'], $price);
  }

  public function testShow(): void
  {
    $title = $this->faker->text(15);
    $payDate = $this->faker->date(format: 'd.m.Y');
    $price = $this->faker->numberBetween(100, 7300);

    $request = LeadRequest::create('/api/amocrm/leads', 'POST',
      App::make(AmocrmLeadService::class)->create(
        title: $title,
        payDate: $payDate,
        price: $price
      ));

    $lead = LeadRequest::create('/api/amocrm/leads', 'GET',
      App::make(AmocrmLeadService::class)->find($request->request->all()['lead']['id']));

    $this->assertEquals($lead->query->all()['lead']['id'], $request->request->all()['lead']['id']);
  }

  public function testUpdate(): void
  {
    $title = $this->faker->text(15);
    $payDate = $this->faker->date(format: 'd.m.Y');
    $price = $this->faker->numberBetween(100, 7300);
    $titleUpdated = $this->faker->text(5);

    $request = LeadRequest::create('/api/amocrm/leads', 'POST',
      App::make(AmocrmLeadService::class)->create(
        title: $title,
        payDate: $payDate,
        price: $price
      ));

    $leadUpdated = LeadRequest::create('/api/amocrm/leads', 'PUT',
      App::make(AmocrmLeadService::class)->update(
        id: $request->request->all()['lead']['id'],
        title: $titleUpdated,
      ));

    $this->assertEquals($leadUpdated->request->all()['lead']['id'], $request->request->all()['lead']['id']);
    $this->assertEquals($titleUpdated, $leadUpdated['lead']['name']);
  }

  public function testList(): void
  {
    $payDate = $this->faker->date(format: 'd.m.Y');
    $price = $this->faker->numberBetween(100, 7300);
    $title1 = $this->faker->text(15);
    $title2 = $this->faker->text(15);
    $title3 = $this->faker->text(15);

    $lead1 = LeadRequest::create('/api/amocrm/leads', 'POST',
      App::make(AmocrmLeadService::class)->create(
        title: $title1,
        payDate: $payDate,
        price: $price
      ));

    $lead2 = LeadRequest::create('/api/amocrm/leads', 'POST',
      App::make(AmocrmLeadService::class)->create(
        title: $title2,
        payDate: $payDate,
        price: $price
      ));

    $lead3 = LeadRequest::create('/api/amocrm/leads', 'POST',
      App::make(AmocrmLeadService::class)->create(
        title: $title3,
        payDate: $payDate,
        price: $price
      ));

    $listFilter = LeadRequest::create('/api/amocrm/leads', 'GET',
      App::make(AmocrmLeadService::class)->list(
        filter: ['id' => [
          $lead1->request->all()['lead']['id'],
          $lead2->request->all()['lead']['id'],
          $lead3->request->all()['lead']['id']]]));

    $this->assertInstanceOf('App\Http\Requests\leadRequest', $listFilter);
    $this->assertEquals(3, $listFilter->query->all()['leads']->count());
    $this->assertTrue($listFilter->query->all()['leads']->contains('id', $lead1->request->all()['lead']['id']));
    $this->assertTrue($listFilter->query->all()['leads']->contains('id', $lead2->request->all()['lead']['id']));
    $this->assertTrue($listFilter->query->all()['leads']->contains('id', $lead3->request->all()['lead']['id']));

    $listLimit = leadRequest::create('/api/amocrm/leads', 'GET',
      App::make(AmocrmleadService::class)->list(limit: 5));

    $this->assertInstanceOf('App\Http\Requests\leadRequest', $listLimit);
    $this->assertEquals(5, $listLimit->query->all()['leads']->count());

    $listPage = leadRequest::create('/api/amocrm/leads', 'GET',
      App::make(AmocrmleadService::class)->list(page: 1));

    $this->assertInstanceOf('App\Http\Requests\leadRequest', $listPage);

    $listWith = leadRequest::create('/api/amocrm/leads', 'GET',
      App::make(AmocrmLeadService::class)->list(with: ['leads']));

    $this->assertInstanceOf('App\Http\Requests\leadRequest', $listWith);
  }
}
