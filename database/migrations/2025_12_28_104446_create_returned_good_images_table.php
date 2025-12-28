<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReturnedGoodImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('returned_good_images', function (Blueprint $table) {
            $table->id();
            // ارتباط با جدول bookings
            $table->foreignId('booking_id')->constrained('bookings')->onDelete('cascade');
            
            $table->string('image_path')->comment('مسیر ذخیره عکس');
            $table->text('description')->nullable()->comment('توضیحات عکس');
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
        Schema::dropIfExists('returned_good_images');
    }
}