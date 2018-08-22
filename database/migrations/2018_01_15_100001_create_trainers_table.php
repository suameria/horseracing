<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrainersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 調教師
        Schema::create('trainers', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name')->nullable()->comment('調教師名');
            $table->string('name_kana')->nullable()->comment('調教師名カナ');
            $table->string('training_center')->nullable()->comment('トレーニングセンター');
            $table->string('hometown')->nullable()->comment('出身地');
            $table->date('birthday')->nullable()->comment('生年月日');

            $table->string('trainer_key')->unique()->nullable()->comment('調教師キー');

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
        Schema::dropIfExists('trainers');
    }
}
