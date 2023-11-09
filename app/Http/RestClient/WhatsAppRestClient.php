<?php

namespace App\Http\RestClient;

use App\Http\Classes\Logger;
use App\Templates\MediaTemplate;
use App\Templates\MessageTemplate;
use Exception;
use SoDe\Extend\Fetch;
use SoDe\Extend\JSON;
use SoDe\Extend\Trace;

class WhatsAppRestClient
{
    static public function sendMessage(?string $content, ?string $to, ?string $whatsapp_id = null): Fetch
    {

        $whatsapp_id = $whatsapp_id ?? 'WHATSAPP_ID';

        $message = new MessageTemplate();
        $message->phone($to);
        $message->body($content);

        $traceid = Trace::getId();
        Logger::info("Request WhatsApp [{$traceid}]: " . JSON::stringify($message->get()));

        $res = new Fetch("https://graph.facebook.com/v15.0/{$_ENV[$whatsapp_id]}/messages", [
            'method' => 'POST',
            'body' => $message->get(),
            'headers' => [
                "Authorization" => "Bearer " . $_ENV['WHATSAPP_TOKEN_API'],
                "Content-Type" => "application/json"
            ]
        ]);

        if ($res->ok) Logger::info("Response WhatsApp [{$traceid}]: " . $res->text());
        else Logger::error("Response WhatsApp [{$traceid}]: " . $res->text());

        return $res;
    }

    static public function sendTemplate(?object $template, ?string $whatsapp_id = null): Fetch
    {
        $whatsapp_id = $whatsapp_id ?? 'WHATSAPP_ID';

        $traceid = Trace::getId();
        Logger::info("Request WhatsApp [{$traceid}]: " . JSON::stringify($template->get()));

        $res = new Fetch("https://graph.facebook.com/v15.0/{$_ENV[$whatsapp_id]}/messages", [
            'method' => 'POST',
            'body' => $template->get(),
            'headers' => [
                "Authorization" => "Bearer " . $_ENV['WHATSAPP_TOKEN_API'],
                "Content-Type" => "application/json"
            ]
        ]);

        if ($res->ok) Logger::info("Response WhatsApp [{$traceid}]: " . $res->text());
        else Logger::error("Response WhatsApp [{$traceid}]: " . $res->text());

        return $res;
    }

    static public function sendMedia(MediaTemplate $media, ?string $whatsapp_id = null): Fetch
    {
        $whatsapp_id = $whatsapp_id ?? 'WHATSAPP_ID';

        $traceid = Trace::getId();
        Logger::info("Request WhatsApp [{$traceid}]: " . JSON::stringify($media->get()));

        $res = new Fetch("https://graph.facebook.com/v15.0/{$_ENV[$whatsapp_id]}/messages", [
            'method' => 'POST',
            'body' => $media->get(),
            'headers' => [
                "Authorization" => "Bearer " . $_ENV['WHATSAPP_TOKEN_API'],
                "Content-Type" => "application/json"
            ]
        ]);

        if ($res->ok) Logger::info("Response WhatsApp [{$traceid}]: " . $res->text());
        else Logger::error("Response WhatsApp [{$traceid}]: " . $res->text());

        return $res;
    }

    static public function getMedia(string $media_id): Fetch
    {
        $traceid = Trace::getId();
        Logger::info("Request WhatsApp [{$traceid}]: {$media_id}");

        $res = new Fetch("https://graph.facebook.com/v15.0/{$media_id}/", [
            'method' => 'GET',
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => "Bearer " . $_ENV['WHATSAPP_TOKEN_API']
            ]
        ]);

        if ($res->ok) Logger::info("Response WhatsApp [{$traceid}]: " . $res->text());
        else Logger::error("Response WhatsApp [{$traceid}]: " . $res->text());

        return $res;
    }

    static public function downloadMedia(string $uri, string $mime_type): string|false
    {
        $traceid = Trace::getId();

        $body = [
            'url' => $uri,
            'mime_type' => $mime_type
        ];
        Logger::info("Request WhatsApp [{$traceid}]: " . JSON::stringify($body));

        $file = false;

        try {
            $res = new Fetch('https://whatsapp-service-prd-hpo6gn7esq-uc.a.run.app/whatsapp/file/download', [
                'method' => 'POST',
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => "Bearer " . $_ENV['WHATSAPP_TOKEN_API']
                ],
                'body' => $body
            ]);

            if (!$res->ok) {
                throw new Exception($res->text());
            }

            $file = $res->blob();
            Logger::info("Response WhatsApp [{$traceid}]: Descarga correcta");
        } catch (\Throwable $th) {
            Logger::error("Response WhatsApp [{$traceid}]: {$th->getMessage()}");
        }

        return $file;
    }
}
