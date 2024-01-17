# Perfilador: Tu mejor amigo para la depuración

Es hora de instalar nuestro segundo paquete. Y éste es divertido. Vamos a confirmar nuestros cambios primero: así será más fácil comprobar los cambios que hace la receta del nuevo paquete.

Añade todo:

```terminal-silent
git add .
```

Parece que está bien, así que... confirma:

```terminal-silent
git commit -m "Added some Tiwggy goodness"
```

Bonito.

## El paquete de depuración

Ahora ejecuta:

```terminal
composer require debug
```

Así que sí, este es otro alias de Flex... y aparentemente es un alias de`symfony/debug-pack`. Y sabemos que un paquete es una colección de paquetes. Así que, en lugar de añadir esta única línea a nuestro archivo `composer.json`, si lo comprobamos, parece que ha añadido un nuevo paquete en la sección `require` -se trata de una biblioteca de registro- y... al final, ha añadido una nueva sección`require-dev` con otras tres bibliotecas.

La diferencia entre `require` y `require-dev` no es demasiado importante: todos estos paquetes se descargaron en nuestra aplicación, pero como mejor práctica, si instalas una biblioteca que sólo está pensada para el desarrollo local, deberías ponerla en`require-dev`. ¡El pack lo hizo por nosotros! ¡Gracias pack!

## Cambios en la receta

De vuelta al terminal, ¡esto también instaló tres recetas! Ooh. Veamos qué han hecho. Limpio la pantalla y corro:

```terminal
git status
```

Esto me resulta familiar: modificó `config/bundles.php` para activar tres nuevos bundles. De nuevo, los bundles son plugins de Symfony, que añaden más funciones a nuestra aplicación.

También añadió varios archivos de configuración al directorio `config/packages/`. Hablaremos más de estos archivos en el próximo tutorial, pero, a alto nivel, controlan el comportamiento de esos bundles.

## La barra de herramientas de depuración web y el perfilador

¿Qué nos aportan estos nuevos paquetes? Para averiguarlo, dirígete a tu navegador y actualiza la página de inicio. ¡Santo cielo, Batman! Es la barra de herramientas de depuración web. Esto es una locura de depuración: una barra de herramientas llena de buena información. A la izquierda, puedes ver el controlador al que se ha llamado junto con el código de estado HTTP. También está la cantidad de tiempo que tardó la página, la memoria que utilizó y también cuántas plantillas se renderizaron a través de Twig: este es el bonito icono de Twig.

En el lado derecho, tenemos detalles sobre el servidor web local Symfony que se está ejecutando e información sobre PHP.

Pero aún no has visto la mejor parte: haz clic en cualquiera de estos iconos para saltar al perfilador. Esta es la barra de herramientas de depuración web... enloquecida. Está llena de datos sobre esa petición, como la petición y la respuesta, todos los mensajes de registro que se produjeron durante esa petición, información sobre las rutas y la ruta a la que se respondió, Twig te muestra qué plantillas se renderizaron y cuántas veces se renderizaron... y hay información de configuración aquí abajo. ¡Uf!

Pero mi sección favorita es la de Rendimiento. Muestra una línea de tiempo de todo lo que ha ocurrido durante la petición. Esto es genial por dos razones. La primera es bastante obvia: puedes usarla para encontrar qué partes de tu página son lentas. Así, por ejemplo, nuestro controlador tardó 20,4 milisegundos. Y dentro de la ejecución del controlador, la plantilla de la página de inicio se renderizó en 3,9 milisegundos y `base.html.twig`se renderizó en 2,8 milisegundos.

La segunda razón por la que esto es realmente genial es que descubre todas las capas ocultas de Symfony. Ajusta este umbral a cero. Antes, esto sólo mostraba las cosas que tardaban más de un milisegundo. Ahora lo muestra todo. No tienes que preocuparte por la gran mayoría de las cosas, pero es superguay ver las capas de Symfony: las cosas que ocurren antes y después de que se ejecute tu controlador. Tenemos un tutorial de inmersión profunda para Symfony si quieres aprender más sobre estas cosas.

La barra de herramientas de depuración web y el perfilador también crecerán con nuestra aplicación. En un futuro tutorial, cuando instalemos una librería para hablar con la base de datos, de repente tendremos una nueva sección que enumera todas las consultas a la base de datos que hizo una página y el tiempo que tardó cada una.

## funciones dump() y dd()

Bien, el paquete de depuración instaló la barra de herramientas de depuración web. También ha instalado una biblioteca de registro que utilizaremos más adelante. Y ha instalado un paquete que nos proporciona dos fantásticas funciones de depuración.

Dirígete a `VinylController`. Imagina que estamos haciendo un desarrollo y necesitamos ver cómo es esta variable `$tracks`. En este caso es bastante obvio, pero a veces querrás ver lo que hay dentro de un objeto complejo.

Para ello, digamos `dd($tracks)`, donde "dd" significa "dump" y "die".

[[[ code('841aecc2d1') ]]]

Así que si refrescamos... ¡sí! Eso vuelca la variable y mata la página. Y esto es mucho más potente -y más bonito- que usar `var_dump()`: podemos ampliar secciones y ver datos profundos con mucha facilidad.

En lugar de `dd()`, también puedes utilizar `dump()`.. para volcar y vivir. Pero esto podría no aparecer donde esperas. En lugar de imprimirse en el centro de la página, aparece abajo en la barra de herramientas de depuración de la web, bajo el icono del objetivo.

[[[ code('13b985bb4b') ]]]

Si es demasiado pequeño, haz clic para ver una versión más grande en el perfilador.

## Volcado en Twig

También puedes utilizar este `dump()` en Twig. Elimina el volcado del controlador... y luego en la plantilla, justo antes del `ul`, `dump(tracks)`.

[[[ code('ee2dc39650') ]]]

Y esto... se ve exactamente igual. Excepto que cuando haces el volcado en Twig, sí que se vuelca justo en el centro de la página

Y aún más útil, sólo en Twig, puedes utilizar `dump()` sin argumentos.

[[[ code('43c4067dd7') ]]]

Esto volcará todas las variables a las que tengamos acceso. Así que aquí está la variable `title`,`tracks` y, ¡sorpresa! Hay una tercera variable llamada `app`. Es una variable global que tenemos en todas las plantillas... y nos da acceso a cosas como la sesión y los datos del usuario. Y... ¡lo hemos descubierto por curiosidad!

Así que ahora que tenemos estas increíbles herramientas de depuración, pasemos a nuestro siguiente trabajo... que es hacer este sitio menos feo. ¡Es hora de añadir CSS y un diseño adecuado para dar vida a nuestro sitio!
