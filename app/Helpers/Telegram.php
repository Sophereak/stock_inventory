<?php

namespace App\Helpers;

use GuzzleHttp\Client;

class Telegram
{
    protected $botToken;
    protected $chatId;

    public function __construct()
    {
        $this->botToken = env('TELEGRAM_BOT_TOKEN'); // Add this in your .env
        $this->chatId = env('TELEGRAM_CHAT_ID');     // Add this in your .env
    }

    public function sendMessage($message)
    {
        $client = new Client();
        $url = "https://api.telegram.org/bot{$this->botToken}/sendMessage";

        $response = $client->post($url, [
            'form_params' => [
                'chat_id' => $this->chatId,
                'text' => $message,
                'parse_mode' => 'HTML'
            ]
        ]);

        return $response->getBody()->getContents();
    }
}
