<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Http\Controllers\Helper\MigrationHelper;

class CreateInfoTable extends MigrationHelper
{
  /**
  * Run the migrations.
  *
  * @return void
  */
  public function up()
  {
    $context = $this;
    Schema::create('info', function (Blueprint $table) use($context) {
      $context->base_column($table);
      $table->string('title',100);
      $table->longText('content')->nullable();
      $table->enum('type', ['term_condition', 'privacy_policy'])->default('term_condition');
    });
  }

  /**
  * Reverse the migrations.
  *
  * @return void
  */
  public function down()
  {
    Schema::dropIfExists('info');
  }
}
