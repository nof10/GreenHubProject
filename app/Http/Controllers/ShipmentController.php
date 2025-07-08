<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shipment;
use App\Models\ShipmentDetails;

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
            'payment_method' => 'required|string',
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
            'status' => $validated['status'] ?? 'pending',
            'is_immediate' => $validated['is_immediate'] ?? true,
            'payment_method' => $validated['payment_method'],
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

    $shipments = Shipment::with('details')
        ->whereHas('details', function ($query) use ($status) {
            $query->where('status', $status);
        })
        ->get();

    return response()->json($shipments);
}

}
