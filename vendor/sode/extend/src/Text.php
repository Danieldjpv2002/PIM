<?php

namespace SoDe\Extend;

class Text
{
    static private string $lineBreak = '
';
    /**
     * Verifica si un String comienza con un caracter en especifico.
     *
     * @param string $string El String con el que se va a realizar
     * la verificación.
     * @param string $needle El caracter con el que se va a realizar
     * la comparación.
     * 
     * @return bool Un valor booleano capaz de representar si el
     * string comienza con el caracter especificado.
     */
    static public function startsWith($string, $needle): bool
    {
        return strpos($string, $needle) === 0;
    }

    /**
     * Esta función limpia los retornos de línea en una cadena dada.
     *
     * @param string $text La cadena que será limpiada.
     * 
     * @return string Una cadena limpia de retornos de línea.
     */
    static public function cleanLineBreak(string $text): string
    {
        $text = str_replace(' ', ' ', $text);
        $text = trim($text, '\\n');
        $text = trim($text, '\n');
        $text = trim($text, Text::$lineBreak);
        $text = trim($text);
        $text = preg_replace('/^\s+|\s+$/m', '', $text);
        return $text;
    }

    /** 
     * Esta función devuelve una cadena con un salto de línea.
     * 
     * @return string un salto de línea
     */
    static public function lineBreak(?int $repeat = 1): string
    {
        return str_repeat(Text::$lineBreak, $repeat);
    }

    /**
     * Separa una cadena de texto en palabras o partes según el separador.
     *
     * @param string $text La cadena a separar.
     * @param string $separator El carácter separador para los elementos
     * del array. (Opcional, por defecto es un espacio en blanco).
     *
     * @return array Un array con las cadenas separadas.
     */
    static public function split(string $text, string $separator = ' '): array
    {
        return explode($separator, $text);
    }

    /**
     * La función "keep" filtra una cadena para incluir solo caracteres
     * específicos y elimina cualquier espacio en blanco.
     * 
     * @param string string La cadena de entrada que debe filtrarse y
     * devolverse.
     * @param string characters El parámetro de caracteres es una cadena
     * que contiene todos los caracteres que deben mantenerse en la cadena
     * de entrada. Todos los demás personajes serán eliminados.
     * 
     * @return string una cadena que contiene solo los caracteres
     * especificados en el parámetro ``, con cualquier carácter que no
     * coincida eliminado. Además, se eliminan los caracteres de espacio
     * en blanco y las palabras restantes se concatenan con un solo
     * carácter de espacio entre ellas.
     */
    public static function keep(string $string, string $characters): string
    {
        $regex = '/[^' . preg_quote($characters, '/') . ']/';
        $filteredString = preg_replace($regex, '', $string);
        $wordsArray = preg_split('/\s+/', $filteredString);
        $filteredArray = array_filter($wordsArray, 'strlen');
        $result = implode(' ', $filteredArray);
        return $result;
    }

    /**
     * La función coincide con un patrón de expresión regular en una
     * cadena determinada y devuelve el resultado junto con el texto
     * restante.
     * 
     * @param string text La cadena de entrada que debe compararse con la
     * expresión regular.
     * @param string regex El patrón de expresión regular a buscar en el
     * texto dado. Se establece en '/{{(.+?)}}/' de forma predeterminada,
     * que coincide con cualquier texto encerrado entre llaves dobles.
     * 
     * @return An array is being returned with three elements:
     */
    public static function match(string $text, string $regex = '/{{(.+?)}}/')
    {
        try {
            $matches = [];

            $found = preg_match($regex, $text, $matches);
            $clean_text = str_replace($matches[0], '', $text);

            return [$found, $matches[1], $clean_text];
        } catch (\Throwable $th) {
            return [false, '', $text];
        }
    }

