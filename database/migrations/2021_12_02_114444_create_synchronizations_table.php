<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSynchronizationsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('synchronizations', function (Blueprint $table) {
      $table->id();
      $table->string('key')->unique();
      $table->text('title');
      $table->text('description');
      $table->text('head_service_class');
      $table->text('tail_service_class');
      $table->unsignedBigInteger('entity_id');
      $table->unsignedBigInteger('category_id');
      $table->timestamps();

      //Relations
      $table->foreign('entity_id')->references('id')->on('synchronizations_entities');
      $table->foreign('category_id')->references('id')->on('synchronizations_categories');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('synchronizations');
  }
}
