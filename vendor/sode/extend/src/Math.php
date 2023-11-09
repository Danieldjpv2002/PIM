<?php

namespace SoDe\Extend;

use Exception;

class Math
{

    public const PI      = 3.141592653589793;  // Valor de PI
    public const E       = 2.718281828459045;  // Número de Euler
    public const PHI     = 1.618033988749894;  // Proporción áurea
    public const LN2     = 0.6931471805599453; // Logaritmo natural de 2
    public const LN10    = 2.302585092994046;  // Logaritmo narutal de 10
    public const LOG2E   = 1.4426950408889634; // Logaritmo natural de 2e
    public const LOG10E  = 0.4342944819032518; // Logaritmo narutal de 10e
    public const SQRT1_2 = 0.7071067811865476; // Raiz de 1/2
    public const SQRT2   = 1.4142135623730951; // Raiz de 2

    /**
     * La función encuentra y retorna el valor mínimo de una lista
     * de parámetros.
     * 
     * @return int El valor mínimo entre los parámetros ingresados.
     */
    public static function min(...$args): int
    {
        $min = $args[0];
        foreach ($args as $number) {
            if ($number <= $min) {
                $min = $number;
            }
        }
        return $min;
    }

    /**
     * La función encuentra y retorna el valor máximo de una lista
     * de parámetros.
     * 
     * @return int El valor máximo entre los parámetros ingresados.
     */
    public static function max(...$args): int
    {
        $max = $args[0];
        foreach ($args as $number) {
            if ($number >= $max) {
                $max = $number;
            }
        }
        return $max;
    }

    /**
     * La función calcula el promedio de una lista de argumentos.
     * 
     * @return int El promedio de los parámetros ingresados.
     */
    public static function avg(...$args): int
    {
        $sum = 0;
        foreach ($args as $number) {
            $sum += $number;
        }
        return $sum / count($args);
    }

    /**
     * Redondea un número al número especificado de decimales.
     *
     * @param float $number El número que se va a redondear.
     * @param int $decimals El número de decimales a los que se debe redondear (predeterminado: 0).
     *
     * @return float El número redondeado al número especificado de decimales.
     */
    public static function round(float $number, int $decimals = 0): float
    {
        $multiplier = 10 ** $decimals;
        return round($number * $multiplier) / $multiplier;
    }

    /**
     * Redondea hacia abajo un número al entero más cercano o al número especificado de decimales.
     *
     * @param float $number El número que se va a redondear hacia abajo.
     * @param int $decimals El número de decimales a los que se debe redondear (predeterminado: 0).
     *
     * @return float El número redondeado hacia abajo.
     */
    public static function floor(float $number, int $decimals = 0): float
    {
        $multiplier = 10 ** $decimals;
        return floor($number * $multiplier) / $multiplier;
    }

    /**
     * Redondea hacia arriba un número al entero más cercano o al número especificado de decimales.
     *
     * @param float $number El número que se va a redondear hacia arriba.
     * @param int $decimals El número de decimales a los que se debe redondear (predeterminado: 0).
     *
     * @return float El número redondeado hacia arriba.
     */
    public static function ceil(float $number, int $decimals = 0): float
    {
        $multiplier = 10 ** $decimals;
        return ceil($number * $multiplier) / $multiplier;
    }

    /**
     * Devuelve los números más altos de una matriz en orden descendente.
     *
     * @param array $numbers Una matriz de números de la que queremos encontrar los valores más altos.
     * @param int $quantity El número de valores más altos para devolver de la matriz.
     *
     * @return array Una matriz de los números más altos de la matriz de entrada en orden descendente.
     */
    public static function highs(array $numbers, int $quantity): array
    {
        rsort($numbers);
        return array_slice($numbers, 0, $quantity);
    }

    /**
     * Devuelve los números más bajos de una matriz en orden ascendente.
     *
     * @param array $numbers Una matriz de números de la que queremos encontrar los valores más bajos.
     * @param int $quantity El número de valores más bajos para devolver de la matriz.
     *
     * @return array Una matriz de los números más bajos de la matriz de entrada en orden ascendente.
     */
    public static function lows(array $numbers, int $quantity): array
    {
        sort($numbers);
        return array_slice($numbers, 0, $quantity);
    }

    /**
     * Calcula la suma de una lista de números.
     *
     * @param array $numbers Una matriz de números para sumar.
     *
     * @return int La suma de los números en la matriz.
     */
    public static function sum(array $numbers): int
    {
        return array_sum($numbers);
    }

    /**
     * Calcula el factorial de un número.
     *
     * @param int $number El número para calcular el factorial.
     *
     * @return int El factorial del número.
     * @throws Exception Si el número es negativo.
     */
    public static function factorial(int $number): int
    {
        if ($number < 0) {
            throw new Exception('El número debe ser no negativo.');
        }

        $factorial = 1;
        for ($i = 2; $i <= $number; $i++) {
            $factorial *= $i;
        }

        return $factorial;
    }

    /**
     * Calcula el exponente de un número elevado a una potencia.
     *
     * @param float $base El número base.
     * @param float $exponent El exponente al que se eleva el número base.
     *
     * @return float El resultado de elevar el número base al exponente.
     */
    public static function pow(float $base, float $exponent): float
    {
        return pow($base, $exponent);
    }

    /**
     * Calcula el valor absoluto de un número.
     *
     * @param int|float $number El número para calcular el valor absoluto.
     *
     * @return int|float El valor absoluto del número.
     */
    public static function abs(int|float $number): int|float
    {
        return abs($number);
    }

    /**
     * Genera un número aleatorio dentro de un rango dado.
     *
     * @param int|float $start El límite inferior del rango.
     * @param int|float $end El límite superior del rango.
     * @param bool $isInteger Indica si el número aleatorio debe ser entero (predeterminado: true).
     *
     * @return int|float El número aleatorio generado.
     * @throws Exception Si los límites del rango no son válidos (si $start es mayor que $end).
     */
    public static function random($start, $end, $isInteger = true)
    {
        if ($start > $end) {
            throw new Exception('Los límites del rango no son válidos. El límite inferior debe ser menor o igual al límite superior.');
        }
        $random = mt_rand() / mt_getrandmax();
        $result = $start + ($random * ($end - $start));
        if ($isInteger) return round($result);
        return $result;
    }
}
