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
        $client = auth('client')->user();

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

 public function acceptOffer(Request $request)
{
    $request->validate([
        'offer_id' => 'required|exists:offers,id',
    ]);

    $offer = Offers::findOrFail($request->offer_id);
    $shipment = $offer->shipment;

    //\Log::info('Client ID (auth): ' . auth('client')->id());
    //\Log::info('Shipment Client ID: ' . $shipment->client_id);
    //\Log::info('Shipment full data: ' . json_encode($shipment));

    if ($shipment->client_id !== auth('client')->id()) {
        return response()->json(['message' => 'غير مصرح لك بالموافقة على هذا العرض'], 403);
    }

    $offer->status = 'accepted';
    $offer->save();

    Offers::where('shipment_id', $shipment->id)
        ->where('id', '!=', $offer->id)
        ->update(['status' => 'rejected']);

    $shipment->Driver_id = $offer->driver_id;
    $shipment->details->status ='قيد التنفيذ';
    $shipment->details->save();
    $shipment->save();

    return response()->json(['message' => 'تم قبول العرض بنجاح']);
}



}
