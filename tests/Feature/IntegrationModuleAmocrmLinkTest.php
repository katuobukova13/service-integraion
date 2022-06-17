<?php

namespace Tests\Feature;

use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMMissedTokenException;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use AmoCRM\Exceptions\InvalidArgumentException;
use App\Modules\Integration\Domain\Amocrm\Contact\ContactModel;
use App\Modules\Integration\Domain\Amocrm\Lead\LeadModel;
use App\Modules\Integration\Domain\Amocrm\Link\LinkModel as LinkModel;
use Exception;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class IntegrationModuleAmocrmLinkTest extends TestCase
{
  use WithFaker;

  /**
   * @throws AmoCRMoAuthApiException
   * @throws InvalidArgumentException
   * @throws AmoCRMApiException
   * @throws AmoCRMMissedTokenException
   * @throws Exception
   */
  public function testCreateLinkFromContactsToLeads(): void
  {
    $firstName = $this->faker->firstName;
    $lastName = $this->faker->lastName;
    $phone[] = $this->faker->phoneNumber;
    $title = $this->faker->text(15);
    $price = $this->faker->numberBetween(100, 5959);

    $amoContact = ContactModel::create(["first_name" => $firstName, "last_name" => $lastName, "phones" => $phone]);
    $amoLead = LeadModel::create(["name" => $title, "price" => $price]);

    $link = LinkModel::link($amoContact, $amoLead);

    $this->assertInstanceOf(LinkModel::class, $link);
    $this->assertEquals("leads", $link->attributes[0]["to_entity_type"]);
    $this->assertEquals($link->attributes[0]["to_entity_id"], $amoLead->attributes['id']);
  }

  /**
   * @throws AmoCRMoAuthApiException
   * @throws InvalidArgumentException
   * @throws AmoCRMApiException
   * @throws AmoCRMMissedTokenException
   * @throws Exception
   */
  public function testCreateLinkFromLeadsToContacts(): void
  {
    $firstName = $this->faker->firstName;
    $lastName = $this->faker->lastName;
    $phone[] = $this->faker->phoneNumber;
    $title = $this->faker->text(15);
    $price = $this->faker->numberBetween(100, 5959);

    $amoContact = ContactModel::create(["first_name" => $firstName, "last_name" => $lastName, "phones" => $phone]);
    $amoLead = LeadModel::create(["name" => $title, "price" => $price]);

    $link = LinkModel::link($amoLead, $amoContact);

    $this->assertInstanceOf(LinkModel::class, $link);
    $this->assertEquals("contacts", $link->attributes[0]["to_entity_type"]);
    $this->assertEquals($link->attributes[0]["to_entity_id"], $amoContact->attributes['id']);
  }
}
