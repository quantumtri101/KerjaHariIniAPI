<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Http\Controllers\Helper\MigrationHelper;

class CreateChatTable extends MigrationHelper
{
  /**
  * Run the migrations.
  *
  * @return void
  */
  public function up()
  {
    $context = $this;
    Schema::create('chat', function (Blueprint $table) use($context) {
      $context->base_column($table);
      $table->string('person_1_id',100)->nullable();
      $table->string('person_2_id',100)->nullable();

      $table->foreign('person_1_id')->references('id')->on('user')->onDelete('cascade');
      $table->foreign('person_2_id')->references('id')->on('user')->onDelete('cascade');
    });
  }

  /**
  * Reverse the migrations.
  *
  * @return void
  */
  public function down()
  {
    Schema::dropIfExists('chat');
  }
}
