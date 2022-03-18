<?php

namespace App\Console\Commands;

use App\Services\Sync\SyncCreator;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class CreateAdveduplatUsersFromAmocrmContacts extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'create_adveduplat_users_from_amocrm_contacts';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Command description';

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
   * Execute the console command.
   *
   * @return int
   */
  public function handle()
  {
    (new SyncCreator('adveduplat_users_amocrm_contacts', SyncDirection::TailToHead))
      ->setFieldMatch('name', 'full_name')
      ->setFieldMatch('email', 'email', function ($value) {
        return Str::lower($value);
      })
      ->setOnDuplicateAttributes(['email'])
      ->run();

    return Command::SUCCESS;
  }
}
