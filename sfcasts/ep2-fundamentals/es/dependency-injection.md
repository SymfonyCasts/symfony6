# Inyección de dependencia

Nuestro servicio `MixRepository` está más o menos funcionando. Podemos autoinstalarlo en nuestro controlador y el contenedor está instanciando el objeto y pasándonoslo. Lo comprobamos aquí porque, cuando ejecutamos el código, llama con éxito al método `findAll()`.

Pero .... luego explota. Eso es porque, dentro de `MixRepository` tenemos dos variables indefinidas. Para que nuestra clase haga su trabajo, necesita dos servicios: el servicio`$cache` y el servicio `$httpClient`.

## La autoconexión con los métodos es un superpoder exclusivo de los controladores

Sigo diciendo que hay muchos servicios flotando dentro de Symfony, esperando que los usemos. Eso es cierto. Pero, no puedes cogerlos de la nada desde cualquier parte de tu código. Por ejemplo, no hay ningún método estático de `Cache::get()` que puedas llamar cuando quieras y que devuelva el objeto de servicio `$cache`. No existe nada así en Symfony. ¡Y eso es bueno! Permitirnos coger objetos de la nada es una receta para escribir mal código.

Entonces, ¿cómo podemos acceder a estos servicios? Actualmente, sólo conocemos una forma: autocableándolos en nuestro controlador. Pero eso no funcionará aquí. El autocableado de servicios en un método es un superpoder que sólo funciona para los controladores.

Fíjate: si añadimos un argumento `CacheInterface`... y luego pasamos y refrescamos, veríamos:

> Demasiados argumentos para la función [...]findAll(), 0 pasados [...] y exactamente 1 esperado.

Eso es porque estamos llamando a `findAll()`. Así que si `findAll()` necesita un argumento, es nuestra responsabilidad pasarlo: no hay magia de Symfony. Lo que quiero decir es que el autocableado funciona en los métodos del controlador, pero no esperes que funcione en ningún otro método.

## ¿Pasar manualmente los servicios a un método?

Una forma de conseguir que esto funcione es añadir ambos servicios al método`findAll()` y luego pasarlos manualmente desde el controlador. Esta no será la solución definitiva, pero vamos a probarla.

Ya tengo un argumento `CacheInterface`... así que ahora añade el argumento`HttpClientInterface` y llámalo `$httpClient`.

¡Perfecto! El código de este método está ahora contento.

De vuelta a nuestro controlador, para `findAll()`, pasa `$httpClient` y `$cache`.

Y ahora... ¡funciona!

## "Dependencias" frente a "Argumentos"

Así que, a alto nivel, esta solución tiene sentido. Sabemos que podemos autoconectar servicios en nuestro controlador... y luego simplemente los pasamos a `MixRepository`. Pero si piensas un poco más en profundidad, los servicios `$httpClient` y `$cache` no son realmente una entrada para la función `findAll()`. No tienen realmente sentido como argumentos.

Veamos un ejemplo. Imagina que decidimos cambiar el método `findAll()` para que acepte un argumento `string $genre` para que el método sólo devuelva mezclas de ese género. Este argumento tiene mucho sentido: al pasar diferentes géneros cambia lo que devuelve. El argumento controla el comportamiento del método.

Pero los argumentos `$httpClient` y `$cache` no controlan el comportamiento de la función. En realidad, pasaríamos estos dos mismos valores cada vez que llamemos al método... para que las cosas funcionen.

En lugar de argumentos, son realmente dependencias que el servicio necesita. ¡Son cosas que deben estar disponibles para que `findAll()` pueda hacer su trabajo!

## Inyección de dependencias y el constructor

Para las "dependencias" como ésta, ya sean objetos de servicio o configuración estática que necesita tu servicio, en lugar de pasarlas a los métodos, las pasamos al constructor. Elimina ese supuesto argumento de `$genre`... y añade un `public function __construct()`. Copia los dos argumentos, bórralos y muévelos hasta aquí.

