<?php

namespace App\Traits;

use App\Models\PaymentLog;
use App\Models\ReservationPayment;
use GuzzleHttp\Client;

use Exception;

trait PayMobTrait
{
    protected function generatePaymentUrl($total_price, $reservation_id,$modelName,$module)
    {
        try {
            // Get user data
            $name = auth()->user()->name;
            if (is_array($name)) {
                $name = explode(' ', $name);
            }

            $first_name = is_array($name) ? ($name[0] ?? $name) : $name;
            $last_name = is_array($name) ? ($name[1] ?? $name) : $name;
            $phone = auth()->user()->phone_id !== null ? auth()->user()->phone->phone : null;

            // Step 1: Get Authentication Token
            $authClient = new Client([
                'base_uri' => 'https://accept.paymob.com/api/auth/tokens',
                'timeout' => 5,
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    "username" => config('services.paymob.username'),
                    "password" => config('services.paymob.password')
                ]

            ]);

            $authResponse = $authClient->request('POST');
            $authData = json_decode($authResponse->getBody()->getContents());

            // Step 2: Create Payment Link
            $paymentClient = new Client([
                'base_uri' => 'https://accept.paymob.com/api/ecommerce/payment-links',
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $authData->token
                ],
                'json' => array_merge([
                    "amount_cents" => (int)($total_price * 100),
                    "currency" => "EGP",
                    "is_live" => false,
                    "payment_methods" => [
                        config('services.paymob.integration_id')
                    ],
                    "full_name" => $first_name . ' ' . $last_name,
                    "email" => auth()->user()->email ?? 'email@example.com',
                ], $phone !== null ? ["phone_number" => $phone] : []),
            ]);

            $paymentResponse = $paymentClient->request('POST');
            $paymentData = json_decode($paymentResponse->getBody()->getContents(), true);

            // Save payment record
            ReservationPayment::create([
                'reservationable_id' => $reservation_id,
                'reservationable_type' => $modelName,
                'reference_id' => $paymentData['order'],
                'module'=>$module,
                'payment_status' => $paymentData['state'] == 'created' ? 0 : 1,
                'payment_response' => json_encode($paymentData),
                'total_price' => $total_price,
            ]);

            return [
                'success' => true,
                'payment_url' => $paymentData['client_url'],
                'order_id' => $paymentData['order']
            ];

        } catch (Exception $e) {
            \Log::error('PayMob Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }


    public function checkPaymentStatus($orderId)
    {
        try {
            $client = new Client([
                'base_uri' => 'https://accept.paymob.com/api/',
                'timeout' => 5,
                'headers' => [
                    'Content-Type' => 'application/json',
                ]
            ]);

            // Authenticate and get the token
            $authResponse = $client->request('POST', 'auth/tokens', [
                'json' => [
                    "username" => config('services.paymob.username'),
                    "password" => config('services.paymob.password')
                ]
            ]);

            $authData = json_decode($authResponse->getBody()->getContents());

            // Transaction inquiry
            $inquiryResponse = $client->request('POST', 'ecommerce/orders/transaction_inquiry', [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $authData->token
                ],
                'json' => [
                    "order_id" => $orderId
                ]
            ]);

            $inquiryData = json_decode($inquiryResponse->getBody()->getContents(), true);
            $transactionId = $inquiryData['id'];

            $isPaid =  $inquiryData['success'] ? 1 : 0;
            $reservationPayment = ReservationPayment::where('reference_id', $orderId)->first();
            $reservationPayment->payment_status = $isPaid;
            $reservationPayment->transaction_id = $transactionId;
            $reservationPayment->save();



            $paymentLog = new PaymentLog();
            $paymentLog->payment_reservation_id = $reservationPayment->id;
            $paymentLog->log = json_encode($inquiryData);
            $paymentLog->status = $isPaid;
            $paymentLog->save();

            return $inquiryData['success'];

        } catch (\Exception $e) {
            \Log::error("Error checking payment status: " . $e->getMessage());
            return false;
        }
    }


}
