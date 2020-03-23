<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDescriptionsToTranslatorTranslations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('translator_translations', function (Blueprint $table) {
            $table->string('descriptions')->after('item')
                ->index()->nullable()
                ->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('translator_translations', function (Blueprint $table) {
            $table->dropColumn('descriptions');
        });
    }
}
