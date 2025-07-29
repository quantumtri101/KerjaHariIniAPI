<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Http\Controllers\Helper\MigrationHelper;

class CreateSubCategoryTable extends MigrationHelper
{
  /**
  * Run the migrations.
  *
  * @return void
  */
  public function up()
  {
    $context = $this;
    Schema::create('sub_category', function (Blueprint $table) use($context) {
      $context->base_column($table);
      $table->string('category_id',100)->nullable();
      $table->string('name',100);

      $table->foreign('category_id')->references('id')->on('category')->onDelete('cascade');
    });
  }

  /**
  * Reverse the migrations.
  *
  * @return void
  */
  public function down()
  {
    Schema::dropIfExists('sub_category');
  }
}
