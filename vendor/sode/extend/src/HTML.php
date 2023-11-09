<?php

namespace SoDe\Extend;

/**
 * La clase HTML es una clase de PHP que permite una fácil creación y
 * representación de elementos HTML con atributos y contenido.
 * 
 * @Author SoDe World.
 * @Copyright Todos los derechos reservados.
 */
class HTML
{
    private string $tag;
    private array $attributes = [];
    private string $content = '';
    private bool $selfClosing = false;
    private array $selfClosingTags = ['input', 'img', 'br', 'hr', 'meta', 'link', 'area', 'base', 'col', 'embed', 'param', 'source', 'track'];

    /**
     * Esta es una función constructora en PHP que toma un parámetro de
     * cadena y lo asigna a una propiedad de clase.
     * 
     * @param string tag El parámetro "etiqueta" es una cadena que
     * representa la etiqueta HTML que se usará para crear un elemento
     * HTML. Este parámetro se usa en el constructor de una clase que
     * crea elementos HTML mediante programación.
     */
    public function __construct(string $tag)
    {
        $this->tag = $tag;
    }

    /**
     * Esta función de PHP establece un atributo con un nombre y valor
     * determinados y devuelve la instancia del objeto.
     * 
     * @param string name Una cadena que representa el nombre del
     * atributo que se agregará o actualizará.
     * @param string value El valor que se asignará al atributo con el
     * nombre especificado.
     * 
     * @return self El método `attr` devuelve la instancia actual de
     * la clase (``) después de establecer el atributo con el nombre
     * y el valor dados.
     */
    public function attr(string $name, string $value): self
    {
        $this->attributes[$name] = $value;
        return $this;
    }

    /**
     * Esta función de PHP establece el contenido de un objeto en una
     * cadena HTML determinada y devuelve el objeto.
     * 
     * @param html El parámetro "html" es una cadena que representa el
     * contenido HTML que se establecerá como contenido de un objeto.
     * La función devuelve el objeto en sí mismo para permitir el
     * encadenamiento de métodos.
     * 
     * @return self El método devuelve la instancia de objeto actual,
     * lo que permite el encadenamiento de métodos.
     */
    public function html($html): self
    {
        $this->content = $html;
        return $this;
    }

    /**
     * La función de "texto" en PHP toma una entrada de cadena, escapa
     * cualquier carácter especial y devuelve la instancia del objeto.
     * 
     * @param text El parámetro "texto" es una cadena que representa
     * el contenido que se agregará a un elemento HTML. El método de
     * "texto" es una función que toma esta cadena como argumento y
     * establece la propiedad de "contenido" del objeto del elemento
     * HTML en la versión codificada en HTML de la cadena usando
     * "htmlspecialchars"
     * 
     * @return self El método `text()` devuelve la instancia del objeto
     * actual (``) después de establecer la propiedad `content` en el
     * resultado de la función `htmlspecialchars()` aplicada al
     * parámetro ``.
     */
    public function text($text): self
    {
        $this->content = htmlspecialchars($text);
        return $this;
    }

    /**
     * Esta es una función de PHP que establece el atributo 'id' de un
     * objeto y devuelve el objeto en sí.
     * 
     * @param id El parámetro "id" es un valor que se pasa a la función.
     * Se utiliza para establecer el atributo "id" de un objeto y
     * devuelve el objeto en sí.
     * 
     * @return self El método devuelve la instancia de objeto actual (``)
     * después de establecer el atributo `id` en el valor proporcionado.
     */
    public function id($id): self
    {
        $this->attributes['id'] = $id;
        return $this;
    }

    /**
     * Esta función de PHP establece el valor de un atributo y devuelve
     * la instancia del objeto.
     * 
     * @param value El valor que se asignará al atributo "valor" de un
     * objeto.
     * 
     * @return self El método devuelve la instancia de objeto actual de
     * la clase, lo que permite el encadenamiento de métodos.
     */
    public function val($value): self
    {
        $this->attributes['value'] = $value;
        return $this;
    }

    /**
     * Esta función de PHP establece el atributo 'src' de un elemento
     * HTML y agrega un identificador único si se especifica.
     * 
     * @param src La URL de origen de un elemento HTML, como una imagen
     * o un archivo de script.
     * @param unique El parámetro "único" es un valor booleano que
     * determina si se debe agregar un identificador único al final
     * del valor del atributo "src". Si se establece en verdadero, se
     * agregará un identificador único al final del valor del atributo
     * "src" en forma de "?v=" seguido de una identificación única.
     * 
     * @return self la instancia de objeto actual de la clase (que está
     * representada por la palabra clave `self`) después de establecer
     * el atributo `src` del objeto en el valor de `` concatenado con
     * un identificador único (si `` es verdadero).
     */
    public function src($src, $unique = false): self
    {
        $this->attributes['src'] = $src . ($unique ? '?v=' . uniqid() : '');
        return $this;
    }

