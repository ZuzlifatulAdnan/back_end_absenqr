<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class FonnteService
{
    protected $apiToken;

    public function __construct()
    {
        $this->apiToken = config('services.fonnte.token');
    }
    
    public function sendMessage($to, $message, $mediaUrl = null)
    {
        $url = "https://api.fonnte.com/send";

        $payload = [
            'target' => $to,
            'message' => $message,
        ];

        if ($mediaUrl) {
            $payload['url'] = $mediaUrl;
        }

        $response = Http::asForm()->withHeaders([
            'Authorization' => $this->apiToken,
        ])->post($url, $payload);

        return $response->json();
    }
}
