<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Http\Controllers\Helper\MigrationHelper;

class CreateBannerTable extends MigrationHelper
{
  /**
  * Run the migrations.
  *
  * @return void
  */
  public function up()
  {
    $context = $this;
    Schema::create('banner', function (Blueprint $table) use($context) {
      $context->base_column($table);
      $table->string('file_name',100)->nullable();
      $table->boolean('is_publish')->default(0);
      $table->boolean('is_primary')->default(0);
    });
  }

  /**
  * Reverse the migrations.
  *
  * @return void
  */
  public function down()
  {
    Schema::dropIfExists('banner');
  }
}
