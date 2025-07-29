<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Http\Controllers\Helper\MigrationHelper;

class CreateResumeTable extends MigrationHelper
{
  /**
  * Run the migrations.
  *
  * @return void
  */
  public function up()
  {
    $context = $this;
    Schema::create('resume', function (Blueprint $table) use($context) {
      $context->base_column($table);
      $table->string('city_id',100)->nullable();
      $table->string('bank_id',100)->nullable();
      $table->string('user_id',100)->nullable();
      $table->string('name', 100);
      $table->string('phone', 100);
      $table->date('birth_date');
      $table->longText('address')->nullable();
      $table->enum('marital_status', ["unmarried", "married"]);
      $table->double('height', 20, 2)->default(0);
      $table->double('weight', 20, 2)->default(0);
      $table->string('acc_no', 100);
      $table->string('id_file_name', 100)->nullable();
      $table->string('selfie_file_name', 100)->nullable();
      $table->boolean('is_publish')->default(0);

      $table->foreign('city_id')->references('id')->on('city')->onDelete('cascade');
      $table->foreign('bank_id')->references('id')->on('bank')->onDelete('cascade');
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
    Schema::dropIfExists('resume');
  }
}
