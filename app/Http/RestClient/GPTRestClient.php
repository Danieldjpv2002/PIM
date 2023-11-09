<?php

namespace App\Http\RestClient;

use App\Http\Classes\Logger;
use SoDe\Extend\Fetch;
use SoDe\Extend\JSON;
use SoDe\Extend\Text;
use SoDe\Extend\Trace;

class GPTRestClient
{
    static public function completions(string $text, string $token): Fetch
    {
        $body = [
            'model' => 'text-davinci-003',
            'prompt' => $text,
            'temperature' => 1,
            'max_tokens' => 1024,
            'top_p' => 1,
            'frequency_penalty' => 0,
            'presence_penalty' => 0.6,
            'stop' => [Text::lineBreak() . 'assistant [', Text::lineBreak() . 'client [']
        ];

        $traceid = Trace::getId();
        Logger::info("Request GPT/completions [{$traceid}]: " . JSON::stringify($body));

        $res = new Fetch("{$_ENV['OPENAIAPI_URL']}/completions", [
            'method' => 'POST',
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $token
            ],
            'body' => $body
        ]);

        if ($res->ok) Logger::info("Response GPT/completions [{$traceid}]: " . $res->text());
        else Logger::error("Response GPT/completions [{$traceid}]: " . $res->text());

        return $res;
    }

    static public function generations(string $prompt, string $token): Fetch
    {
        $body = [
            'prompt' => $prompt,
            'n' => 1,
            'size' => '256x256'
        ];

        $traceid = Trace::getId();
        Logger::info("Request GPT/images/generations [{$traceid}]: " . JSON::stringify($body));

        $res = new Fetch("{$_ENV['OPENAIAPI_URL']}/images/generations", [
            'method' => 'POST',
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $token
            ],
            'body' => $body
        ]);

        if ($res->ok) Logger::info("Response GPT/images/generations [{$traceid}]: " . $res->text());
        else Logger::error("Response GPT/images/generations [{$traceid}]: " . $res->text());

        return $res;
    }

    static public function transcriptions(string $file, string $token): Fetch
    {
        $body = [
            'file' => "@{$file}",
            'model' => 'whisper-1'
        ];

        $traceid = Trace::getId();
        Logger::info("Request GPT/audio/transcriptions [{$traceid}]: " . JSON::stringify($body));

        $res = new Fetch("{$_ENV['OPENAIAPI_URL']}/audio/transcriptions", [
            'method' => 'POST',
            'headers' => [
                'Content-Type' => 'multipart/form-data',
                'Authorization' => 'Bearer ' . $token
            ],
            'body' => $body
        ]);

        if ($res->ok) Logger::info("Response GPT/audio/transcriptions [{$traceid}]: " . $res->text());
        else Logger::error("Response GPT/audio/transcriptions [{$traceid}]: " . $res->text());

        return $res;
    }
}
