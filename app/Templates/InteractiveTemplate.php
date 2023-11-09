<?php

namespace App\Templates;

use SoDe\Extend\JSON;

class InteractiveTemplate
{
    public string $recipient_type = 'individual';
    public string $messaging_product = 'whatsapp';
    public ?string $to = null;
    public string $type = 'interactive';
    public array $interactive = [];

    public function phone(?string $phone): void
    {
        $this->to = $phone;
    }

    public function interactive(?array $interactive): void
    {
        $this->interactive = $interactive;
    }

    public function get(): array
    {
        $json = JSON::stringify($this);
        return JSON::parse($json) ?? [];
    }
}
