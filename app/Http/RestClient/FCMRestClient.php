<?php

namespace App\Http\RestClient;

use App\Http\Classes\Logger;
use SoDe\Extend\Fetch;
use SoDe\Extend\JSON;
use SoDe\Extend\Trace;

class FCMRestClient
{
    static public function send(object $data): Fetch
    {
        $trace_id = Trace::getId();
        Logger::info("Request FCM/send [{$trace_id}]: " . JSON::stringify($data));

        $res = new Fetch("{$_ENV['FCM_URL']}/fcm/send", [
            'method' => 'POST',
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $_ENV['FIREBASE_SERVER_TOKEN'],
                'project_id' => $_ENV['FIREBASE_MESSAGING_SENDER_ID']
            ],
            'body' => $data
        ]);

        if ($res->ok) Logger::info("Response FCM/send [{$trace_id}]: " . $res->text());
        else Logger::error("Response FCM/send [{$trace_id}]: " . $res->text());

        return $res;
    }

    static public function notification(object $data): Fetch
    {
        $trace_id = Trace::getId();
        Logger::info("Request FCM/notification [{$trace_id}]: " . JSON::stringify($data));

        $res = new Fetch("{$_ENV['FCM_URL']}/fcm/notification", [
            'method' => 'POST',
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $_ENV['FIREBASE_SERVER_TOKEN'],
                'project_id' => $_ENV['FIREBASE_MESSAGING_SENDER_ID']
            ],
            'body' => $data
        ]);

        if ($res->ok) Logger::info("Response FCM/notification [{$trace_id}]: " . $res->text());
        else Logger::error("Response FCM/notification [{$trace_id}]: " . $res->text());

        return $res;
    }
}
