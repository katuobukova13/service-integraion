<?php

namespace App\Console\Commands;

use App\Services\Sync\SyncUpdater;
use Illuminate\Console\Command;

class UpdateAdveduplatUsersFromAmocrmContacts extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'update_adveduplat_users_from_amocrm_contacts';

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
    (new SyncUpdater('adveduplat_users_amocrm_contacts', SyncDirection::TailToHead))
      ->setFieldMatch('name', 'full_name')
      ->getIdsToUpdate(function () {
      })
      ->run();

    return Command::SUCCESS;
  }
}
