<?php

namespace SoDe\Extend;

class Trace
{
    static private $mounths = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

    /**
     * Esta función genera un identificador único formado por fecha
     * y hora.
     * 
     * @return string Regresa un identificador único foramdo por la
     * fecha y hora actual.
     */
    static public function getId(): string
    {
        date_default_timezone_set('America/Lima');
        return date('YmdHisu');
    }

    /**
     * Obtiene la fecha y hora actual en el formato especificado.
     * 
     * @param string $format El formato que se desea devolver.
     * Puede ser: 'mysql', 'iso', 'gpt' o 'default'.
     * 
     * @return string La fecha y hora en el formato especificado.
     */
    static public function getDate(string $format = 'iso'): string
    {
        date_default_timezone_set('America/Lima');
        setlocale(LC_TIME, 'es_ES.utf8');
        switch ($format) {
            case 'date':
                return date('Y-m-d');
                break;
            case 'time':
                return date('H:i:s');
                break;
            case 'mysql':
                return date('Y-m-d H:i:s');
                break;
            case 'iso':
                return date('Y-m-d\TH:i:s\Z');
                break;
            case 'gpt':
                return date('l, F d Y H:i:s');
                break;
            default:
                return date('Y-m-d H:i:s.u');
                break;
        }
    }
    
    /**
     * La función `formato` es una función pública estática que toma
     * un parámetro de cadena `` que representa el formato de fecha
     * deseado y devuelve una cadena que representa la fecha y la hora
     * actuales formateadas según el formato especificado. Utiliza la
     * función `fecha` de PHP para formatear la fecha y la hora.
     */
    static public function format(string $format): string
    {
        return date($format);
    }
    
    /**
     * La función `mes` es una función pública estática que toma un
     * parámetro de cadena opcional `` que representa el índice del
     * mes a recuperar (1 para enero, 2 para febrero, etc.). Si se
     * proporciona ``, la función devuelve el nombre del mes
     * correspondiente a ese índice de la matriz privada estática ``.
     * Si no se proporciona ``, la función devuelve el nombre del
     * mes actual en función de la fecha y la hora actuales mediante
     * la función `fecha` de PHP y la matriz ``.
     */
    static public function month(string $index = ''): string
    {
        if ($index != '') {
            return Trace::$mounths[intval($index)];
        }
        return Trace::$mounths[date('n') - 1];
    }
}
