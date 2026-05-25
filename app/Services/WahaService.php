<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class WahaService
{
    protected string $apiUrl;
    protected string $session;
    protected ?string $apiKey;
    protected Client $client;

    public function __construct()
    {
        $this->apiUrl = rtrim((string) config('services.waha.api_url'), '/');
        $this->session = (string) config('services.waha.session', 'default');
        $this->apiKey = config('services.waha.api_key');
        $this->client = new Client();
    }

    public function sendWhatsApp(string $phoneNumber, string $message): array|false
    {
        $chatId = $this->toChatId($phoneNumber);
        if (!$chatId) {
            return false;
        }

        $headers = [
            'Accept' => 'application/json',
        ];
        if (!empty($this->apiKey)) {
            $headers['X-Api-Key'] = $this->apiKey;
        }

        try {
            $response = $this->client->post($this->apiUrl . '/api/sendText', [
                'headers' => $headers,
                'json' => [
                    'session' => $this->session,
                    'chatId' => $chatId,
                    'text' => $message,
                ],
                'timeout' => 15,
            ]);

            $body = (string) $response->getBody();
            $decoded = json_decode($body, true);

            if (is_array($decoded)) {
                return $decoded;
            }

            return ['raw' => $body];
        } catch (\Throwable $e) {
            Log::error('WAHA sendText failed', [
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    protected function toChatId(string $phoneNumber): ?string
    {
        $trimmed = trim($phoneNumber);
        if ($trimmed === '') {
            return null;
        }

        if (str_contains($trimmed, '@')) {
            return $trimmed;
        }

        $digits = preg_replace('/\D+/', '', $trimmed) ?? '';
        if ($digits === '') {
            return null;
        }

        if (str_starts_with($digits, '0')) {
            $digits = '62' . substr($digits, 1);
        } elseif (str_starts_with($digits, '8')) {
            $digits = '62' . $digits;
        }

        return $digits . '@c.us';
    }
}
