<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Train;
use Illuminate\Http\Request;

class TrainController extends Controller
{
    // لیست قطارها (برای اینکه ببینیم چه قطارهایی هست)
    public function index()
    {
        return response()->json([
            'data' => Train::latest()->get()
        ]);
    }

    // ثبت قطار جدید (توسط ادمین فرضی)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'train_number' => 'required|string|unique:trains,train_number',
            'origin' => 'required|string',
            'destination' => 'required|string',
            'departure_time' => 'required|date',
            'arrival_time' => 'required|date|after:departure_time',
            'total_wagons' => 'required|integer|min:1',
        ]);

        $train = Train::create($validated);

        return response()->json([
            'message' => 'قطار با موفقیت در سیستم ثبت شد.',
            'data' => $train
        ], 201);
    }
    
    // نمایش تکی قطار
    public function show(Train $train)
    {
        return response()->json(['data' => $train]);
    }
}