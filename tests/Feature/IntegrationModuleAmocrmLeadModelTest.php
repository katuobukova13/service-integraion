<?php

namespace Tests\Feature;

use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMMissedTokenException;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use App\Modules\Integration\Domain\Amocrm\AmocrmSamplingClause;
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

    $model = LeadModel::create(["name" => $title, "pay_date" => $payDate]);

    $lead = LeadModel::find($model->attributes['id']);

    $this->assertInstanceOf('App\Modules\Integration\Domain\Amocrm\Lead\LeadModel', $lead);
    $this->assertEquals($model->attributes['id'], $lead->attributes['id']);
  }

  public function testCreateLead(): void
  {
    $title = $this->faker->text(10);
    $payDate = $this->faker->date('d.m.Y');

    $model = LeadModel::create(["name" => $title, "pay_date" => $payDate]);

    $this->assertInstanceOf('App\Modules\Integration\Domain\Amocrm\Lead\LeadModel', $model);
    $this->assertEquals($title, $model->attributes['name']);
    $this->assertEquals($model->attributes['pay_date'], $payDate);
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

    $model = LeadModel::create(["name" => $title, "pay_date" => $payDate]);

    $lead = LeadModel::find($model->attributes['id']);

    $lead->update([
      'name' => $titleUpdate,
      'pay_date' => $payDateUpdate
    ]);

    $lead = LeadModel::find($model->attributes['id']);

    $this->assertInstanceOf(LeadModel::class, $lead);
    $this->assertEquals($titleUpdate, $lead->attributes['name']);
    $this->assertEquals($lead->attributes['pay_date'], $payDateUpdate);
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

    $listFilter = LeadModel::list(new AmocrmSamplingClause(filter: ['id' => [$model1->attributes['id'],
      $model2->attributes['id']]]));

    $this->assertInstanceOf(Collection::class, $listFilter);
    $this->assertEquals($listFilter[0]->attributes['id'], $model1->attributes['id']);
    $this->assertEquals($listFilter[1]->attributes['id'], $model2->attributes['id']);
  }

  /**
   * @throws Exception
   */
  public function testListLimit()
  {
    $listLimit = LeadModel::list(new AmocrmSamplingClause(limit: 2));

    $this->assertInstanceOf(Collection::class, $listLimit);
    $this->assertEquals(2, $listLimit->count());
  }

  /**
   * @throws Exception
   */
  public function testListPage()
  {
    $listPage = LeadModel::list(new AmocrmSamplingClause(page: 1));
    $this->assertInstanceOf(Collection::class, $listPage);
  }
}