    /**
     * Esta función de PHP agrega un identificador único al final de un
     * atributo href determinado si el parámetro único se establece en
     * verdadero.
     * 
     * @param href La URL a la que debe apuntar el hipervínculo.
     * @param unique Un parámetro booleano que determina si agregar un
     * identificador único al final de la URL href. Si se establece en
     * verdadero, agregará una cadena de consulta con un identificador
     * único generado por la función uniqid(). Si se establece en falso,
     * no agregará ninguna cadena de consulta a la URL de href.
     * 
     * @return self la instancia de objeto actual de la clase, que
     * permite el encadenamiento de métodos.
     */
    public function href($href, $unique = false): self
    {
        $this->attributes['href'] = $href . ($unique ? '?v=' . uniqid() : '');
        return $this;
    }

    /**
     * Esta función de PHP establece el atributo de cierre automático
     * de una etiqueta HTML.
     * 
     * @param bool selfClosing Un parámetro booleano que determina si
     * la etiqueta HTML debe cerrarse automáticamente o no. Si se
     * establece en verdadero, la etiqueta se cerrará automáticamente;
     * de ​​lo contrario, no se cerrará automáticamente.
     * 
     * @return self El método devuelve la instancia de objeto actual
     * (de la clase que contiene este método) utilizando la palabra
     * clave `self`.
     */
    public function selfClosing(bool $selfClosing = true): self
    {
        $this->selfClosing = $selfClosing;
        return $this;
    }

    /**
     * Esta función de PHP representa etiquetas HTML con atributos y
     * contenido, y puede manejar etiquetas de cierre automático.
     */
    public function render(): void
    {
        $attributes = [];
        foreach ($this->attributes as $name => $value) {
            $attributes[] = "{$name}=\"{$value}\"";
        }
        $attributes_str = implode(' ', $attributes);

        if ($this->selfClosing || in_array($this->tag, $this->selfClosingTags)) {
            echo "<{$this->tag} {$attributes_str} />";
        } else {
            echo "<{$this->tag} {$attributes_str}>{$this->content}</{$this->tag}>";
        }
    }

    /**
     * Esta función de PHP devuelve una representación de cadena de una
     * etiqueta HTML con sus atributos y contenido.
     * 
     * @return string El método `__toString()` devuelve una representación
     * de cadena de un elemento HTML en función de su etiqueta, atributos
     * y contenido. Si el elemento es una etiqueta de cierre automático
     * o una de las etiquetas de cierre automático predefinidas, devuelve
     * una cadena con la etiqueta y los atributos seguidos de una barra
     * diagonal. De lo contrario, devuelve una cadena con la etiqueta
     * de apertura, los atributos, el contenido y la etiqueta de cierre.
     */
    public function __toString(): string
    {
        $attributes = [];
        foreach ($this->attributes as $name => $value) {
            $attributes[] = "{$name}=\"{$value}\"";
        }
        $attributes_str = implode(' ', $attributes);

        if ($this->selfClosing || in_array($this->tag, $this->selfClosingTags)) {
            return "<{$this->tag} {$attributes_str} />";
        } else {
            return "<{$this->tag} {$attributes_str}>{$this->content}</{$this->tag}>";
        }
    }

    
    /**
     * Esta función de PHP convierte el código HTML en una imagen usando
     * HTML/CSS to Image API.
     * 
     * @param string html Una cadena que contiene el código HTML que se
     * convertirá en una imagen.
     * @param string type El tipo de salida deseada, ya sea 'url',
     * 'base64' o 'blob'.
     * 
     * @return string una cadena que representa una URL o una imagen
     * codificada en base64, según el valor del parámetro `type`. Si
     * `type` es `'url'`, la función devuelve una URL que apunta a una
     * imagen generada a partir del HTML de entrada. Si `type` es
     * `'base64'' o `'blob'`, la función devuelve una imagen codificada
     * en base64 o una imagen binaria.
     */
    public static function toImage(string $html, string $type = 'url'): string
    {
        $body = [
            'html' => $html,
            'render_when_ready' => false,
            'device_scale' => 3
        ];
        $res = new Fetch("https://htmlcsstoimage.com/demo_run", [
            'method' => 'POST',
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ],
            'body' => $body
        ]);

        $data = JSON::parseable($res->text());
        $image = 'https://sode.me/img/banner/sode.banner.png';

        if ($type == 'base64' || $type == 'blob') {
            if ($res->ok) $image = $data['url'];
            $res_bin = new Fetch($image);
            $data_bin = $res_bin->blob();
            if ($type == 'blob') return $data_bin;
            return 'data:image/png;base64,' . base64_encode($data_bin);
        } else {
            if (!$res->ok) return $image;
            else return $data['url'];
        }
    }
}