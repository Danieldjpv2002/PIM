<?php

namespace App\Templates;

use SoDe\Extend\JSON;

class MessageTemplate
{
    public string $recipient_type = 'individual';
    public string $messaging_product = 'whatsapp';
    public ?string $to = null;
    public string $type = 'text';
    public array $text = [
        'body' => null,
        'preview_url' => true
    ];

    public function phone(?string $phone): void
    {
        $this->to = $phone;
    }

    public function body(?string $body): void
    {
        $this->text['body'] = $body;
    }

    public function get(): array
    {
        $json = JSON::stringify($this);
        return JSON::parse($json) ?? [];
    }
}
