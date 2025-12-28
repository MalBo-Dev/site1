<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    // نمایش همه رزروها
    public function index()
    {
        return response()->json([
            'data' => Booking::with(['train', 'images'])->latest()->get()
        ]);
    }

    // نمایش یک رزرو خاص
    public function show(Booking $booking)
    {
        return response()->json([
            'data' => $booking->load(['train', 'images'])
        ]);
    }

    // ثبت رزرو (دریافت user_id دستی)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id', // <-- نکته مهم: شناسه کاربر از اینجا می‌آید
            'train_id' => 'required|exists:trains,id',
            'wagon_count' => 'required|integer|min:1',
            'weight' => 'required|numeric|min:0.1',
            'cargo_description' => 'required|string|max:255',
        ]);

        // قیمت‌گذاری: هر واگن ۱ میلیون تومان
        $cost = $validated['wagon_count'] * 1000000;

        $booking = Booking::create([
            'user_id' => $validated['user_id'],
            'train_id' => $validated['train_id'],
            'wagon_count' => $validated['wagon_count'],
            'weight' => $validated['weight'],
            'cargo_description' => $validated['cargo_description'],
            'cost' => $cost,
            'status' => 'tentative',
            'is_paid' => false,
        ]);

        return response()->json([
            'message' => 'رزرو اولیه انجام شد. شناسه رزرو: ' . $booking->id,
            'data' => $booking
        ], 201);
    }

    // پرداخت (پیدا کردن کاربر از روی رزرو و کسر پول)
    public function pay(Booking $booking)
    {
        if ($booking->is_paid) {
            return response()->json(['message' => 'این سفارش قبلاً پرداخت شده است.'], 400);
        }

        // پیدا کردن کاربری که این رزرو را انجام داده
        $user = User::findOrFail($booking->user_id);

        return DB::transaction(function () use ($user, $booking) {
            // قفل کردن رکورد کاربر برای جلوگیری از تداخل
            $userLock = User::where('id', $user->id)->lockForUpdate()->first();

            // بررسی موجودی
            if ($userLock->wallet_balance < $booking->cost) {
                return response()->json([
                    'message' => 'موجودی کیف پول کافی نیست.',
                    'current_balance' => $userLock->wallet_balance,
                    'required' => $booking->cost
                ], 402);
            }

            // کسر پول
            $userLock->decrement('wallet_balance', $booking->cost);
            
            // تایید رزرو
            $booking->update([
                'is_paid' => true,
                'status' => 'confirmed'
            ]);

            return response()->json([
                'message' => 'پرداخت موفق بود و رزرو قطعی شد.',
                'new_balance' => $userLock->wallet_balance
            ]);
        });
    }
}