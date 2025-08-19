<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateChocolateyShopHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chocolatey_shop_history', function (Blueprint $table) {
            $table->integer('id');
            $table->integer('user_id');
            $table->integer('item_id');
            $table->integer('payment_method');
            $table->boolean('approved')->default(false);
            $table->string('approved_by', 125)->default('System');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->primary('id', 'chocolatey_shop_history_primary');
        });

        DB::update('ALTER TABLE chocolatey_shop_history MODIFY COLUMN id INT AUTO_INCREMENT');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chocolatey_shop_history');
    }
}
