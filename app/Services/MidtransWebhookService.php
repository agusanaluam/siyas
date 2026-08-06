<?php

namespace App\Services;

use App\Models\Transaction\Donation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MidtransWebhookService
{
    /**
     * Map status transaksi Midtrans ke status payment
     *
     * @param string $transactionStatus Status dari Midtrans
     * @return string Status payment
     */
    private function mapTransactionStatus(string $transactionStatus): string
    {
        return match ($transactionStatus) {
            'settlement', 'capture' => 'paid',
            'cancel', 'expire' => 'failed',
            'pending' => 'pending',
            default => 'pending',
        };
    }

    /**
     * Handle webhook notification dari Midtrans
     *
     * @param array $payload Payload dari webhook
     * @return array Response dengan status dan message
     */
    public function handleWebhook(array $payload): array
    {
        $orderId = $payload['order_id'] ?? null;
        $transactionStatus = $payload['transaction_status'] ?? null;
        $transactionId = $payload['transaction_id'] ?? null;

        if (empty($orderId) || empty($transactionStatus)) {
            return [
                'success' => false,
                'message' => 'Missing required fields',
                'status_code' => 400,
            ];
        }

        try {
            DB::beginTransaction();

            // Cari donation berdasarkan liq_number (order_id)
            $donation = Donation::where('liq_number', $orderId)->first();

            if (!$donation) {
                Log::warning('Midtrans webhook: Order ID not found', [
                    'order_id' => $orderId,
                    'transaction_id' => $transactionId,
                ]);

                DB::rollBack();
                return [
                    'success' => false,
                    'message' => 'Order ID not found',
                    'status_code' => 404,
                ];
            }

            // Cek apakah sudah pernah diproses (idempotent check)
            // Jika status sudah sama dan sudah ada transaction_id, skip update
            $paymentStatus = $this->mapTransactionStatus($transactionStatus);

            if ($donation->payment_status === $paymentStatus && !empty($donation->midtrans_transaction_id)) {
                Log::info('Midtrans webhook: Notification already processed (idempotent)', [
                    'order_id' => $orderId,
                    'transaction_id' => $transactionId,
                    'current_status' => $donation->payment_status,
                ]);

                DB::rollBack();
                return [
                    'success' => true,
                    'message' => 'Notification already processed',
                    'status_code' => 200,
                ];
            }

            // Update donation
            $donation->payment_status = $paymentStatus;
            $donation->midtrans_transaction_id = $transactionId;

            // Update status donation berdasarkan payment status
            if ($paymentStatus === 'paid') {
                $donation->status = 'Completed';
            } elseif ($paymentStatus === 'failed') {
                $donation->status = 'Failed';
            } else {
                $donation->status = 'Pending';
            }

            $donation->save();

            DB::commit();

            Log::info('Midtrans webhook: Donation updated successfully', [
                'order_id' => $orderId,
                'transaction_id' => $transactionId,
                'transaction_status' => $transactionStatus,
                'payment_status' => $paymentStatus,
                'donation_id' => $donation->id,
            ]);

            return [
                'success' => true,
                'message' => 'Webhook processed successfully',
                'status_code' => 200,
                'donation_id' => $donation->id,
            ];

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Midtrans webhook: Error processing webhook', [
                'order_id' => $orderId,
                'transaction_id' => $transactionId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'message' => 'Error processing webhook: ' . $e->getMessage(),
                'status_code' => 500,
            ];
        }
    }
}
