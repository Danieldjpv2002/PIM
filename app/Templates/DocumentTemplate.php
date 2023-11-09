<?php

namespace App\Templates;

use SoDe\Extend\JSON;

class DocumentTemplate
{
    public string $recipient_type = 'individual';
    public string $messaging_product = 'whatsapp';
    public ?string $to = null;
    public string $type = 'document';
    public array $document = [
        'link' => null,
        'filename' => null
    ];

    public function phone(?string $phone): void
    {
        $this->to = $phone;
    }

    public function link(?string $link): void
    {
        $this->document['link'] = $link;
    }

    public function filename(?string $filename): void
    {
        $this->document['filename'] = $filename;
    }

    public function get(): array
    {
        $json = JSON::stringify($this);
        return JSON::parse($json) ?? [];
    }
}
