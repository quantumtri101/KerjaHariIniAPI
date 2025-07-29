<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Http\Controllers\Helper\MigrationHelper;

class CreateNotificationTable extends MigrationHelper
{
  /**
  * Run the migrations.
  *
  * @return void
  */
  public function up()
  {
    $context = $this;
    Schema::create('notification', function (Blueprint $table) use($context) {
      $context->base_column($table);
      $table->string('user_id',100)->nullable();
      $table->string('title',100);
      $table->longText('body')->nullable();
      $table->longText('data')->nullable();
      $table->dateTime('read_at')->nullable();

      $table->foreign('user_id')->references('id')->on('user')->onDelete('cascade');
    });
  }

  /**
  * Reverse the migrations.
  *
  * @return void
  */
  public function down()
  {
    Schema::dropIfExists('notification');
  }
}
