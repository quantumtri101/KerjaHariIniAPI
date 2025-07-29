<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Http\Controllers\Helper\MigrationHelper;

class CreateGeneralQuizResultTable extends MigrationHelper
{
  /**
  * Run the migrations.
  *
  * @return void
  */
  public function up()
  {
    $context = $this;
    Schema::create('general_quiz_result', function (Blueprint $table) use($context) {
      $context->base_column($table);
      $table->string('user_id',100)->nullable();
      $table->double('score', 20, 2)->default(0);
      $table->double('time_completed', 20, 2)->default(0);

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
    Schema::dropIfExists('general_quiz_result');
  }
}
