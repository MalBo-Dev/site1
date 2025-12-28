<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrainsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trains', function (Blueprint $table) {
            $table->id();
            $table->string('train_number', 50)->unique()->comment('شماره قطار');
            $table->string('origin', 100)->comment('مبدا');
            $table->string('destination', 100)->comment('مقصد');
            $table->dateTime('departure_time')->comment('زمان حرکت');
            $table->dateTime('arrival_time')->comment('زمان رسیدن');
            $table->integer('total_wagons')->comment('تعداد کل واگن‌ها');
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
        Schema::dropIfExists('trains');
    }
}