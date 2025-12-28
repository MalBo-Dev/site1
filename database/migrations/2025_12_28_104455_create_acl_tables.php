<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAclTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 1. جدول نقش‌ها (Roles)
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->comment('نام نقش مثل admin');
            $table->string('label', 100)->nullable()->comment('نام نمایشی');
        });

        // 2. جدول دسترسی‌ها (Permissions)
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->comment('نام دسترسی');
            $table->string('label', 100)->nullable()->comment('توضیحات');
        });

        // 3. جدول واسط کاربر و نقش (Role User)
        Schema::create('role_user', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('role_id')->constrained('roles')->onDelete('cascade');
            
            // کلید اصلی ترکیبی
            $table->primary(['user_id', 'role_id']);
        });

        // 4. جدول واسط نقش و دسترسی (Permission Role)
        Schema::create('permission_role', function (Blueprint $table) {
            $table->foreignId('permission_id')->constrained('permissions')->onDelete('cascade');
            $table->foreignId('role_id')->constrained('roles')->onDelete('cascade');
            
            // کلید اصلی ترکیبی
            $table->primary(['permission_id', 'role_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('permission_role');
        Schema::dropIfExists('role_user');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('roles');
    }
}