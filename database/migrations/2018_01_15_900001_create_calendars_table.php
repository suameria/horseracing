<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCalendarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calendars', function (Blueprint $table) {
            $table->increments('id');

            $table->date('date')->nullable()->comment('レース日付');
            $table->tinyInteger('grade')->default(0)->comment('グレード 0:重賞以外 1:G1 2:G2 3:G3');
            $table->string('title')->nullable()->comment('メインレースタイトル');

            $table->string('list_key')->unique()->nullable()->comment('レースキー(末尾2桁のレースNo無し)');
            $table->string('race_key')->nullable()->index()->comment('メインレースキー');

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
        Schema::dropIfExists('calendars');
    }
}
