<?php

namespace Tests\Feature;

use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMMissedTokenException;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use App\Modules\Integration\Domain\Amocrm\Lead\LeadModel;
use Exception;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Collection;
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

    $this->assertInstanceOf(LeadModel::class, $lead);
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

    $model1 = LeadModel::create(["name" => $title, "price" => $price1]);
    $model2 = LeadModel::create(["name" => $title, "price" => $price2]);

    $listFilter = LeadModel::list(filter: ['id' => [$model1->attributes['id'], $model2->attributes['id']]]);

    $this->assertInstanceOf(Collection::class, $listFilter);
    $this->assertEquals($listFilter[0]->attributes['id'], $model1->attributes['id']);
    $this->assertEquals($listFilter[1]->attributes['id'], $model2->attributes['id']);
  }

  /**
   * @throws Exception
   */
  public function testListLimit()
  {
    $listLimit = LeadModel::list(limit: 2);

    $this->assertInstanceOf(Collection::class, $listLimit);
    $this->assertEquals(2, $listLimit->count());
  }

  /**
   * @throws Exception
   */
  public function testListPage()
  {
    $listPage = LeadModel::list(page: 1);
    $this->assertInstanceOf(Collection::class, $listPage);
  }

  /**
   * @throws Exception
   */
  public function testListWithContacts()
  {
    $listWith = LeadModel::list(with: ['contacts']);
    $this->assertInstanceOf(Collection::class, $listWith);
  }
}
