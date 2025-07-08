<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Favorate_dest;
use Illuminate\Support\Facades\Auth;

class FavoriteDestinationController extends Controller
{
    public function store(Request $request)
    {
        try {
            $clientId = Auth::guard('client')->id();
            if (!$clientId) {
                return response()->json(['message' => 'غير مصرح لك.'], 401);
            }

            $validated = $request->validate([
                'destination' => 'required|string',
                'address' => 'required|string',
            ]);

            $favorite = Favorate_dest::create([
                'client_id' => $clientId,
                'destination' => $validated['destination'],
                'address' => $validated['address'],
            ]);

            return response()->json([
                'message' => 'تم حفظ الوجهة المفضلة',
                'data' => $favorite,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'حدث خطأ داخلي',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}