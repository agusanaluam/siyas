<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MidtransService
{
    private $serverKey;
    private $isProduction;
    private $baseUrl;

    public function __construct()
    {
        $this->serverKey = env('MIDTRANS_SERVER_KEY');
        $this->isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        $this->baseUrl = $this->isProduction
            ? 'https://api.midtrans.com'
            : 'https://api.sandbox.midtrans.com';
    }

    /**
     * Membuat payment link di Midtrans
     *
     * @param string $orderId Order ID (dari liq_number)
     * @param int $grossAmount Total amount dalam rupiah
     * @param int $campaignId Campaign ID
     * @param string $campaignName Nama campaign
     * @param array $customerDetails Detail customer (first_name, email, phone, notes)
     * @return array|null Response dari Midtrans atau null jika gagal
     */
    public function createPaymentLink(
        string $orderId,
        int $grossAmount,
        int $campaignId,
        string $campaignName,
        array $customerDetails
    ): ?array {
        try {
            // Format nomor telepon jika belum ada +62
            $phone = $customerDetails['phone'] ?? '';
            if (!empty($phone) && !str_starts_with($phone, '+62')) {
                // Hapus leading 0 jika ada
                $phone = ltrim($phone, '0');
                // Tambahkan +62
                $phone = '+62' . $phone;
            }

            $payload = [
                'transaction_details' => [
                    'order_id' => $orderId,
                    'gross_amount' => $grossAmount,
                ],
                'item_details' => [
                    [
                        'id' => 'campaign-' . $campaignId,
                        'name' => $campaignName,
                        'price' => $grossAmount,
                        'quantity' => 1,
                    ],
                ],
                'customer_details' => [
                    'first_name' => $customerDetails['first_name'] ?? 'Donatur',
                    'email' => $customerDetails['email'] ?? '',
                    'phone' => $phone,
                ],
            ];

            // Tambahkan notes jika ada
            if (!empty($customerDetails['notes'])) {
                $payload['customer_details']['notes'] = $customerDetails['notes'];
            }

            // Basic Auth: Base64 encode dari server_key + ":"
            $auth = base64_encode($this->serverKey . ':');

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => 'Basic ' . $auth,
            ])->post($this->baseUrl . '/v1/payment-links', $payload);

            if ($response->successful()) {
                return $response->json();
            }

            // Log error jika gagal
            Log::error('Midtrans API Error', [
                'status' => $response->status(),
                'response' => $response->body(),
                'order_id' => $orderId,
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('Midtrans Service Exception', [
                'message' => $e->getMessage(),
                'order_id' => $orderId,
            ]);

            return null;
        }
    }

    /**
     * Generate Snap token untuk Snap.js popup
     *
     * @param string $orderId Order ID (dari liq_number)
     * @param int $grossAmount Total amount dalam rupiah
     * @param array $items Array of items dengan structure: [['id', 'name', 'price', 'quantity'], ...]
     * @param array $customerDetails Detail customer (first_name, email, phone, notes?)
     * @param array $callbacks Callback URLs (finish, unfinish, error)
     * @return string|null Snap token atau null jika gagal
     */
    public function generateSnapToken(
        string $orderId,
        int $grossAmount,
        array $items,
        array $customerDetails,
        array $callbacks = []
    ): ?string {
        try {
            // Validasi server key
            if (empty($this->serverKey)) {
                Log::error('Midtrans Server Key tidak ditemukan', [
                    'order_id' => $orderId,
                ]);
                return null;
            }

            // Format nomor telepon jika belum ada +62
            $phone = $customerDetails['phone'] ?? '';
            if (!empty($phone) && !str_starts_with($phone, '+62')) {
                // Hapus leading 0 jika ada
                $phone = ltrim($phone, '0');
                // Tambahkan +62
                $phone = '+62' . $phone;
            }

            $payload = [
                'transaction_details' => [
                    'order_id' => $orderId,
                    'gross_amount' => $grossAmount,
                ],
                'item_details' => $items,
                'customer_details' => [
                    'first_name' => $customerDetails['first_name'] ?? 'Donatur',
                    'email' => $customerDetails['email'] ?? '',
                    'phone' => $phone,
                ],
            ];

            // Tambahkan notes jika ada
            if (!empty($customerDetails['notes'])) {
                $payload['customer_details']['notes'] = $customerDetails['notes'];
            }

            // Tambahkan callbacks jika ada
            if (!empty($callbacks)) {
                $payload['callbacks'] = $callbacks;
            }

            // Basic Auth: Base64 encode dari server_key + ":"
            $auth = base64_encode($this->serverKey . ':');

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => 'Basic ' . $auth,
            ])->post($this->baseUrl . '/snap/v1/transactions', $payload);

            if ($response->successful()) {
                $result = $response->json();
                return $result['token'] ?? null;
            }

            // Log error jika gagal
            Log::error('Midtrans Snap Token API Error', [
                'status' => $response->status(),
                'response' => $response->body(),
                'order_id' => $orderId,
                'base_url' => $this->baseUrl,
                'endpoint' => $this->baseUrl . '/snap/v1/transactions',
                'has_server_key' => !empty($this->serverKey),
                'payload' => $payload,
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('Midtrans Snap Token Service Exception', [
                'message' => $e->getMessage(),
                'order_id' => $orderId,
                'trace' => $e->getTraceAsString(),
            ]);

            return null;
        }
    }
}
