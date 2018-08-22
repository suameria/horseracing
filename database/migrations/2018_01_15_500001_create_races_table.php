<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // レース結果
        Schema::create('races', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('schedule_id')->unsigned()->index()->comment('スケジュールID');

            $table->integer('order_of_finish')->nullable()->comment('着順');
            $table->integer('post_position')->nullable()->comment('枠番');
            $table->integer('horse_number')->nullable()->comment('馬番');

            $table->string('horse_name')->nullable()->comment('馬名');
            $table->string('horse_sex')->nullable()->comment('性別');
            $table->integer('horse_age')->nullable()->comment('年齢');
            $table->integer('horse_weight')->nullable()->comment('馬体重');
            $table->integer('sign')->nullable()->comment('符号');
            $table->integer('change_weight')->nullable()->comment('変化馬体重');
            $table->integer('is_blinker')->nullable()->comment('ブリンカー有無');

            $table->string('time')->nullable()->comment('タイム');
            $table->string('margin_disp')->nullable()->comment('着差');

            $table->string('passing')->nullable()->comment('通過順位');
            $table->decimal('three_furlong')->nullable()->comment('上3Fタイム');

            $table->string('jockey_name')->nullable()->comment('騎手名');
            $table->decimal('weight')->nullable()->comment('斤量');

            $table->integer('favorite')->nullable()->comment('人気');
            $table->decimal('odds')->nullable()->comment('オッズ');

            $table->string('trainer_name')->nullable()->comment('調教師名');

            $table->tinyInteger('status')->default(0)->comment('ステータス 0:特別登録 1:出走予定 2:出走確定');

            $table->string('race_key')->nullable()->index()->comment('レースキー');
            $table->string('horse_key')->nullable()->index()->comment('競走馬キー');
            $table->string('jockey_key')->nullable()->index()->comment('騎手キー');
            $table->string('trainer_key')->nullable()->index()->comment('調教師キー');

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
        Schema::dropIfExists('races');
    }
}
