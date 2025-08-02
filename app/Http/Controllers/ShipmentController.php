<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shipment;
use App\Models\ShipmentDetails;
use Illuminate\Support\Facades\Log;

class ShipmentController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'Client_id' => 'required|integer',
            'type' => 'required|string',
            'weight' => 'required|string',
            'size' => 'required|string',
            'summary' => 'nullable|string',
            'destination' => 'required|string',
            'address' => 'required|string',
            'scheduled_date' => 'nullable|string',
            'scheduled_time' => 'nullable|string',
            'status' => 'nullable|string',
            'is_immediate' => 'boolean',
            'payment_method' => 'nullable|string',
        ]);

        $shipment = Shipment::create([
            'Client_id' => $validated['Client_id'],
            'Driver_id' => null,
        ]);

        $details = ShipmentDetails::create([
            'shipment_id' => $shipment->id,
            'type' => $validated['type'],
            'weight' => $validated['weight'],
            'size' => $validated['size'],
            'summary' => $validated['summary'] ?? null,
            'destination' => $validated['destination'],
            'address' => $validated['address'],
            'scheduled_date' => $validated['scheduled_date'] ?? null,
            'scheduled_time' => $validated['scheduled_time'] ?? null,
            'status' => 'قيد الانتظار', // ✅ تجاهل أي status من الطلب وثبتها يدويًا
            'is_immediate' => $validated['is_immediate'] ?? true,
            'payment_method' => $validated['payment_method'] ?? null,
        ]);


        return response()->json([
            'shipment' => $shipment,
            'details' => $details,
        ], 201);
    }

    public function destroy($id){
    $shipment = Shipment::with('details')->findOrFail($id);


    if ($shipment->Client_id !== auth('client')->id()) {
        return response()->json(['message' => 'غير مصرح لك بحذف هذه الشحنة'], 403);
    }

    $details = $shipment->details;


    if (
        $details->status !== 'قيد الانتظار' &&
        !($details->status === 'قيد التنفيذ' && $details->is_immediate == false)
    ) {
        return response()->json(['message' => 'لا يمكن حذف هذه الشحنة في حالتها الحالية'], 403);
    }

    $details->delete();
    $shipment->delete();

    return response()->json(['message' => 'تم حذف الشحنة بنجاح']);
}

public function listByStatus($status)
{
    $shipments = Shipment::with(['details', 'offers.driver']) // ✅ جلب التفاصيل والعروض مع السائق
        ->whereHas('details', function ($query) use ($status) {
            $query->where('status', $status);
        })
        ->where('Client_id', auth('client')->id()) // ✅ فقط الشحنات الخاصة بالعميل الحالي
        ->get();

    return response()->json($shipments);
}



public function getPendingShipments()
{
    $shipments = Shipment::with(['details', 'offers.driver']) // جلب التفاصيل والعروض مع السائق
        ->whereHas('details', function ($query) {
            $query->where('status', 'قيد الانتظار');
        })
        ->where('Client_id', auth('client')->id()) 
        ->get();

    return response()->json($shipments);
}


public function pastShipments(Request $request)
{
    $user = $request->user();

    if (!$user) {
        Log::warning('Unauthenticated request to pastShipments API.');
        return response()->json(['message' => 'Unauthenticated'], 401);
    }

    $shipments = Shipment::where('client_id', $user->id)
        ->whereHas('details', function ($query) {
            $query->where('status', 'تم التسليم');
        })
        ->with('details')
        ->orderBy('created_at', 'desc')
        ->get();

    Log::info('Past shipments for user ' . $user->id . ': ' . $shipments->toJson());

    return response()->json($shipments);
}



public function presentShipments()
{
    $user = auth('client')->user();

    $shipments = Shipment::where('Client_id', $user->id)
        ->whereHas('details', function ($query) {
            $query->where('status', 'قيد التنفيذ'); // ✅ استخدم where عادية
        })
        ->with(['details', 'offers.driver'])
        ->get();

    return response()->json($shipments);
}

//ALL for driver 
public function newOrdersForDriver()
{
    $shipments = Shipment::with('details')
        ->whereNull('Driver_id') // لم تُعين لسائق بعد
        ->whereHas('details', function ($query) {
            $query->where('status', 'قيد الانتظار');
        })
        ->get();

    return response()->json($shipments);
}

public function show($id)
{
    $shipment = Shipment::with('details')->find($id);

    if (!$shipment) {
        return response()->json(['message' => 'الشحنة غير موجودة'], 404);
    }

    return response()->json($shipment);
}


}