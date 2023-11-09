<?php

namespace App\Http\Classes;

use App\Models\Traceability;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use SoDe\Extend\JSON;

class Logger implements ShouldQueue
{

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private string $message; // El mensaje que se va a registrar en el registro de seguimiento.
    private string $type; // El tipo de registro de seguimiento (info, error, warn).

    /**
     * Crea una nueva instancia del registrador de seguimiento.
     *
     * @param string $message El mensaje a registrar.
     * @param string $type El tipo de registro.
     */
    public function __construct($message, $type)
    {
        $this->message = $message;
        $this->type = $type;
    }

    /**
     * Procesa la lógica de registro de seguimiento.
     *
     * @return void
     */
    public function handle()
    {
        $logger = new Traceability();
        $logger->type = $this->type;
        $logger->message = $this->message;
        $logger->trace = JSON::stringify(debug_backtrace(1));
        $logger->save();
    }

    /**
     * Registra un mensaje de información en el registro de seguimiento.
     *
     * @param string $message El mensaje a registrar.
     * @return void
     */
    static public function info($message)
    {
        Logger::dispatch($message, 'info');
    }

    /**
     * Registra un mensaje de error en el registro de seguimiento.
     *
     * @param string $message El mensaje a registrar.
     * @return void
     */
    static public function error($message)
    {
        Logger::dispatch($message, 'error');
    }

    /**
     * Registra un mensaje de advertencia en el registro de seguimiento.
     *
     * @param string $message El mensaje a registrar.
     * @return void
     */
    static public function warn($message)
    {
        Logger::dispatch($message, 'warn');
    }
}
