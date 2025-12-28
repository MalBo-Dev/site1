<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TrainController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ReturnedGoodImageController;

// تمام روت‌ها عمومی هستند (بدون احراز هویت)
Route::prefix('v1')->group(function () {
    
    // ۱. مدیریت قطارها (ثبت زمان و مشاهده)
    Route::apiResource('trains', TrainController::class);

    // ۲. رزرو و پرداخت
    Route::get('bookings', [BookingController::class, 'index']); // لیست رزروها
    Route::get('bookings/{booking}', [BookingController::class, 'show']); // مشاهده تکی
    Route::post('bookings', [BookingController::class, 'store']); // ثبت رزرو (با ارسال user_id)
    Route::post('bookings/{booking}/pay', [BookingController::class, 'pay']); // پرداخت

    // ۳. تصاویر کالای مرجوعی (آپلود، ویرایش آن‌سیف، حذف)
    Route::post('bookings/{booking}/images', [ReturnedGoodImageController::class, 'store']);
    Route::put('images/{image}', [ReturnedGoodImageController::class, 'update']);
    Route::delete('images/{image}', [ReturnedGoodImageController::class, 'destroy']);
});