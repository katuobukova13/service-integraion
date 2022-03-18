<?php

namespace App\Console\Commands;

use App\Http\Controllers\AmocrmOrderController;
use App\Http\Requests\ContactRequest;
use App\Http\Requests\OrderRequest;
use App\Modules\Integration\Domain\Adveduplat\User\UserModel;
use App\Modules\Integration\Domain\Amocrm\Lead\LeadModel as AmoLead;
use App\Modules\Integration\Domain\Amocrm\Contact\ContactModel as AmoContact;
use App\Modules\Integration\Domain\Amocrm\Lead\LeadResource;
use App\Modules\Integration\Domain\Amocrm\Link\LinkModel as LinkModel;
use App\Services\Integration\AmocrmContactService;
use App\Services\Integration\AmocrmOrderService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;

class Test extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'test:fetch';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = '';

  /**
   * Create a new command instance.
   *
   * @return void
   */
  public function __construct()
  {
    parent::__construct();
  }

  /**
   * @throws \Exception
   */
  public function handle()
  {
//    $a = App::make(UserModel::class);
//    $a->create([
//        'email' => 'fk@mail.ru',
//        'password' => 'jhg59ddldl',
//      ]
//    );

//    $accountName = 'get.advance-club.ru';
//    $secretKey = 'fMdhNYSz4qv8slRVXVM3PgzOR8yth8oXRLC8Oq8nxy6QtlW55dVbYL8TTne9kj797ZA4u5AwB6egIl26llBUvModQ9esRP7mDFr5pPz3QCnR7BjcttWA4xFW8c4vPyd0';
//
//    $user = [];
//    $user['user']['email'] = 'xxxxx@xxxxx.xxx';
//    $user['user']['phone'] = '+74951234567';
//    $user['user']['first_name'] = 'Василий';
//    $user['user']['last_name'] = 'Пупкин';
//    $user['user']['city'] = 'Москва';
//    $user['user']['country'] = 'Россия';
//    $user['user']['group_name'] = ['Группа1', 'Группа2'];
//    $user['system']['refresh_if_exists'] = 1;
//
//    $json = json_encode($user);
//    $base64 = base64_encode($json);
//
//    if ($curl = curl_init()) {
//      curl_setopt($curl, CURLOPT_URL, 'https://' . $accountName . '/pl/api/users');
//      curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
//      curl_setopt($curl, CURLOPT_POST, true);
//      curl_setopt($curl, CURLOPT_POSTFIELDS, 'action=add&key=' . $secretKey . '&params=' . $base64);
//      $out = curl_exec($curl);
//      echo $out;
//      curl_close($curl);
//    } else {
//      echo 'Failed initialization';
//    }

$a = ContactRequest::create('/api/amocrm/contacts', 'GET',
  App::make(AmocrmContactService::class)->list(filter: ['id' => [20542605, 20542967]]));
dd($a->query);
    //  $amoLead = AmoContact::find(19579409);
    // $amoLead = AmoLead::list(filter: ['price'=>1], limit: 3);
    // $amoLead = AmoLead::create(["name" => "it6", "cf_pay_date" => "30.03.2022"]);
    // $amo = AmoLead::find($amoLead->attributes['id']);
    // $amoContact = AmoContact::create(["first_name" => "it6"]);
    //  $amoContacts = AmoContact::list(filter: ['id' => [19579409, 19129741]]);

    //   $amoLead = AmoLead::list(filter:['id'=>[14803475, 14766549, 14766465]]);
    //  $amoLead = AmoLead::list(limit: 100);
    //    $amoLead = AmoLead::find(14766549);


    //  $link = LinkModel::link($amoContact, $amoLead);

    //   dd($link);

    return Command::SUCCESS;
  }
}
