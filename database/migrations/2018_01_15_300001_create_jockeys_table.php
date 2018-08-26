<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJockeysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 騎手
        Schema::create('jockeys', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name')->nullable()->comment('騎手名');
            $table->string('name_kana')->nullable()->comment('騎手名カナ');
            $table->string('blood')->nullable()->comment('血液型');
            $table->string('height')->nullable()->comment('身長');
            $table->string('weight')->nullable()->comment('体重');
            $table->string('training_center')->nullable()->comment('トレーニングセンター');
            $table->string('belonging')->nullable()->comment('所属');
            $table->string('hometown')->nullable()->comment('出身地');
            $table->date('birthday')->nullable()->comment('生年月日');

            $table->string('jockey_key')->unique()->nullable()->comment('騎手キー');
            $table->string('trainer_key')->nullable()->comment('調教師キー');

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
        Schema::dropIfExists('jockeys');
    }
}
