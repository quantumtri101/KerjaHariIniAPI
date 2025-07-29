<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Http\Controllers\Helper\MigrationHelper;

class CreateUserTable extends MigrationHelper
{
  /**
  * Run the migrations.
  *
  * @return void
  */
  public function up()
  {
    $context = $this;
    Schema::create('user', function (Blueprint $table) use($context) {
      $context->base_column($table);
      $table->string('type_id',100)->nullable();
      $table->string('city_id',100)->nullable();
      $table->string('name',100)->nullable();
      $table->string('email',100);
      $table->text('password');
      $table->string('phone',100)->nullable();
      $table->boolean('gender')->default(0);
      $table->date('birth_date')->nullable();
      $table->string('file_name',100)->nullable();
      $table->double('salary_balance', 20, 2)->default(0);
      $table->text('pin_code')->nullable();
      $table->string('otp_code',10)->nullable();
      $table->dateTime('phone_verified_at')->nullable();
      $table->dateTime('verified_at')->nullable();
      $table->string('id_no',100)->nullable();
      $table->string('id_file_name',100)->nullable();
      $table->string('selfie_file_name',100)->nullable();
      $table->boolean('is_active')->default(1);

      $table->foreign('type_id')->references('id')->on('type')->onDelete('cascade');
      $table->foreign('city_id')->references('id')->on('city')->onDelete('cascade');
    });
  }

  /**
  * Reverse the migrations.
  *
  * @return void
  */
  public function down()
  {
    Schema::dropIfExists('user');
  }
}
