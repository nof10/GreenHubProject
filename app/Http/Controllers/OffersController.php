<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Offers;

class OffersController extends Controller
{
    public function store(Request $request)
    {
        // تحقق من صحة البيانات
        $request->validate([
            'shipment_id' => 'required|exists:shipments,id',
            'price' => 'required|numeric|min:0',
        ]);

        // السائق الموثق بالتوكن
        $driver = auth('driver')->user();

        // تأكد أنه ما قدم عرض من قبل على نفس الشحنة
        $existingOffer = Offers::where('shipment_id', $request->shipment_id)
                            ->where('driver_id', $driver->id)
                            ->first();

        if ($existingOffer) {
            return response()->json(['message' => 'لقد قدمت عرضًا مسبقًا على هذه الشحنة'], 400);
        }

        // أنشئ العرض
        $offer = Offers::create([
            'shipment_id' => $request->shipment_id,
            'driver_id' => $driver->id,
            'price' => $request->price,
            'status' => 'قيد المراجعة',
        ]);

        return response()->json(['message' => 'تم إرسال العرض بنجاح', 'offer' => $offer], 201);
    }
}
