<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    /**
     * غیرفعال کردن تایم‌استمپ‌ها
     */
    public $timestamps = false;

    protected $fillable = ['name', 'label'];

    /**
     * رابطه با نقش‌ها:
     * هر دسترسی ممکن است در چندین نقش استفاده شود.
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
}