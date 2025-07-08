<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Offers;
use App\Models\Shipment;

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

    public function showOffersForShipment($shipmentId)
    {
    $shipment = Shipment::with('offers.driver')->findOrFail($shipmentId);

    if ($shipment->Client_id !== auth('client')->id()) {
        return response()->json(['message' => 'غير مصرح لك بمشاهدة هذه العروض'], 403);
    }

    return response()->json([
        'shipment_id' => $shipment->id,
        'offers' => $shipment->offers
    ]);
    }

    public function acceptOffer($offerId)
    {
    $offer = Offers::findOrFail($offerId);
    $shipment = $offer->shipment;

    if ($shipment->Client_id !== auth('client')->id()) {
        return response()->json(['message' => 'غير مصرح لك بالموافقة على هذا العرض'], 403);
    }

    $offer->status = 'accepted';
    $offer->save();
    // رفض جميع العروض الأخرى على نفس الشحنة
    Offers::where('shipment_id', $shipment->id)
        ->where('id', '!=', $offer->id)
        ->update(['status' => 'rejected']);
    // تحديث حالة الشحنة
    $shipment->Driver_id = $offer->driver_id;
    $shipment->save();

    return response()->json(['message' => 'تم قبول العرض بنجاح']);
}

}
