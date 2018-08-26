<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // スケジュール
        Schema::create('schedules', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('calendar_id')->unsigned()->index()->comment('カレンダーID');

            $table->integer('race')->nullable()->comment('何レース目');

            $table->dateTime('date')->nullable()->comment('レース日時');

            $table->string('title')->nullable()->comment('レースタイトル');

            $table->string('detail_1')->nullable()->comment('詳細1:例）芝・右・外 1600m');
            $table->string('detail_2')->nullable()->comment('詳細2:例）晴');
            $table->string('detail_3')->nullable()->comment('詳細3:例）良');
            $table->string('detail_4')->nullable()->comment('詳細4:例）サラ系3歳');
            $table->string('detail_5')->nullable()->comment('詳細5:例）オープン （混合）（特指） 別定"');
            $table->string('detail_6')->nullable()->comment('詳細6:例）本賞金：1900、760、480、290、190万');

            $table->tinyInteger('status')->default(0)->comment('ステータス 0:レース結果前 1:レース結果後');

            $table->string('race_key')->unique()->nullable()->index()->comment('レースキー');

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
        Schema::dropIfExists('schedules');
    }
}
