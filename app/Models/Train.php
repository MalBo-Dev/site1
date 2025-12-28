<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Train extends Model
{
    use HasFactory;

    protected $fillable = [
        'train_number',
        'origin',
        'destination',
        'departure_time',
        'arrival_time',
        'total_wagons',
    ];

    /**
     * تبدیل زمان‌ها به نمونه کربن (Carbon) برای کار آسان با تاریخ
     */
    protected $casts = [
        'departure_time' => 'datetime',
        'arrival_time' => 'datetime',
    ];

    /**
     * رابطه: هر قطار شامل چندین رزرو (بار) است
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}