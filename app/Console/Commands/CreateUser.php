<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;

class CreateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "create:user
    {name : user's name}
    {email : user's email}
    {password : user's password}
    ";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create user for authorization at Integration Service';

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
     * return array
     */
    public function handle(UserService $userService): int
    {

      $user = $userService->create(new User(), [
        'name' => $this->argument('name'),
        'email' => $this->argument('email'),
        'password' => $this->argument('password'),
      ]);

      dump($user->toArray());

      return Command::SUCCESS;
    }
}
