<?php

namespace App\Http\Classes;

use App\Models\AssistantMessages;
use SoDe\Extend\Tokenizer;

class Messages
{
    /**
     * Agregar un mensaje a la base de datos de mensajes del Asistente por user_id y tipo.
     *
     * @param string $user_id El ID de usuario debe ser proporcionado como un identificador
     * único para cada conjunto de mensajes.
     * @param string $message El contenido del mensaje a agregar.
     * @param string $type Opcionalmente, un tipo puede ser especificado para categorizar
     * los mensajes.
     *
     * @return void
     */
    static public function add(string $user_id, string $whatsapp_id, string $message, ?string $type = null): void
    {
        $_message = new AssistantMessages();
        $_message->_user = $user_id;
        $_message->message = $message;
        $_message->whatsapp_id = $whatsapp_id;
        $_message->type = $type;
        $_message->length = Tokenizer::tokens($message);
        $_message->save();
    }
}
