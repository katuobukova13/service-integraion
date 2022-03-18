<?php

namespace Tests\Feature;

use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMMissedTokenException;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use App\Modules\Integration\Domain\Amocrm\Lead\LeadModel;
use Exception;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class IntegrationModuleAmocrmLeadModelTest extends TestCase
{
  use WithFaker;

  public function testFindLead(): void
  {
    $title = $this->faker->text(10);
    $payDate = $this->faker->date;

    $model = LeadModel::create(["name" => $title, "cf_pay_date" => $payDate]);

    $lead = LeadModel::find($model->attributes['id']);

    $this->assertInstanceOf('App\Modules\Integration\Domain\Amocrm\Lead\LeadModel', $lead);
    $this->assertEquals($model->attributes['id'], $lead->attributes['id']);
  }

  public function testCreateLead(): void
  {
    $title = $this->faker->text(10);
    $payDate = $this->faker->date('d.m.Y');

    $model = LeadModel::create(["name" => $title, "cf_pay_date" => $payDate]);

    $this->assertInstanceOf('App\Modules\Integration\Domain\Amocrm\Lead\LeadModel', $model);
    $this->assertEquals($title, $model->attributes['name']);
    $this->assertEquals($model->attributes["custom_fields_values"][0]['field_id'], config('services.amocrm.advance.custom_fields.leads.pay_date'));
    $this->assertEquals($payDate, $model->attributes["custom_fields_values"][0]['values'][0]['value']->format('d.m.Y'));
  }

  /**
   * @throws AmoCRMoAuthApiException
   * @throws AmoCRMApiException
   * @throws AmoCRMMissedTokenException
   */
  public function testUpdateLead(): void
  {
    $title = $this->faker->text(10);
    $payDate = $this->faker->date('d.m.Y');

    $titleUpdate = $this->faker->title;
    $payDateUpdate = $this->faker->date('d.m.Y');

    $model = LeadModel::create(["name" => $title, "cf_pay_date" => $payDate]);

    $lead = LeadModel::find($model->attributes['id']);

    $lead->update([
      'name' => $titleUpdate,
      'cf_pay_date' => $payDateUpdate
    ]);

    $lead = LeadModel::find($model->attributes['id']);

    $this->assertInstanceOf('App\Modules\Integration\Domain\Amocrm\Lead\LeadModel', $lead);
    $this->assertEquals($titleUpdate, $lead->attributes['name']);
    $this->assertEquals($lead->attributes["custom_fields_values"][0]['field_id'], config('services.amocrm.advance.custom_fields.leads.pay_date'));
    $this->assertEquals($payDateUpdate, $lead->attributes["custom_fields_values"][0]['values'][0]['value']->timezone('Europe/Moscow')->format('d.m.Y'));
  }

  /**
   * @throws Exception
   */
  public function testListLeads(): void
  {
    $title = $this->faker->text(10);
    $price1 = $this->faker->numberBetween(1500, 6000);
    $price2 = $this->faker->numberBetween(1000, 4000);
    $price3 = $this->faker->numberBetween(500, 600);

    $model1 = LeadModel::create(["name" => $title, "price" => $price1]);
    $model2 = LeadModel::create(["name" => $title, "price" => $price2]);
    $model3 = LeadModel::create(["name" => $title, "price" => $price3]);

    $listFilter = LeadModel::list(filter: ['id' => [$model1->attributes['id'], $model2->attributes['id']]]);

    $this->assertInstanceOf('Illuminate\Support\Collection', $listFilter);
    $this->assertTrue($listFilter->count() == 2);
    $this->assertTrue($listFilter->contains('id', $model1->attributes['id']));
    $this->assertTrue($listFilter->contains('id', $model2->attributes['id']));
    $this->assertFalse($listFilter->contains('id', $model3->attributes['id']));

    $listLimit = LeadModel::list(limit: 5);

    $this->assertInstanceOf('Illuminate\Support\Collection', $listLimit);
    $this->assertEquals(5, $listLimit->count());

    $listPage = LeadModel::list(page: 1);
    $this->assertInstanceOf('Illuminate\Support\Collection', $listPage);

    $listWith = LeadModel::list(with: ['contacts']);
    $this->assertInstanceOf('Illuminate\Support\Collection', $listWith);
  }
}
