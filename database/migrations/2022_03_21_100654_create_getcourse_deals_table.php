<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGetcourseDealsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('getcourse_deals', function (Blueprint $table) {
      $table->id();
      $table->integer('number');
      $table->string('title');
      $table->string('status');
      $table->unsignedBigInteger('user_id');
      $table->float('sum');
      $table->float('paid');
      $table->dateTimeTz('paid_at');
      $table->timestamps();

      $table->foreign('user_id')->references('id')->on('getcourse_users');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('getcourse_deals');
  }
}
