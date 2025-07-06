<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\Client;
use App\Models\Driver;
///home/u295987876/domains/sa-fvs.com/public_html/gh/app/Http/Controllers/AppController
class AppController extends Controller
{
    //
    public function sendVerificationCode(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'type' => 'required|in:client,driver',
        ]);

        $code = rand(1000, 9999);
        $key = $request->type . '_' . $request->phone;

        Cache::put($key, $code, now()->addMinutes(5));

        // SmsService::send($request->phone, "رمز التحقق الخاص بك هو: $code");

        return response()->json(['message' => 'تم إرسال رمز التحقق بنجاح',
                'code' => $code
    ]);
    }

    public function verifyCode(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'code' => 'required|digits:4',
            'type' => 'required|in:client,driver',
        ]);

        $key = $request->type . '_' . $request->phone;
        $storedCode = Cache::get($key);

        if (!$storedCode || $storedCode != $request->code) {
            return response()->json(['message' => 'رمز التحقق غير صحيح أو منتهي'], 401);
        }

        Cache::forget($key);

        if ($request->type === 'client') {
            $user = Client::firstOrCreate(['phone' => $request->phone]);
        } else {
            $user = Driver::firstOrCreate(['phone' => $request->phone]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'تم التحقق بنجاح',
            'user' => $user,
            'user_type' => $request->type,
            'token' => $token,
        ]);
    }

    public function logout(Request $request)
    {

        $request->user()->tokens()->delete();
        return response()->json(['message' => 'تم تسجيل الخروج']);
    }
}