<?php

namespace App\Traits;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

trait DreamsSmsTrait
{
    protected function getDreamsUser()
    {
        return config('services.dreams.user');
    }

    protected function getDreamsSecretKey()
    {
        return config('services.dreams.secret_key');
    }

    protected function getDreamsSender()
    {
        return config('services.dreams.sender', 'Fzaah.com');
    }

    public function sendDreamsSms($to, $message)
    {
        $url = 'https://www.dreams.sa/index.php/api/sendsms/';

        $queryParams = [
            'user' => $this->getDreamsUser(),
            'secret_key' => $this->getDreamsSecretKey(),
            'sender' => $this->getDreamsSender(),
            'to' => $to,
            'message' => $message,
        ];

        $response = Http::timeout(30)->get($url, $queryParams);

   

        if ($response->successful()) {
            return $response->body();
        }

        Log::error('Dreams SMS Send Failed', [
            'status' => $response->status(),
            'body' => $response->body(),
            'url' => $url,
            'params' => $queryParams
        ]);

        throw new \Exception('Failed to send SMS. Status: ' . $response->status());
    }
}
