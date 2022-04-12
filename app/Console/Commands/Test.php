<?php

namespace App\Console\Commands;

use App\Modules\Integration\Core\Concerns\ResourceRequestBodyFormat;
use App\Modules\Integration\Core\Concerns\ResourceRequestMethod;
use App\Modules\Integration\Core\Concerns\ResourceRequestOptions;
use App\Modules\Integration\Domain\Amocrm\AmocrmResource;
use App\Modules\Integration\Domain\Amocrm\Contact\ContactResource;
use App\Modules\Integration\Domain\Getcourse\Deal\DealModel;
use App\Modules\Integration\Domain\Getcourse\GetcourseResource;
use App\Modules\Integration\Domain\Getcourse\User\UserModel;
use App\Modules\Integration\Domain\Getcourse\User\UserResource;
use App\Services\Integration\GetcourseUserImportService;
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
//    $data = App::make(UserModel::class)->update(['email' => "teskkik@testik.ru",
//      'first_name' => 'test', 'last_name' => 'test']);

//    $data = GetcourseUserImportService::get(['created_at' => [
//    'from' => '2022-04-03',
//    'to' => '2022-04-08',
//  ]]);

    //  $data = UserModel::list(filter: ['created_at' => ['06.04.2022', '09.04.2022']]);

    // $data = UserModel::find(243456082);


 $data = DealModel::create(['email' => 'teskkik@testik.ru','title' => 'testovii']);
    dd($data);

    return Command::SUCCESS;
  }
}
