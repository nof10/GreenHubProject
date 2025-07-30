<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Favorate_dest;
use Illuminate\Support\Facades\Auth; // تأكد من وجود هذا الاستيراد

class FavoriteDestinationController extends Controller
{
    public function store(Request $request)
    {
        try {
            // بدلاً من Auth::guard('client')->id()، نستخدم Auth::user()
            // لأن المسار محمي بـ 'auth:sanctum'
            $user = Auth::user();

            // التحقق مما إذا كان المستخدم مصادقًا
            if (!$user) {
                // هذا السطر من المفترض ألا يتم الوصول إليه إذا كان middleware 'auth:sanctum' يعمل بشكل صحيح
                // لأنه سيمنع الطلب قبل الوصول إلى هنا إذا لم يكن هناك مستخدم مصادق
                return response()->json(['message' => 'غير مصرح لك. (المستخدم غير موجود)'], 401);
            }

            // التأكد من أن المستخدم المصادق هو من نوع 'client' إذا كان ذلك مطلوبًا
            // يمكنك إضافة هذا التحقق إذا كان لديك أنواع مستخدمين مختلفة
            // if ($user->typeuser !== 'client') {
            //     return response()->json(['message' => 'غير مصرح لك. (ليس عميلاً)'], 403);
            // }

            // الحصول على client_id من المستخدم المصادق
            $clientId = $user->id; // نفترض أن الـ ID الخاص بالمستخدم المصادق هو client_id

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

        } catch (\Illuminate\Validation\ValidationException $e) {
            // لالتقاط أخطاء التحقق من الصحة بشكل خاص
            return response()->json([
                'message' => 'خطأ في البيانات المدخلة',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            // لالتقاط أي أخطاء أخرى غير متوقعة
            return response()->json([
                'message' => 'حدث خطأ داخلي',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
