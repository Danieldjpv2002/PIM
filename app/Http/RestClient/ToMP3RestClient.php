<?php

namespace App\Http\RestClient;

use App\Http\Classes\Logger;
use Exception;
use SoDe\Extend\Fetch;
use SoDe\Extend\File;
use SoDe\Extend\JSON;
use SoDe\Extend\Text;
use SoDe\Extend\Trace;

class ToMP3RestClient
{

    static public function search(string $query): Fetch
    {
        $body = [
            "query" => $query,
            "vt" => "downloader"
        ];

        $trace_id = Trace::getId();
        Logger::info("Request ToMP3/search [{$trace_id}]: " . JSON::stringify($body));

        $res = new Fetch("{$_ENV['TOMP3API_URL']}/search", [
            "method" => "POST",
            "body" => $body
        ]);

        if ($res->ok) Logger::info("Response ToMP3/search [{$trace_id}]: " . $res->text());
        else Logger::error("Response ToMP3/search [{$trace_id}]: " . $res->text());

        return $res;
    }

    static public function convert(string $id, string $code): Fetch
    {
        $body = [
            "vid" => $id,
            "k" => $code
        ];

        $trace_id = Trace::getId();
        Logger::info("Request ToMP3/convert [{$trace_id}]: " . JSON::stringify($body));

        $counter = 1;
        do {
            $res = new Fetch("{$_ENV['TOMP3API_URL']}/convert", [
                "method" => "POST",
                "body" => $body
            ]);
            if ($res->ok) {
                Logger::info("Response ({$counter}) ToMP3/convert [{$trace_id}]: " . $res->text());
                $data = $res->json();

                $verify = new Fetch($data['dlink']);

                if (
                    Text::startsWith($verify->contentType, 'audio') ||
                    Text::startsWith($verify->contentType, 'video')
                ) {
                    File::save("../storage/youtube/{$data['vid']}.{$data['ftype']}", $verify->blob());
                    break;
                }
            } else Logger::error("Response ({$counter}) ToMP3/convert [{$trace_id}]: " . $res->text());

            $counter++;
        } while ($counter <= 10);
        if ($counter > 10) throw new Exception('Se realizaron 10 intentos para descargar la música pero no se logró');
        return $res;
    }

    static public function smart(string $query, string $type = 'mp3')
    {
        try {
            // Round 1 [Search results]
            $r_res = ToMP3RestClient::search($query);
            if (!$r_res->ok) {
                throw new Exception("Ocurrió un error al obtener resultados de *{$query}*");
            }
            $r_data = $r_res->json();

            if ($r_data['p'] == 'convert') {
                $f_res = $r_res;
            } else {
                $youtube = $r_data['items'][0];

                // Round 2 [Formats & Qualities]
                $uri = "https://youtu.be/{$youtube['v']}";
                $f_res = ToMP3RestClient::search($uri);
            }

            if (!$f_res->ok) {
                throw new Exception("Ocurrió un error al obtener datos de *{$query}*. Intenta acceder a el con este link:" . Text::lineBreak() . $uri);
            }
            $f_data = $f_res->json();

            $code = null;
            $l_size = 0;
            $l_quality = '0';

            foreach ($f_data['links'][$type] as $key => $value) {
                $size = floatval(Text::keep($value['q'], '0123456789.') || '0');
                if ($value['f'] == $type && $size > $l_size && $size <= 721) {
                    $l_size = $size;
                    $l_quality = $value['q'];
                    $code = $value['k'];
                }
            }

            // Round 3 [Convert & Download]
            $c_res = ToMP3RestClient::convert($f_data['vid'], $code);
            if (!$c_res->ok) {
                throw new Exception("Ocurrió un error al descargar *{$query}*. Intenta acceder a el con este link:" . Text::lineBreak() . $uri);
            }
            $c_data = $c_res->json();

            return [
                "status" => true,
                "message"  => "Operación correcta",
                "data" => [
                    "id" => $c_data['vid'],
                    "uri" => $c_data['dlink'],
                    "title" => $c_data['title'],
                    "quality" => $l_quality,
                    "mime_type" => $c_data['ftype']
                ]
            ];
        } catch (\Throwable $th) {
            return [
                "status" => false,
                "message" => $th->getMessage()
            ];
        }
    }

    static public function related(string $query)
    {
        try {
            // Round 1 [Search results]
            $r_res = ToMP3RestClient::search($query);
            if (!$r_res->ok) {
                throw new Exception("Ocurrió un error al obtener resultados de *{$query}*");
            }
            $r_data = $r_res->json();

            if ($r_data['p'] == 'convert') {
                $f_res = $r_res;
            } else {
                $youtube = $r_data['items'][0];

                // Round 2 [Formats & Qualities]
                $uri = "https://youtu.be/{$youtube['v']}";
                $f_res = ToMP3RestClient::search($uri);
            }

            if (!$f_res->ok) {
                throw new Exception("Ocurrió un error al obtener datos de *{$query}*. Intenta acceder a el con este link:" . Text::lineBreak() . $uri);
            }
            $f_data = $f_res->json();

            $related = [];

            foreach ($f_data['related'][0]['contents'] as $value) {
                $related[] = [
                    "id" => $value['vid'],
                    "title" => $value['title'],
                    "uri" => 'youtu.be/' . $value['vid']
                ];
            }

            return [
                "status" => true,
                "message"  => "Operación correcta",
                "data" => $related
            ];
        } catch (\Throwable $th) {
            return [
                "status" => false,
                "message" => $th->getMessage()
            ];
        }
    }
}
