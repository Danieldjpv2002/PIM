<?php

namespace SoDe\Extend;
use Exception;

class Fetch
{
    private $curl;
    private string $response;
    public bool $ok;
    public string $status;
    public string $contentType;

    function __construct(string $url, array $options = [])
    {
        $options['headers'] = $options['headers'] ?? [];
        $options['method'] = $options['method'] ?? 'GET';
        $options['body'] = $options['body'] ?? [];
        $options['timeout'] = $options['timeout'] ?? 0;

        if (isset($options['headers']['Content-Type']) && $options['headers']['Content-Type'] == 'application/json') {
            $options['body'] = json_encode($options['body']);
        }

        $this->curl = curl_init();

        $headers = [];
        foreach ($options['headers'] as $key => $value) {
            $headers[] = "$key: $value";
        }

        $opt_array = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => $options['timeout'],
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $options['method'],
            CURLOPT_POSTFIELDS => $options['method'] != 'GET' ? $options['body'] : null,
            CURLOPT_HTTPHEADER => $headers,
        ];

        if (isset($options['headers']['Content-Type']) && $options['headers']['Content-Type'] == 'multipart/form-data') {
            $opt_array[CURLOPT_POST] = 1;
        }

        curl_setopt_array($this->curl, $opt_array);

        $this->response = curl_exec($this->curl);
        $this->status = (string) curl_getinfo($this->curl, CURLINFO_RESPONSE_CODE);
        $this->ok = $this->status >= 200 && $this->status < 300;
        $this->contentType = (string) curl_getinfo($this->curl, CURLINFO_CONTENT_TYPE);

        if ($this->response === false) {
            throw new Exception(curl_error($this->curl), curl_errno($this->curl));
        }
    }

    public function text(): string
    {
        return $this->response;
    }

    public function json(): array
    {
        return json_decode($this->response, true);
    }

    public function blob(): string
    {
        return $this->response;
    }

    function __destruct()
    {
        curl_close($this->curl);
    }
}