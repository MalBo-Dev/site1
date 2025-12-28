<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            
            // ارتباط با جدول users و trains
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('train_id')->constrained('trains')->onDelete('cascade');
            
            $table->string('cargo_description')->comment('شرح کالا');
            $table->decimal('weight', 10, 2)->comment('وزن بار');
            $table->integer('wagon_count')->comment('تعداد واگن رزرو شده');
            $table->decimal('cost', 15, 0)->comment('هزینه کل');
            
            // وضعیت رزرو
            $table->enum('status', ['tentative', 'confirmed'])
                  ->default('tentative')
                  ->comment('وضعیت: tentative=رزرو اولیه, confirmed=قطعی');
            
            $table->boolean('is_paid')->default(false)->comment('وضعیت پرداخت');
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
        Schema::dropIfExists('bookings');
    }
}