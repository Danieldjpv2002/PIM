<?php

namespace App\Templates;

use SoDe\Extend\JSON;

class MediaTemplate
{
    public string $recipient_type = 'individual';
    public string $messaging_product = 'whatsapp';
    public ?string $to = null;
    public string $type = 'image';

    public function phone(?string $phone): void
    {
        $this->to = $phone;
    }

    public function type(?string $type): void
    {
        $this->type = $type;
    }

    public function set($key, $value) {
        $this->$key = $value;
    }

    public function media(string $uri, ?string $caption = null): void
    {
        $this->set($this->type, [
            'link' => $uri,
            'caption' => $caption
        ]);
    }

    public function get(): array
    {
        $json = JSON::stringify($this);
        return JSON::parse($json) ?? [];
    }
}
