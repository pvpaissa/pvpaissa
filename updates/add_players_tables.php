<?php

namespace Cleanse\PvPaissa\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class AddPlayersTables extends Migration
{
    public function up()
    {
        Schema::create('cleanse_pvpaissa_players', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('user_id')->unsigned()->index()->nullable();
            $table->string('character')->index();
            $table->string('name');
            $table->string('data_center');
            $table->string('server');
            $table->string('avatar')->nullable();
            $table->integer('pvp_rank')->nullable();
            $table->string('grand_company')->nullable();
            $table->string('verification_code')->nullable()->index();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::drop('cleanse_pvpaissa_players');
    }
}
