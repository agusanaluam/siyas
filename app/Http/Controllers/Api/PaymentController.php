<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    protected $midtransService;

    public function __construct(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }

    /**
     * Generate Snap token untuk checkout
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function generateSnapToken(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|string|max:50',
            'amount' => 'required|numeric|min:1000',
            'email' => 'required|email',
            'name' => 'required|string|max:150',
            'phone' => 'required|string',
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|string',
            'items.*.name' => 'required|string',
            'items.*.price' => 'required|numeric|min:1',
            'items.*.quantity' => 'required|integer|min:1',
            'description' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 400);
        }

        try {
            // Format phone number
            $phone = $request->phone;
            if (!str_starts_with($phone, '+62')) {
                $phone = ltrim($phone, '0');
                $phone = '+62' . $phone;
            }

            // Prepare customer details
            $customerDetails = [
                'first_name' => $request->name,
                'email' => $request->email,
                'phone' => $phone,
            ];

            if ($request->has('description')) {
                $customerDetails['notes'] = $request->description;
            }

            // Prepare callbacks
            $callbacks = [
                'finish' => $request->callback_url ?? url('/donasi/sukses'),
                'unfinish' => $request->callback_url ?? url('/donasi/sukses'),
                'error' => $request->callback_url ?? url('/donasi/sukses'),
            ];

            // Generate snap token
            $token = $this->midtransService->generateSnapToken(
                $request->order_id,
                (int) $request->amount,
                $request->items,
                $customerDetails,
                $callbacks
            );

            if (!$token) {
                Log::error('Payment Controller: Failed to generate snap token', [
                    'order_id' => $request->order_id,
                    'amount' => $request->amount,
                    'has_server_key' => !empty(env('MIDTRANS_SERVER_KEY')),
                ]);

                return response()->json([
                    'message' => 'Gagal generate snap token. Pastikan MIDTRANS_SERVER_KEY sudah dikonfigurasi dengan benar di .env',
                ], 500);
            }

            return response()->json([
                'token' => $token,
                'redirect_url' => null, // Snap popup tidak perlu redirect_url
            ], 200);

        } catch (\Exception $e) {
            Log::error('Payment Controller Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all(),
            ]);

            $isProduction = config('app.env') === 'production';
            return response()->json([
                'message' => 'Terjadi kesalahan saat generate snap token',
                'error' => $isProduction ? null : $e->getMessage(),
            ], 500);
        }
    }
}
