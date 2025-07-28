<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\Client;
use App\Models\Driver;
use App\Models\Drive_Profile;
use App\Models\Client_Profile;
///home/u295987876/domains/sa-fvs.com/public_html/gh/app/Http/Controllers/AppController
class ProfileController extends Controller
{
    public function updateProfile(Request $request)
{
    $user = auth()->user();

        if ($user->typeuser === 'client') {
            $validated = $request->validate([
                'name'  => 'required|string|max:255',
                'city'  => 'nullable|string|max:100',
                'phone' => 'nullable|string|max:20',
            ]);

        
            $profile = Client_Profile::updateOrCreate(
                ['client_id' => $user->id], 
                $validated                   
            );

            // لتحديث رقم الجوال في جدول العميل
            $user->phone = $request->phone;
            $user->save();

        } elseif ($user->typeuser === 'driver') {

            $validated = $request->validate([
                'name'          => 'required|string|max:255',
                'email' => 'required|email|unique:drive__profiles,email,' . $user->id . ',driver_id',
                'city'          => 'nullable|string|max:100',
                'national_ID'   => 'nullable|string|size:10|unique:drive__profiles,national_ID,' . $user->id . ',driver_id',
                'phone'         => 'nullable|string|max:20|unique:drive__profiles,phone,' . $user->id . ',driver_id',
                'documents'     => 'nullable|string|max:255',
                'birth_date'    => 'nullable|date',
            ]);

            $profile = Drive_Profile::updateOrCreate(
                ['driver_id' => $user->id],  
                $validated                   
            );
            // لتحديث رقم الجوال في جدول السائق 
            $user->phone = $request->phone;
            $user->save();

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
