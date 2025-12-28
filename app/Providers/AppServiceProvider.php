<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        // تعریف مجوز دسترسی ادمین با استفاده از سیستم جدید نقش‌ها
        Gate::define('admin-access', function (User $user) {
            // این متد hasRole را در مدل User ساختیم
            return $user->hasRole('admin');
        });
    }
}
