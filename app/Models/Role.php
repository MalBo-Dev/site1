<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;
    
    // چون معمولا جداول ACL تایم‌استمپ نیاز ندارند (اختیاری)
    public $timestamps = false;

    protected $fillable = ['name', 'label'];

    /**
     * رابطه معکوس با کاربر (هر نقش متعلق به چندین کاربر است)
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
    
    /**
     * رابطه با دسترسی‌ها (هر نقش چندین دسترسی دارد)
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }
}