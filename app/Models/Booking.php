<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'train_id',
        'cargo_description',
        'weight',
        'wagon_count',
        'cost',
        'status',   // tentative, confirmed
        'is_paid',  // true, false
    ];

    protected $casts = [
        'is_paid' => 'boolean',
        'cost' => 'decimal:0',
        'weight' => 'decimal:2',
    ];

    /**
     * رابطه: هر رزرو متعلق به یک کاربر است
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * رابطه: هر رزرو مربوط به یک قطار است
     */
    public function train()
    {
        return $this->belongsTo(Train::class);
    }

    /**
     * رابطه: هر رزرو ممکن است چندین تصویر کالای مرجوعی داشته باشد
     */
    public function images()
    {
        return $this->hasMany(ReturnedGoodImage::class);
    }
}