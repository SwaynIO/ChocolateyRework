<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateChocolateyUsersSecurityTrustedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chocolatey_users_security_trusted', function (Blueprint $table) {
            $table->integer('id');
            $table->integer('user_id');
            $table->string('ip_address', 255);
            $table->primary('id', 'chocolatey_users_security_trusted_primary');
        });

        DB::update('ALTER TABLE chocolatey_users_security_trusted MODIFY COLUMN id INT AUTO_INCREMENT');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chocolatey_users_security_trusted');
    }
}
