<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHorsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 競走馬
        Schema::create('horses', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name')->nullable()->comment('馬名');
            $table->string('name_detail')->nullable()->comment('馬名(詳細)');
            $table->string('sex')->nullable()->comment('性別');
            $table->string('coat_color')->nullable()->comment('毛色');
            $table->date('birthday')->nullable()->comment('生年月日');
            $table->string('owner')->nullable()->comment('馬主');
            $table->string('breeder')->nullable()->comment('生産者');
            $table->string('hometown')->nullable()->comment('産地');
            $table->string('status')->nullable()->comment('登録種別');

            $table->string('horse_key')->unique()->nullable()->comment('競走馬キー');
            $table->string('f_horse_key')->nullable()->comment('父競走馬キー');
            $table->string('m_horse_key')->nullable()->comment('母競走馬キー');
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
        Schema::dropIfExists('horses');
    }
}
