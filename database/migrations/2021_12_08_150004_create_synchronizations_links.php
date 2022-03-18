<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSynchronizationsLinks extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('synchronizations_links', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('sync_id');
      $table->integer('head_service_entity_id');
      $table->integer('tail_service_entity_id');

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
    Schema::dropIfExists('synchronizations_links');
  }
}
