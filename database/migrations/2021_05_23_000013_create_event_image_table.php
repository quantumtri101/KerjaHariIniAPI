<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Http\Controllers\Helper\MigrationHelper;

class CreateEventImageTable extends MigrationHelper
{
  /**
  * Run the migrations.
  *
  * @return void
  */
  public function up()
  {
    $context = $this;
    Schema::create('event_image', function (Blueprint $table) use($context) {
      $context->base_column($table);
      $table->string('event_id',100)->nullable();
      $table->string('file_name', 100)->nullable();
      $table->boolean('is_publish')->default(0);

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
    Schema::dropIfExists('event_image');
  }
}
