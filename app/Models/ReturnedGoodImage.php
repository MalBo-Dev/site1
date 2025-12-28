<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnedGoodImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'image_path',
        'description',
    ];

    /**
     * رابطه: هر تصویر متعلق به یک رزرو است
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}