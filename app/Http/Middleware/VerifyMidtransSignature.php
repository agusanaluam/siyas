<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class VerifyMidtransSignature
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $serverKey = env('MIDTRANS_SERVER_KEY');

        if (empty($serverKey)) {
            Log::error('Midtrans Server Key tidak ditemukan di environment');
            return response()->json([
                'message' => 'Server configuration error'
            ], 500);
        }

        $orderId = $request->input('order_id');
        $statusCode = $request->input('status_code');
        $grossAmount = $request->input('gross_amount');
        $signatureKey = $request->input('signature_key');

        // Validasi field yang diperlukan
        if (empty($orderId) || empty($statusCode) || empty($grossAmount) || empty($signatureKey)) {
            Log::warning('Midtrans webhook missing required fields', [
                'order_id' => $orderId,
                'has_status_code' => !empty($statusCode),
                'has_gross_amount' => !empty($grossAmount),
                'has_signature_key' => !empty($signatureKey),
            ]);
            return response()->json([
                'message' => 'Missing required fields'
            ], 400);
        }

        // Generate signature: SHA512(order_id + status_code + gross_amount + server_key)
        $signatureString = $orderId . $statusCode . $grossAmount . $serverKey;
        $expectedSignature = hash('sha512', $signatureString);

        // Bandingkan signature
        if (!hash_equals($expectedSignature, $signatureKey)) {
            Log::warning('Midtrans webhook signature verification failed', [
                'order_id' => $orderId,
                'expected_signature' => substr($expectedSignature, 0, 20) . '...',
                'received_signature' => substr($signatureKey, 0, 20) . '...',
            ]);
            return response()->json([
                'message' => 'Invalid signature'
            ], 403);
        }

        Log::info('Midtrans webhook signature verified', [
            'order_id' => $orderId,
        ]);

        return $next($request);
    }
}