Antes de terminar esto, tengo que decirte que el autocableado funciona en dos sitios. Ya sabemos que podemos autoconectar argumentos en los métodos de nuestro controlador. Pero también podemos autoconectar argumentos en el método `__construct()` de cualquier servicio. De hecho, ¡éste es el lugar principal en el que se supone que funciona la autoconexión! El hecho de que la autoconexión también funcione en los métodos del controlador es... una especie de "extra" para hacer la vida más agradable.

En cualquier caso, la autoconexión funciona en el método `__construct()` de nuestros servicios. Así que, siempre que indiquemos los argumentos (y lo hemos hecho), cuando Symfony instancie nuestro servicio, nos pasará estos dos servicios. ¡Sí!

¿Y qué hacemos con estos dos argumentos? Los establecemos en propiedades.

Creamos una propiedad `private $httpClient` y una propiedad `private $cache`. Luego, abajo, en el constructor, les asignamos: `$this->httpClient = $httpClient`, y`$this->cache = $cache`.

Así, cuando Symfony instancie nuestro `MixRepository`, nos pasará estos dos argumentos y los almacenaremos en propiedades para poder utilizarlos después.

¡Observa! Aquí abajo, en lugar de `$cache`, utiliza `$this->cache`. Y entonces no necesitamos este `use ($httpClient)` de aquí... porque podemos decir `$this->httpClient`.

Este servicio está ahora en perfecto estado.

De vuelta a `VinylController`, ¡ahora podemos simplificar! El método `findAll()`no necesita ningún argumento... y así ni siquiera necesitamos autoconducir`$httpClient` o `$cache` en absoluto. Voy a celebrarlo eliminando esas declaraciones `use`de la parte superior.

¡Mira qué fácil es! Autocableamos el único servicio que necesitamos, llamamos al método en él, y... ¡hasta funciona! Así es como escribimos servicios. Añadimos las dependencias al constructor, las establecemos en las propiedades y luego las utilizamos.

## ¡Hola a la inyección de dependencias!

Por cierto, lo que acabamos de hacer tiene un nombre extravagante: "Inyección de dependencias", ¡pero no huyas! Puede que sea un término que asuste... o que al menos suene "aburrido", pero es un concepto muy sencillo.

Cuando estás dentro de un servicio como `MixRepository` y te das cuenta de que necesitas otro servicio (o tal vez alguna configuración como una clave de API), para obtenerlo, crea un constructor, añade un argumento para lo que necesitas, ponlo en una propiedad, y luego úsalo abajo en tu código. Sí Eso es la inyección de dependencia.

En pocas palabras, la inyección de dependencia dice

> Si necesitas algo, en lugar de cogerlo de la nada, obliga a Symfony a
> te lo pase a través del constructor.

Este es uno de los conceptos más importantes de Symfony... y lo haremos una y otra vez.

## Promoción de propiedades en PHP 8

Bien, sin relación con la inyección de dependencia y el autocableado, hay dos pequeñas mejoras que podemos hacer a nuestro servicio. La primera es que podemos añadir tipos a nuestras propiedades: `HttpClientInterface` y `CacheInterface`. Eso no cambia el funcionamiento de nuestro código... es sólo una forma agradable y responsable de hacer las cosas.

¡Pero podemos ir más allá! En PHP 8, hay una nueva sintaxis más corta para crear una propiedad y establecerla en el constructor como estamos haciendo. Tiene el siguiente aspecto. En primer lugar, moveré mis argumentos a varias líneas... sólo para mantener las cosas organizadas. Ahora añade la palabra `private` delante de cada argumento. Termina borrando las propiedades... así como el interior del método.

Esto puede parecer raro al principio, pero en cuanto añades `private`, `protected`, o`public` delante de un argumento `__construct()`, se crea una propiedad con este nombre y se fija el argumento en esa propiedad. Así que parece diferente, pero es exactamente lo mismo que teníamos antes.

Cuando lo probamos... ¡sí! Sigue funcionando.

Siguiente: Sigo diciendo que el contenedor contiene servicios. Es cierto Pero también contiene otra cosa: una simple configuración llamada "parámetros".
