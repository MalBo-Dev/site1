<?php

namespace App\Models;

// ایمپورت‌های ضروری
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * فیلدهای قابل پر شدن
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'wallet_balance', // موجودی کیف پول
    ];

    /**
     * فیلدهای مخفی در خروجی JSON
     */
    protected $hidden = [
        'password',
        'remember_token',
        'pivot', // مخفی کردن اطلاعات جدول واسط در خروجی
    ];

    /**
     * تبدیل نوع داده‌ها
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'wallet_balance' => 'decimal:0',
    ];

    /*
    |--------------------------------------------------------------------------
    | روابط (Relationships)
    |--------------------------------------------------------------------------
    */

    /**
     * رابطه با رزروها (هر کاربر چندین رزرو دارد)
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * رابطه با نقش‌ها (هر کاربر چندین نقش دارد) - بخش ACL
     * این رابطه به جدول واسط role_user اشاره می‌کند
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    /*
    |--------------------------------------------------------------------------
    | متدهای کمکی (Helper Methods)
    |--------------------------------------------------------------------------
    */

    /**
     * بررسی اینکه آیا کاربر نقش خاصی دارد یا خیر
     * مثال استفاده: $user->hasRole('admin')
     * * @param string $roleName
     * @return bool
     */
    public function hasRole($roleName)
    {
        // چک می‌کند در بین نقش‌های کاربر، نقشی با این نام وجود دارد یا خیر
        return $this->roles()->where('name', $roleName)->exists();
    }
}