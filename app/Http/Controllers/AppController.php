<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Client;
use App\Models\Driver;

class AppController extends Controller
{
    public function sendVerificationCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone_number' => 'required|string',
            'user_type' => 'required|in:client,driver',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $verificationCode = "123456";

        return response()->json([
            'status' => true,
            'code' => $verificationCode,
            'user_type' => $request->user_type
        ]);
    }

    public function verifyCode(Request $request)
{
    $validator = Validator::make($request->all(), [
        'phone_number' => 'required|string',
        'code' => 'required|string|size:6',
        'user_type' => 'required|in:client,driver',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => false,
            'message' => 'Validation failed',
            'errors' => $validator->errors()
        ], 422);
    }

    if ($request->code !== "123456") {
        return response()->json([
            'status' => false,
            'message' => 'رمز التحقق غير صحيح',
        ]);
    }

    if ($request->user_type === 'client') {
        $user = Client::firstOrCreate(
            ['phone' => $request->phone_number],
            ['typeuser' => 'client']
        );
    } else {
        $user = Driver::firstOrCreate(
            ['phone' => $request->phone_number],
            ['typeuser' => 'driver', 'face_id' => null]
        );
    }

    // ✅ إنشاء التوكن هنا
    $token = $user->createToken('mobile')->plainTextToken;

    return response()->json([
        'status' => true,
        'message' => 'تم التحقق والتسجيل بنجاح',
        'token' => $token, // رجّع التوكن
        'client_id' => $user->id,
        'user' => $user,
    ]);
}
}
