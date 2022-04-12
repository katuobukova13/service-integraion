<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGetcourseUsersTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('getcourse_users', function (Blueprint $table) {
      $table->id();
      $table->integer('id_getcourse');
      $table->string('name');
      $table->string('phone');
      $table->string('email')->unique();
      $table->string('city');
      $table->string('country');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('getcourse_users');
  }
}
