# Sode/Extend

[![Latest Stable Version](https://poser.pugx.org/sode/extend/v)](//packagist.org/packages/sode/extend)
[![License](https://poser.pugx.org/sode/extend/license)](//packagist.org/packages/sode/extend)

## Descripción

Sode/Extend es un paquete de PHP que proporciona clases extendidas para el manejo rápido de datos.

## Instalación

Puedes instalar este paquete utilizando Composer. Asegúrate de tener Composer instalado en tu proyecto y luego ejecuta el siguiente comando:

```bash
composer require sode/extend
```

## Uso

Para utilizar las clases de Sode/Extend, simplemente importa la clase que necesitas y úsala en tu código. Aquí tienes una descripción de algunos de los paquetes disponibles:

## Crypto

El paquete Crypto ofrece métodos para generar caracteres aleatorios. Puedes utilizar el método `short()` para generar 8 caracteres aleatorios y devolverlos como una cadena. También puedes utilizar el método `randomUUID()` para generar un UUID con caracteres aleatorios.

## Fetch

Fetch es un análogo a la función Fetch de JavaScript, pero escrita en PHP. Puedes instanciarlo utilizando `new Fetch($url, $options = [method => string, headers => array, body => array])`. Esto te permite realizar solicitudes HTTP en tu código PHP.

## File

El paquete File proporciona métodos para trabajar con extensiones de archivos y tipos MIME. Puedes utilizar el método `getExtension()` para obtener la extensión de un archivo según su tipo MIME, y utilizar el método `getMimeType()` para obtener el tipo/subtipo MIME de una extensión de archivo.

## HTML

El paquete HTML te permite crear etiquetas HTML desde una clase de PHP. Puedes instanciarlo utilizando `new HTML($tag)`, y luego agregar atributos, valores, texto, etc., utilizando métodos. Además, el paquete ofrece el método `toImage()` que convierte una cadena HTML en una imagen. Puedes utilizarlo de la siguiente manera: `HTML::toImage($html, $type: url|base64|blob)`.

## JSON

El paquete JSON proporciona métodos estáticos `parse()` y `stringify()` para trabajar con JSON. Puedes utilizar `parse()` para analizar una cadena JSON y obtener un objeto PHP, y utilizar `stringify()` para convertir un objeto PHP en una cadena JSON.

## Status

El paquete Status ofrece el método estático `get()` que recibe un número entero y devuelve un código de estado HTTP correspondiente. Si el código no se encuentra, se devuelve un entero con el valor 500.

## Text

El paquete Text proporciona métodos para el manejo rápido de texto. Algunos de los métodos disponibles son:

- `startsWith($string, $needle)`: Verifica si una cadena comienza con otra cadena específica.
- `cleanLineBreak($string)`: Elimina los saltos de línea al comienzo y al final de una cadena.
- `lineBreak($int)`: Devuelve una cadena con un número especificado de saltos de línea.
- `split($text, $sep)`: Divide una cadena en un array utilizando un separador.
- `keep($string, $chars)`: Mantiene solo los caracteres especificados en una cadena.
- `reduce($string, $chars)`:

# Math

La clase `Math` proporciona una serie de funciones matemáticas útiles.

## Constantes

- `Math::PI`: Valor de PI.
- `Math::E`: Número de Euler.
- `Math::PHI`: Proporción áurea.
- `Math::LN2`: Logaritmo natural de 2.
- `Math::LN10`: Logaritmo natural de 10.
- `Math::LOG2E`: Logaritmo natural de 2e.
- `Math::LOG10E`: Logaritmo natural de 10e.
- `Math::SQRT1_2`: Raíz cuadrada de 1/2.
- `Math::SQRT2`: Raíz cuadrada de 2.

## Métodos

### `min(...$args): int`

Encuentra y devuelve el valor mínimo de una lista de parámetros.

### `max(...$args): int`

Encuentra y devuelve el valor máximo de una lista de parámetros.

### `avg(...$args): int`

Calcula el promedio de una lista de argumentos.

### `round(float $number, int $decimals = 0): float`

Redondea un número al número especificado de decimales.

### `floor(float $number, int $decimals = 0): float`

Redondea hacia abajo un número al entero más cercano o al número especificado de decimales.

### `ceil(float $number, int $decimals = 0): float`

Redondea hacia arriba un número al entero más cercano o al número especificado de decimales.

### `highs(array $numbers, int $quantity): array`

Devuelve los números más altos de una matriz en orden descendente.

### `lows(array $numbers, int $quantity): array`

Devuelve los números más bajos de una matriz en orden ascendente.

### `sum(array $numbers): int`

Calcula la suma de una lista de números.

### `factorial(int $number): int`

Calcula el factorial de un número.

### `pow(float $base, float $exponent): float`

Calcula el exponente de un número elevado a una potencia.

### `abs(int|float $number): int|float`

Calcula el valor absoluto de un número.

### `random(int|float $start, int|float $end, bool $isInteger = true): int|float`

Genera un número aleatorio dentro de un rango dado.

---

**Nota:** Asegúrate de importar la clase `Math` en tu archivo PHP antes de usar estas funciones.

```php
use SoDe\Extend\Math;

// Ejemplo de uso
$minValue = Math::min(3, 5, 1, 7); // Output: 1

$maxValue = Math::max(3, 5, 1, 7); // Output: 7

$average = Math::avg(2, 4, 6, 8); // Output: 5

$rounded = Math::round(3.14159, 2); // Output: 3.14

$floorValue = Math::floor(3.99); // Output: 3

$ceilValue = Math::ceil(3.01); // Output: 4

$highestNumbers = Math::highs([5, 9, 2, 7, 4], 3); // Output: [9, 7, 5]

$lowestNumbers = Math::lows([5, 9, 2, 7, 4], 3); // Output: [2, 4, 5]

$sum = Math::sum([1, 2, 3, 4, 5]); // Output: 15

$factorial = Math::factorial(5); // Output: 120

$exponent = Math::pow(2, 3); // Output: 8

$absoluteValue = Math::abs(-5); // Output: 5

$randomNumber = Math::random(0, 10); // Output: Un número aleatorio entre 0 y 10
```