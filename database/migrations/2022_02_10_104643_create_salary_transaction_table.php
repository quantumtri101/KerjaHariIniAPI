<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Http\Controllers\Helper\MigrationHelper;

class CreateSalaryTransactionTable extends MigrationHelper
{
  /**
  * Run the migrations.
  *
  * @return void
  */
  public function up()
  {
    $context = $this;
    Schema::create('salary_transaction', function (Blueprint $table) use($context) {
      $context->base_column($table);
      $table->string('user_id',100)->nullable();
      $table->enum('type', ['in', 'out'])->default('in');
      $table->double('amount', 20, 2)->default(0);
      $table->double('fee', 20, 2)->default(0);
      $table->double('total_amount', 20, 2)->default(0);
      $table->longText('description', 100)->nullable();
      $table->boolean('is_approve')->default(0);
      $table->dateTime('date')->nullable();

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
    Schema::dropIfExists('salary_transaction');
  }
}
