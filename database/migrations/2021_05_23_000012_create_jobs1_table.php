<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Http\Controllers\Helper\MigrationHelper;

class CreateJobs1Table extends MigrationHelper
{
  /**
  * Run the migrations.
  *
  * @return void
  */
  public function up()
  {
    $context = $this;
    Schema::create('jobs1', function (Blueprint $table) use($context) {
      $context->base_column($table);
      $table->string('company_id',100)->nullable();
      $table->string('event_id',100)->nullable();
      $table->string('category_id',100)->nullable();
      $table->enum('type', ['regular', 'one-time'])->default('one-time');
      $table->string('name', 100);
      $table->double('salary', 20, 2)->default(0);
      $table->dateTime('start_date')->nullable();
      $table->dateTime('end_date')->nullable();
      $table->longText('description')->nullable();
      $table->boolean('is_urgent')->default(0);

      $table->foreign('category_id')->references('id')->on('category')->onDelete('cascade');
      $table->foreign('company_id')->references('id')->on('company')->onDelete('cascade');
      $table->foreign('event_id')->references('id')->on('event')->onDelete('cascade');
    });
  }

  /**
  * Reverse the migrations.
  *
  * @return void
  */
  public function down()
  {
    Schema::dropIfExists('jobs1');
  }
}
