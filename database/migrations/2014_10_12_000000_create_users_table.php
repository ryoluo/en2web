<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('password')->nullable();
            $table->string('email_verify_token')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->string('identification_code')->unique()->nullable();
            $table->boolean('isAdmin')->default(false);

            $table->unsignedInteger('generation')->nullable();
            $table->year('year')->nullable();
            $table->string('department')->nullable();
            $table->string('major')->nullable();
            $table->string('university')->nullable();
            $table->boolean('isOB')->default(false);
            $table->string('job')->nullable();
            $table->text('profile')->nullable();
            $table->softDeletes();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
