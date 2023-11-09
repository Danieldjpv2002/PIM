<?php
namespace App\Models;

class Response {
    public int $status;
    public string $message;
    public array $data;
    public int $draw;
    public int $iTotalDisplayRecords;
    public int $iTotalRecords;

    public function __construct() {
        $this -> status = 400;
        $this -> message = 'Error inesperado';
        $this -> data = [];
    }
    public function toJson(): string {
        $json = json_encode($this, JSON_PRETTY_PRINT);
        return $json;
    }

    public function toArray(): array {
        $string = json_encode($this);
        $array = json_decode($string, true);

        // try {
        //     $log = new Logs();
        //     $log->status = $this->status;
        //     $log->message = $this->message;
        //     $log->request=json_encode($request);
        //     $log->response = json_encode($this);
        //     $log->save();
        // } catch (\Throwable $th) { }

        return $array;
    }

    public function setError(\Throwable $e) {
        $this->status = 400;
        $this->message = $e -> getMessage();
    }

    public function getStatus(): int {
        return $this->status;
    }
    public function setStatus(int $status): void {
        $this->status = $status;
    }
    public function getMessage(): string {
        return $this->message;
    }
    public function setMessage(string $message): void {
        $this->message = $message;
    }
    public function getData(): array {
        return $this->data;
    }
    public function setData(array $data): void {
        $this->data = $data;
    }
    public function getDraw(): int {
        return $this->draw;
    }
    public function setDraw(int $draw): void {
        $this->draw = $draw;
    }
    public function getiTotalDisplayRecords(): int {
        return $this->iTotalDisplayRecords;
    }
    public function setiTotalDisplayRecords(int $iTotalDisplayRecords): void {
        $this->iTotalDisplayRecords = $iTotalDisplayRecords;
    }
    public function getiTotalRecords(): int {
        return $this->iTotalRecords;
    }
    public function setiTotalRecords(int $iTotalRecords): void {
        $this->iTotalRecords = $iTotalRecords;
    }
}
