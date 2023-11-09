<?php

namespace App\Templates;

use SoDe\Extend\File;
use SoDe\Extend\Trace;

/**
 * Clase para crear un mensaje para un juego de roles.
 */
class RolTemplate
{
    private ?string $content = null;            // El contenido del mensaje.
    public ?string $owner = null;               // El nombre del propietario del juego.
    public ?string $owner_birthdate = null;     // La fecha de nacimiento del propietario.
    public ?string $name = null;                // Nombre del asistente del juego.
    public ?string $location = null;            // Pais del propietario.
    public ?string $rol = null;                 // El rol del asistente del juego.
    public ?string $personality = null;         // La personalidad esperada del asistente.
    public ?string $language = null;            // El idioma[es] que el asistente debe hablar.
    public ?string $rules = null;               // Las reglas a seguir durante el juego.
    public ?string $message = null;             // El mensaje inicial del juego.

    /**
     * Constructor de la clase para darle el contenido base al mensaje y establecer las variables por defecto.
     */
    function __construct()
    {
        $template = File::get('../storage/templates/rol.template.txt');
        $this->content = $template;
    }

    /**
     * Obtener el contenido generado para el juego.
     *
     * @return string
     */
    public function get(): string
    {
        return str_replace(
            [
                '{owner}',
                '{owner_birthdate}',
                '{name}',
                '{location}',
                '{rol}',
                '{personality}',
                '{rules}',
                '{language}',
                '{message}',
                '{datetime}'
            ],
            [
                $this->owner,
                $this->owner_birthdate,
                $this->name,
                $this->location,
                $this->rol,
                $this->personality,
                $this->rules,
                $this->language,
                $this->message,
                Trace::getDate('mysql')
            ],
            $this->content
        );
    }
}
