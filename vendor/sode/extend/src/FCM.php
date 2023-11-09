<?php

namespace SoDe\Extend;

class FCM
{
    private const URI = 'https://fcm.googleapis.com/fcm';

    public static function send(object $data): bool
    {
        $uri = FCM::URI;

        $auth = $_ENV['FIREBASE_SERVER_TOKEN'];
        $projectId = $_ENV['FIREBASE_MESSAGING_SENDER_ID'];

        $res = new Fetch("{$uri}/send", [
            'method' => 'POST',
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => "Bearer {$auth}",
                'project_id' => $projectId
            ],
            'body' => $data
        ]);

        return $res->ok;
    }

    public static function notification(object $data): bool
    {
        $uri = FCM::URI;

        $auth = $_ENV['FIREBASE_SERVER_TOKEN'];
        $projectId = $_ENV['FIREBASE_MESSAGING_SENDER_ID'];

        $res = new Fetch("{$uri}/notification", [
            'method' => 'POST',
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => "Bearer {$auth}",
                'project_id' => $projectId
            ],
            'body' => $data
        ]);

        return $res->ok;
    }
}