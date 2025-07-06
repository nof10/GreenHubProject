<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client_Profile;
use App\Models\Driver_Profile;

class ProfileController extends Controller
{
    public function updateProfile(Request $request)
    {
        $user = auth()->user();


        if ($user->typeuser === 'client') {

            $profile = Client_Profile::where('client_id', $user->id)->firstOrFail();
        // dd($profile);

            $request->validate([
                'name'  => 'required|string|max:255',
                'email' => 'required|email|unique:client__profiles,email,' . $profile->id,
                'city'  => 'nullable|string|max:100',
                'phone' => 'nullable|string|max:20',

            ]);
            // dd($request);
            $profile->update($request->only(['name', 'email', 'city', 'phone']));

        } elseif ($user->typeuser === 'driver') {
            $profile = Driver_Profile::where('driver_id', $user->id)->firstOrFail();

            $request->validate([
                'name'  => 'required|string|max:255',
                'email' => 'required|email|unique:driver_profiles,email,' . $profile->id,
                'license_number' => 'nullable|string|max:50',
                'phone' => 'nullable|string|max:20',
            ]);

            $profile->update($request->only(['name', 'email', 'license_number', 'phone']));
        }

        return response()->json([
            'message' => 'تم تحديث البروفايل بنجاح.',
            'profile' => $profile,
        ]);
    }
}
