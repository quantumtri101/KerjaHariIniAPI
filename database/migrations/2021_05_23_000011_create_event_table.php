<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Http\Controllers\Helper\MigrationHelper;

class CreateEventTable extends MigrationHelper
{
  /**
  * Run the migrations.
  *
  * @return void
  */
  public function up()
  {
    $context = $this;
    Schema::create('event', function (Blueprint $table) use($context) {
      $context->base_column($table);
      $table->string('company_id',100)->nullable();
      $table->string('name', 100);
      $table->dateTime('start_date')->nullable();
      $table->dateTime('end_date')->nullable();

      $table->foreign('company_id')->references('id')->on('company')->onDelete('cascade');
    });
  }

  /**
  * Reverse the migrations.
  *
  * @return void
  */
  public function down()
  {
    Schema::dropIfExists('event');
  }
}
