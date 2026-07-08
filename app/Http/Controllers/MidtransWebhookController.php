<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MidtransWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->all();
        
        Log::info('Midtrans Webhook Masuk! Payload:', $payload);

        $orderId = $payload['order_id'] ?? null;
        $statusCode = $payload['status_code'] ?? null;
        $grossAmount = $payload['gross_amount'] ?? null;
        $transactionStatus = $payload['transaction_status'] ?? null;
        $fraudStatus = $payload['fraud_status'] ?? null;
        $signatureKeyInput = $payload['signature_key'] ?? null;

        if (!$orderId) {
            Log::error('Webhook Gagal: Order ID kosong.');
            return response()->json(['message' => 'Invalid payload'], 400);
        }

        // 1. KEAMANAN CRITICAL: Validasi SHA512 Signature Key (Mencegah Pemalsuan Data Pembayaran)
        $serverKey = env('MIDTRANS_SERVER_KEY');
        $localSignatureKey = hash("sha512", $orderId . $statusCode . $grossAmount . $serverKey);

        if ($signatureKeyInput !== $localSignatureKey) {
            Log::error('Webhook Gagal: Signature Key TIDAK COCOK! Dugaan manipulasi data.');
            return response()->json(['message' => 'Invalid signature key'], 403);
        }

        // Mencari ID transaksi di database lokal
        $transaction = Transaction::with('event')->where('order_id', $orderId)->first();

        if (!$transaction) {
            Log::error('Webhook Gagal: Order ID ' . $orderId . ' TIDAK DITEMUKAN di database!');
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        // Cegah proses berulang jika sudah berstatus success/failed
        if (in_array($transaction->status, ['success', 'failed'])) {
            Log::info('Webhook Info: Order ID ' . $orderId . ' sudah diproses sebelumnya dengan status: ' . $transaction->status);
            return response()->json(['message' => 'Already processed']);
        }

        // Logika Penerjemahan Status Midtrans API
        if ($transactionStatus == 'capture') {
            if ($fraudStatus == 'challenge') {
                $transaction->status = 'challenge';
            } else if ($fraudStatus == 'accept') {
                $transaction->status = 'success';
                $this->processSuccess($transaction);
            }
        } else if ($transactionStatus == 'settlement') {
            $transaction->status = 'success'; 
            $this->processSuccess($transaction);
            Log::info('Webhook Berhasil: Order ID ' . $orderId . ' sukses dibayar via settlement.');
        } else if (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
            $transaction->status = 'failed';
        } else if ($transactionStatus == 'pending') {
            $transaction->status = 'pending';
        }

        $transaction->save();
        return response()->json(['message' => 'OK']);
    }

    private function processSuccess(Transaction $transaction)
    {
        $event = $transaction->event;
        
        // Jika tiket masih ada dan terhubung dengan data event, kurangi jumlahnya sebanyak 1
        if ($event && $event->stock > 0) {
            $event->stock = $event->stock - 1;
            $event->save();
            
            // Mengirimkan email E-Ticket ke pelanggan
            try {
                \Illuminate\Support\Facades\Mail::to($transaction->customer_email)->send(new \App\Mail\EventTicketMail($transaction));
            } catch (\Exception $e) {
                Log::error('Gagal mengirim email E-Ticket: ' . $e->getMessage());
            }
        } else {
            Log::warning('Stock habis setelah pembayaran berhasil (Perlu proses refund opsional). Order: ' . $transaction->order_id);
        }
    }
}   