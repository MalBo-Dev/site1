<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\ReturnedGoodImage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;

class ReturnedGoodImageController extends Controller
{
    /**
     * متد کمکی برای بررسی دسترسی (ACL) با پیام فارسی
     */
    private function authorizeAdmin($userId)
    {
        $user = User::findOrFail($userId);

        // بررسی می‌کنیم: اگر کاربر دسترسی 'admin-access' را نداشت (False بود)
        if (! Gate::forUser($user)->allows('admin-access')) {
            // یک خطای 403 با پیام فارسی پرتاب کن
            abort(403, 'شما دسترسی مجاز (Admin) برای انجام این عملیات را ندارید.');
        }
    }

    // 1. ثبت تصویر
    public function store(Request $request, Booking $booking)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'description' => 'nullable|string',
        ]);

        $this->authorizeAdmin($request->user_id); // اینجا چک میشه

        $path = $request->file('image')->store('returned_goods', 'public');

        $image = $booking->images()->create([
            'image_path' => $path,
            'description' => $request->description
        ]);

        return response()->json([
            'message' => 'تصویر با موفقیت ثبت شد.',
            'data' => $image
        ], 201);
    }

    // 2. ویرایش تصویر
    public function update(Request $request, ReturnedGoodImage $image)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'description' => 'nullable|string',
        ]);

        $this->authorizeAdmin($request->user_id); // اینجا چک میشه

        if ($request->hasFile('image')) {
            if (Storage::disk('public')->exists($image->image_path)) {
                Storage::disk('public')->delete($image->image_path);
            }
            $path = $request->file('image')->store('returned_goods', 'public');
            $image->update(['image_path' => $path]);
        }

        if ($request->has('description')) {
            $image->update(['description' => $request->description]);
        }

        return response()->json(['message' => 'ویرایش با موفقیت انجام شد.', 'data' => $image]);
    }

    // 3. حذف تصویر
    public function destroy(Request $request, ReturnedGoodImage $image)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $this->authorizeAdmin($request->user_id); // اینجا چک میشه

        if (Storage::disk('public')->exists($image->image_path)) {
            Storage::disk('public')->delete($image->image_path);
        }
        
        $image->delete();

        return response()->json(['message' => 'تصویر با موفقیت حذف شد.']);
    }
}