    /**
     * La función reduce una cadena dada a un número específico de
     * caracteres y agrega puntos suspensivos si la cadena es más larga
     * que el número especificado de caracteres.
     * 
     * @param string string Una cadena de caracteres que debe reducirse
     * en longitud.
     * @param int chars El número máximo de caracteres al que debe
     * reducirse la cadena.
     * 
     * @return string una cadena que es la cadena original pasada o una
     * versión abreviada de la misma con puntos suspensivos agregados al
     * final si la cadena original era más larga que el número especificado
     * de caracteres.
     */
    public static function reduce(string $string, int $chars): string
    {
        $text = strval($string);
        if (strlen($text) > $chars) {
            $text = substr($text, 0, $chars - 3) . "...";
        }
        return $text;
    }

    /**
     * La función comprueba si una cadena contiene una subcadena
     * específica y devuelve un valor booleano.
     * 
     * @param string string El primer parámetro es una variable de cadena
     * llamada `string` que representa la cadena en la que queremos buscar
     * el segundo parámetro.
     * @param string needle El parámetro "aguja" es una cadena que estamos
     * buscando dentro de otra cadena. Es la subcadena que queremos
     * verificar si existe dentro de la cadena principal.
     * 
     * @return bool Un valor booleano que indica si la cadena `needle` se
     * encuentra dentro de la cadena `string`.
     */
    public static function has(string $string, string $needle): bool
    {
        return str_contains($string, $needle);
    }

    /**
     * Esta es una función de PHP que verifica si una cadena contiene
     * alguna de las cadenas en una lista dada.
     * 
     * @param string string Una cadena en la que queremos verificar la
     * presencia de ciertas subcadenas.
     * @param array needle_list Una matriz de cadenas que representan las
     * cadenas para buscar en el parámetro.
     * 
     * @return bool un valor booleano, ya sea verdadero o falso.
     */
    public static function hasOne(string $string, array $needle_list): bool
    {
        foreach ($needle_list as $needle) {
            if (str_contains($string, $needle)) return true;
        }
        return false;
    }

    /**
     * La función verifica si una cadena dada contiene todas las subcadenas
     * en una lista dada.
     * 
     * @param string string Una cadena que queremos verificar si contiene
     * todas las subcadenas en la lista de subcadenas.
     * @param array needle_list Una matriz de cadenas que se buscan en
     * el parámetro.
     * 
     * @return bool Se devuelve un valor booleano, ya sea verdadero o falso.
     */
    public static function hasAll(string $string, array $needle_list): bool
    {
        foreach ($needle_list as $needle) {
            if (!str_contains($string, $needle)) return false;
        }
        return true;
    }

    /**
     * Esta función comprueba si una cadena determinada es nula o
     * está vacía.
     * 
     * @param string El parámetro llamado "string" es un tipo de
     * string anulable, indicado por el "?" antes de la palabra
     * clave "string". Esto significa que puede ser una string o
     * un valor nulo.
     * 
     * @return bool un valor booleano. Si la cadena de entrada es
     * nula o está vacía, devolverá verdadero. De lo contrario,
     * devolverá falso.
     */
    public static function nullOrEmpty(?string $string): bool
    {
        if (!isset($string) || $string == null || trim($string) == '') {
            return true;
        }
        return false;
    }

    public static function toTitleCase(string $string, bool $capitalizeSingleWords = true): string
    {
        $string = mb_strtolower($string);
        $result = preg_replace_callback('/(\b\w|\.\s\w)/u', function ($matches) {
            return mb_strtoupper($matches[0]);
        }, $string);

        $result = preg_replace_callback('/(\w+)/u', function ($matches) use ($capitalizeSingleWords) {
            $word = $matches[0];
            if ($word === mb_strtoupper($word)) {
                return $word;
            }
            return ($capitalizeSingleWords || mb_strlen($word) > 1) ? ucfirst($word) : mb_strtolower($word);
        }, $result);

        return trim($result);
    }

    public static function fillObject(string $string, array $object): string
    {
        $flattened = JSON::flatten($object);
        foreach ($flattened as $key => $value) {
            $string = str_replace('{{' . $key . '}}', $value, $string);
        }
        return $string;
    }
}
