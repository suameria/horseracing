<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRefundsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 払戻金
        Schema::create('refunds', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('schedule_id')->unsigned()->index()->comment('スケジュールID');

            $table->string('order_of_finish')->nullable()->comment('着順');
            $table->integer('price')->nullable()->comment('払戻金');
            $table->integer('favorite')->nullable()->comment('人気');
            $table->integer('type')->nullable()->comment('払戻金タイプ 1:単勝 2:複勝 3:枠連 4:馬連 5:ワイド 6:馬単 7:3連複 8:3連単');

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
        Schema::dropIfExists('refunds');
    }
}
