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
        if (!Schema::hasTable('user')) {
            Schema::create('user', function (Blueprint $table) {
                $table->charset = 'utf8mb4';
                $table->collation = 'utf8mb4_unicode_ci';
                
                $table->bigInteger('id')->autoIncrement()->unsigned();
                $table->string('first_name', 48);
                $table->string('second_name', 48);
                $table->string('last_name', 48);
                $table->string('username', 48);
                $table->string('email');
                $table->string('phone');
                $table->string('profile_url');
                $table->string('password');
                $table->boolean('role')->index()->default(1)->comment('0=role 1, 1=role 2');
                $table->string('remember_token');
                $table->boolean('status')->index()->default(1)->comment('0=status 1, 1=status 2');
                $table->dateTime('created_at');
                $table->dateTime('updated_at');
                $table->dateTime('deleted_at')->nullable()->default(null);
            });
        }
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
