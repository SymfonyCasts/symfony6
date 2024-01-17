# Comando: Autoconexión y preguntas interactivas

¡Equipo del último capítulo! ¡Vamos a hacerlo!

Vale, ¿y si necesitamos un servicio desde dentro de nuestro comando? Por ejemplo, digamos que queremos utilizar `MixRepository` para imprimir una recomendación de mezcla de vinilo. ¿Cómo podemos hacerlo?

Bueno, estamos dentro de un servicio y necesitamos acceder a otro servicio, lo que significa que necesitamos... la temida inyección de dependencia. Es broma, no es temible, ¡es fácil con el autocableado!

Añade `public function __construct()` con `private MixRepository $mixRepository`para crear y establecer esa propiedad de una sola vez.

[[[ code('628bac0c7d') ]]]

Aunque, si pasas el ratón por encima de `__construct()`, dice

> Falta una llamada al constructor padre.

Para solucionarlo, llama a `parent::__construct()`:

[[[ code('f2801b5b65') ]]]

Esta es una situación súper rara en la que la clase base tiene un constructor al que tenemos que llamar. De hecho, es la única situación que se me ocurre en Symfony como ésta... así que normalmente no es algo de lo que debas preocuparte.

## Preguntas interactivas

Aquí abajo, vamos a dar salida a una recomendación de mezcla... pero hazlo aún más genial preguntando primero al usuario si quiere esta recomendación.

Podemos hacer preguntas interactivas aprovechando el objeto `$io`. Diré`if ($io->confirm('Do you want a mix recommendation?'))`:

[[[ code('cdef6583b7') ]]]

Esto hará esa pregunta, y si el usuario responde "sí", devolverá true. 
El objeto `$io` está lleno de cosas geniales como ésta, incluyendo la formulación de preguntas de opción múltiple y el autocompletado de las respuestas. Incluso podemos crear una barra de progreso

Dentro del if, obtén todas las mezclas con`$mixes = $this->mixRepository->findAll()`. Luego... sólo necesitamos un poco de código feo - `$mix = $mixes[array_rand($mixes)]` - para obtener una mezcla aleatoria.

Imprime la mezcla con un método más `$io` `$io->note()` pasando por`I recommend the mix` y luego introduce `$mix['title']`:

[[[ code('bb2cf7650b') ]]]

Y... ¡listo! Por cierto, ¿te has fijado en este `return Command::SUCCESS`? Eso controla el código de salida de tu comando, así que siempre querrás tener `Command::SUCCESS` al final de tu comando. Si hubiera un error, podrías `return Command::ERROR`.

Bien, ¡probemos esto! Dirígete a tu terminal y ejecuta:

```terminal
php bin/console app:talk-to-me --yell
```

Obtenemos la salida... y luego obtenemos:

> ¿Quieres una recomendación de mezcla?

¡Pues sí, la queremos! ¡Y qué excelente recomendación!

¡Muy bien, equipo! ¡Lo hemos conseguido! ¡Hemos terminado el que creo que es el tutorial de Symfony más importante de todos los tiempos! No importa lo que necesites construir en Symfony, los conceptos que acabamos de aprender serán la base para hacerlo.

Por ejemplo, si necesitas añadir una función o un filtro personalizado a Twig, ¡no hay problema! Lo haces creando una clase de extensión de Twig... y puedes usar MakerBundle para generarla por ti o construirla a mano. Es muy similar a la creación de un comando de consola personalizado: en ambos casos, estás construyendo algo para "engancharse" a una parte de Symfony.

Así que, para crear una extensión Twig, debes crear una nueva clase PHP, hacer que implemente cualquier interfaz o clase base que necesiten las extensiones Twig (la documentación te lo dirá)... y luego sólo tienes que rellenar la lógica... que no mostraré aquí.

Y ya está Entre bastidores, tu extensión Twig se vería automáticamente como un servicio, y la autoconfiguración se encargaría de integrarla en Twig... exactamente como el comando de la consola.

En el próximo curso, pondremos en práctica nuestros nuevos superpoderes añadiendo una base de datos a nuestra aplicación para poder cargar datos reales y dinámicos. Y si tienes alguna pregunta real y dinámica, estamos aquí para ti, como siempre, abajo en la sección de comentarios.

Muy bien, amigos. Muchas gracias por codificar conmigo y nos vemos la próxima vez.
