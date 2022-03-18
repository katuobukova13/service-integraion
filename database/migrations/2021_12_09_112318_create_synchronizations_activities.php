<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSynchronizationsActivities extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('synchronizations_activities', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('sync_id');
      $table->enum('status', ['processing', 'success', 'error', 'unknown'])->nullable(true);
      $table->dateTime('started_at')->nullable(true);
      $table->dateTime('finished_at')->nullable(true);

      $table->foreign('sync_id')->references('id')->on('synchronizations');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('synchronizations_activities');
  }
}
