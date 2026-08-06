<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\MidtransWebhookService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    protected $webhookService;

    public function __construct(MidtransWebhookService $webhookService)
    {
        $this->webhookService = $webhookService;
    }

    /**
     * Handle webhook notification dari Midtrans
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handleDonationWebhook(Request $request)
    {
        // Log semua webhook yang diterima untuk debugging
        Log::info('Midtrans webhook received', [
            'payload' => $request->all(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        $payload = $request->all();

        // Validasi payload
        $requiredFields = ['order_id', 'transaction_status', 'status_code', 'gross_amount', 'signature_key'];
        foreach ($requiredFields as $field) {
            if (!isset($payload[$field])) {
                Log::warning('Midtrans webhook: Missing required field', [
                    'field' => $field,
                    'payload' => $payload,
                ]);
                return response()->json([
                    'message' => "Missing required field: {$field}"
                ], 400);
            }
        }

        // Process webhook melalui service
        $result = $this->webhookService->handleWebhook($payload);

        // Return response sesuai status code
        return response()->json([
            'message' => $result['message'],
            'success' => $result['success'],
        ], $result['status_code']);
    }
}
