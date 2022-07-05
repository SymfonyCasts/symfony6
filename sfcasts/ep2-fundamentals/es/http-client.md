# El servicio cliente HTTP

Todavía no tenemos una base de datos... y eso lo dejaremos para un futuro tutorial. Pero para hacer las cosas un poco más divertidas, he creado un repositorio de GitHub - https://github.com/SymfonyCasts/vinyl-mixes - con un archivo `mixes.json` que contiene una base de datos falsa de mezclas de vinilos. Hagamos una petición HTTP desde nuestra aplicación Symfony a este archivo y usémoslo como nuestra base de datos temporal.

Entonces... ¿cómo podemos hacer peticiones HTTP en Symfony? Bueno, hacer una petición HTTP es un trabajo, y -dilo conmigo ahora- "El trabajo lo hace un servicio". Así que la siguiente pregunta es ¿Existe ya un servicio en nuestra aplicación que pueda hacer peticiones HTTP?

¡Averigüémoslo! Dirígete a tu terminal y ejecuta

```terminal
php bin/console debug:autowiring http
```

para buscar "http" en los servicios. Obtenemos un montón de resultados, pero... nada que parezca un cliente HTTP. Y, eso es correcto. Actualmente no hay ningún servicio en nuestra aplicación que pueda hacer peticiones HTTP.

## Instalar el componente HTTPClient

Pero podemos instalar otro paquete que nos proporcione uno. En tu terminal, escribe:

```terminal
composer require symfony/http-client
```

Pero, antes de ejecutarlo, quiero mostrarte de dónde viene este comando. Busca "symfony http client". Uno de los primeros resultados es la documentación de Symfony.com que enseña sobre un componente Cliente HTTP. Recuerda: Symfony es una colección de muchas bibliotecas diferentes, llamadas componentes. ¡Y éste nos ayuda a realizar peticiones HTTP!

Cerca de la parte superior, verás una sección llamada "Instalación", ¡y ahí está la línea de nuestro terminal!

De todos modos, si ejecutamos eso... ¡genial! Una vez que termine, prueba de nuevo el comando `debug:autowiring`:

```terminal-silent
php bin/console debug:autowiring http
```

Y... ¡aquí está! Justo en la parte inferior: `HttpClientInterface` que

> Proporciona métodos flexibles para solicitar recursos HTTP de forma sincrónica o
> de forma asíncrona.

## El FrameworkBundle superinteligente

¡Guau! ¡Acabamos de conseguir un nuevo servicio! Eso significa que debemos haber instalado un nuevo bundle, ¿verdad? Porque... ¿los bundles nos dan servicios? Bueno... ve a ver`config/bundles.php`:

[[[ code('31a0dbdbc7') ]]]

¡Woh! ¡Aquí no hay ningún bundle nuevo! Prueba a ejecutar

```terminal
git status
```

Sí... eso sólo instaló un paquete PHP normal. Dentro de `composer.json`, aquí está el nuevo paquete... Pero es sólo una "biblioteca": no un bundle.

[[[ code('d946582375') ]]]

Así que, normalmente, si instalas "sólo" una biblioteca PHP, te da clases PHP, pero no se engancha a Symfony para darte nuevos servicios. Lo que acabamos de ver es un truco especial que utilizan muchos de los componentes de Symfony. El bundle principal de nuestra aplicación es `framework-bundle`. De hecho, cuando empezamos nuestro proyecto, éste era el único bundle que teníamos. `framework-bundle` es inteligente. Cuando instalas un nuevo componente de Symfony -como el componente Cliente HTTP- ese bundle se da cuenta de la nueva biblioteca y añade automáticamente los servicios para ella.

Así que el nuevo servicio proviene de `framework-bundle`... que lo añade en cuanto detecta que el paquete `http-client` está instalado.

## Utilizar el servicio HttpClientInterface

De todos modos, es hora de utilizar el nuevo servicio. El tipo que necesitamos es `HttpClientInterface`. Dirígete a `VinylController.php` y, aquí arriba, en la acción `browse()`, autocablea `HttpClientInterface` y llamémoslo `$httpClient`:

[[[ code('f67b91f6c3') ]]]

Entonces, en lugar de llamar a `$this->getMixes()`, di `$response = $httpClient->`. 
Esto lista todos sus métodos... probablemente queramos `request()`. Pasa este `GET`... 
y luego pegaré la URL: puedes copiarla del bloque de código de esta página. 
Es un enlace directo al contenido del archivo `mixes.json`:

[[[ code('f33fdc893f') ]]]

¡Genial! Así que hacemos la petición y nos devuelve una respuesta que contiene los datos de `mixes.json`que vemos aquí. Afortunadamente, estos datos tienen todas las mismas claves que los antiguos datos que estábamos utilizando aquí abajo... así que deberíamos poder cambiarlos con mucha facilidad. Para obtener los datos mixtos de la respuesta, podemos decir `$mixes = $response->toArray()`:

[[[ code('b6660098c4') ]]]

¡un práctico método que decodifica los datos en JSON por nosotros!

¡Momento de la verdad! Muévete, actualiza y... ¡funciona! Ahora tenemos seis mezclas en la página. Y... ¡superguay! Apareció un nuevo icono en la barra de herramientas de depuración de la web: "Total de peticiones: 1". El servicio Cliente HTTP se engancha a la barra de herramientas de depuración web para añadir esto, lo cual es bastante impresionante. Si hacemos clic en él, podemos ver información sobre la petición y la respuesta. Eso me encanta.

Para celebrar que esto funciona, vuelve a girar y elimina el método `getMixes()`codificado:

[[[ code('fe38cc18e5') ]]]

El único problema que se me ocurre ahora es que, cada vez que alguien visita nuestra página, estamos haciendo una petición HTTP a la API de GitHub... ¡y las peticiones HTTP son lentas! Para empeorar las cosas, una vez que nuestro sitio se haga superpopular -lo que no tardará mucho- la API de GitHub probablemente empezará a limitarnos la velocidad.

Para solucionar esto, vamos a aprovechar otro servicio de Symfony: el servicio de caché.
