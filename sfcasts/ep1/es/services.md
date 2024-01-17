# Objetos de servicio

Veo a Symfony como dos grandes partes. La primera parte es el sistema de ruta, controlador y respuesta. Es muy simple y bueno... ¡ya eres un experto en ello! La segunda mitad de Symfony se trata de los muchos objetos útiles que están flotando por ahí... ¡sólo esperando a que los usemos!

## Objetos de servicio Hola

Por ejemplo, cuando renderizamos una plantilla, lo que estamos haciendo en realidad es aprovechar un objeto Twig y pedirle que renderice una plantilla. También hay un objeto registrador, un objeto caché, un objeto de conexión a la base de datos, un objeto que ayuda a hacer peticiones a la API, ¡y muchos, muchos más! Y cuando instalas un nuevo paquete, eso te da aún más objetos útiles.

La verdad es que todo lo que hace Symfony lo hace... uno de estos objetos útiles. Diablos, ¡hay incluso un objeto router que se encarga de encontrar la ruta adecuada para la página dada!

En el mundo de Symfony, y realmente en el mundo de la programación orientada a objetos en general, estos "objetos que hacen trabajo" tienen un nombre especial: servicios. Pero no dejes que esa palabra te confunda. Cuando oigas servicio, piensa: ¡es un objeto que hace trabajo! Como un objeto de plantilla que representa una plantilla o un objeto de conexión a la base de datos que realiza consultas.

Y como los objetos de servicio hacen trabajo, son básicamente... ¡herramientas que te ayudan a hacer tu trabajo! La segunda mitad de Symfony consiste en descubrir qué servicios están disponibles y cómo utilizarlos.

 El comando debug:autowiring

Vamos a probar algo. En nuestro controlador, quiero registrar un mensaje... quizás algún mensaje de depuración. Como registrar un mensaje es un trabajo, lo hace un servicio. ¿Nuestra aplicación ya tiene un servicio de registro? Y si es así, ¿cómo lo conseguimos?

Para averiguarlo, ve a tu terminal y ejecuta otro comando `bin/console`:

```terminal
php bin/console debug:autowiring
```

Saluda a uno de los comandos más potentes de `bin/console`. Me encanta esta cosa! Esta lista todos los servicios que existen en nuestra aplicación. De acuerdo, en realidad no es la lista completa, pero esto muestra los servicios que probablemente necesites. Y aunque nuestra aplicación es pequeña, ¡hay muchas cosas aquí! Hay un servicio de sistema de archivos... y aquí abajo un servicio de caché. ¡Incluso hay un servicio Twig!

¿Hay un servicio de registro? Puedes mirar en esta lista... o puedes volver a ejecutar este comando y buscar la palabra log:

```terminal-silent
php bin/console debug:autowiring log
```

¡Excelente! Por ahora, ignora todo excepto la primera línea. Esta línea nos dice que hay un servicio de registro y que este objeto implementa una interfaz llamada `Psr\Log\LoggerInterface`.

## Obtención de un servicio mediante autoconexión

Vale, ¿y por qué nos ayuda saber eso? Porque si quieres un servicio, lo pides utilizando la sugerencia de tipo que se muestra en este comando. Se llama autoconexión.

Vamos a probarlo. Dirígete a nuestro controlador y añade un segundo argumento. En realidad, el orden de los argumentos no importa. Lo que importa es que el nuevo argumento se indique con `LoggerInterface`. Pulsaré el tabulador para autocompletarlo... para que PhpStorm añada la declaración de uso en la parte superior.

En este caso, el argumento puede llamarse como sea, como `$logger`. Cuando Symfony ve esta sugerencia de tipo, busca dentro de la lista `debug:autowiring`... y como hay una coincidencia, nos pasará el servicio de registro.

Así que ahora conocemos dos tipos diferentes de argumentos que podemos tener en el controlador: puedes tener un argumento cuyo nombre coincida con un comodín de la ruta o un argumento cuyo tipo-hint coincida con uno de los servicios de nuestra app.

## Utilizar el registrador

Bien, ahora que sabemos que Symfony nos pasará el objeto de servicio logger, ¡vamos a utilizarlo! No sé, todavía, qué métodos puedo llamar en él pero... si decimos`$logger->`... PhpStorm... ¡nos lo dice! ¡Ha sido fácil!

Voy a registrar algo en un nivel de prioridad `info()`. Digamos:

> Devolución de la respuesta de la API para la canción

Y luego el `$id`.

[[[ code('77f869a1d1') ]]]

En realidad, podemos hacer algo aún más genial con este servicio de registro. Añade `{song}` al mensaje... y añade un segundo argumento, que es una matriz de información extra que quieres adjuntar al mensaje de registro. Pasa `song`ajustado a `$id`. En un minuto, verás que el registrador imprimirá el id real en lugar de `{song}`.

[[[ code('c3b3132647') ]]]

En cualquier caso, este controlador es para nuestra ruta de la API. Así que vamos a refrescarlo. ¡Um... ok! Así que no hay error, ¡eso es bueno! ¿Pero ha funcionado? ¿Dónde se registra realmente el servicio de registro?

Averigüémoslo a continuación, aprendamos un truco para ver el perfilador incluso para las peticiones de la API y luego aprovechemos nuestro segundo servicio directamente.
