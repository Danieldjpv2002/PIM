<?php

namespace SoDe\Extend;

class Response {
    public int $status;
    public string $message;
    public int $draw;
    public int $iTotalDisplayRecords;
    public int $iTotalRecords;
    public object|array|null $data;

    public function __construct() {
        $this -> status = 500;
        $this -> message = 'Error inesperado';
    }
    public function toArray(): array {
        $json = json_encode($this);
        $array = json_decode($json, true);
        return $array;
    }

    function __toString()
    {
        return json_encode($this);
    }
}