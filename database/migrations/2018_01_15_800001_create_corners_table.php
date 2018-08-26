<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCornersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('corners', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('schedule_id')->unsigned()->index()->comment('スケジュールID');

            $table->string('corner_1')->nullable()->comment('1角');
            $table->string('corner_2')->nullable()->comment('2角');
            $table->string('corner_3')->nullable()->comment('3角');
            $table->string('corner_4')->nullable()->comment('4角');

            $table->string('race_key')->nullable()->index()->comment('レースキー');

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
        Schema::dropIfExists('corners');
    }
}
