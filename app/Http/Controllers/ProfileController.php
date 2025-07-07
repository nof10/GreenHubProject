<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client_Profile;
use App\Models\Drive_Profile;

class ProfileController extends Controller
{
    public function updateProfile(Request $request)
{
    $user = auth()->user();

    if ($user->typeuser === 'client') {

        $profile = Client_Profile::where('client_id', $user->id)->firstOrFail();

        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:client__profiles,email,' . $profile->id,
            'city'  => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:20',
        ]);

        $profile->update($request->only(['name', 'email', 'city', 'phone']));

    } elseif ($user->typeuser === 'driver') {

        $profile = Drive_Profile::where('driver_id', $user->id)->firstOrFail();

        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:drive__profiles,email,' . $profile->id,
            'city'          => 'nullable|string|max:100',
            'national_ID'   => 'nullable|string|size:10|unique:drive__profiles,national_ID,' . $profile->id,
            'phone'         => 'nullable|string|max:20|unique:drive__profiles,phone,' . $profile->id,
            'documents'     => 'nullable|string|max:255',
        ]);

        $profile->update($request->only(['name', 'email', 'city', 'national_ID', 'phone', 'documents']));

    } else {
        return response()->json([
            'message' => 'نوع المستخدم غير مدعوم.',
        ], 400);
    }

    return response()->json([
        'message' => 'تم تحديث البروفايل بنجاح.',
        'profile' => $profile,
    ]);
}
}
