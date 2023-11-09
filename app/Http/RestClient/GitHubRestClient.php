<?php

namespace App\Http\RestClient;

use SoDe\Extend\Fetch;
use SoDe\Extend\Trace;
use App\Http\Classes\Logger;

class GitHubRestClient {
    static public function users($username): Fetch
    {
        $traceid = Trace::getId();
        Logger::info("Request GitHub/users [{$traceid}]: {$username}");

        $res = new Fetch("https://api.github.com/users/{$username}", [
            'method' => 'GET',
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0.0.0 Safari/537.36',
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ]
        ]);

        if ($res->ok) Logger::info("Response GitHub/users [{$traceid}]: " . $res->text());
        else Logger::error("Response GitHub/users [{$traceid}]: " . $res->text());

        return $res;
    }
}