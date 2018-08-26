<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLapsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('laps', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('schedule_id')->unsigned()->index()->comment('スケジュールID');

            $table->string('time')->nullable()->comment('ラップタイム');

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
        Schema::dropIfExists('laps');
    }
}
