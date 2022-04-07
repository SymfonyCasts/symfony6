# Activos, CSS, imágenes, etc

Si descargas el código del curso desde la página en la que estás viendo este vídeo, después de descomprimirlo, encontrarás un directorio `start/` que contiene la misma aplicación nueva de Symfony 6 que hemos creado antes. En realidad no necesitas ese código, pero contiene un directorio extra llamado `tutorial/`, como el que tengo aquí. Este contiene algunos archivos que vamos a utilizar.

Así que hablemos de nuestro siguiente objetivo: hacer que este sitio parezca un sitio real... en lugar de parecer algo que he diseñado yo mismo. Y eso significa que necesitamos un verdadero diseño HTML que incluya algo de CSS.

## Añadir un diseño y archivos CSS

Sabemos que nuestro archivo de diseño es `base.html.twig`... y también hay un archivo`base.html.twig` en el nuevo directorio `tutorial/`. Copia eso... pégalo en las plantillas, y anula el original.

Antes de ver eso, copia también los tres archivos `.png` y ponlos en el directorio `public/`... para que nuestros usuarios puedan acceder a ellos.

Muy bien. Abre el nuevo archivo `base.html.twig`. Aquí no hay nada especial. Traemos algunos archivos CSS externos de algunos CDN para Bootstrap y FontAwesome. Al final de este tutorial, refactorizaremos esto para que sea una forma más elegante de manejar el CSS... pero por ahora, esto funcionará bien.

Por lo demás, todo sigue estando codificado. Tenemos una navegación codificada, el mismo bloque `body`... y un pie de página codificado. Vamos a ver cómo queda. ¡Refresca y woo! Bueno, no es perfecto, pero es mejor

 Añadir un archivo CSS personalizado

El directorio `tutorial/` también contiene un archivo `app.css` con CSS personalizado. Para que esté disponible públicamente, de modo que el navegador de nuestro usuario pueda descargarlo, tiene que estar en algún lugar del directorio `public/`. Pero no importa dónde o cómo organices las cosas dentro.

Creemos un directorio `styles/`... y luego copiamos `app.css`... y lo pegamos allí.

De vuelta en `base.html.twig`, dirígete a la parte superior. Después de todos los archivos CSS externos, vamos a añadir una etiqueta de enlace para nuestro `app.css`. Así que `<link rel="stylesheet"`y `href=""`. Como el directorio `public/` es la raíz de nuestro documento, para referirse a un archivo CSS o de imagen allí, la ruta debe ser con respecto a ese directorio. Así que esto será `/styles/app.css`.

[[[ code('fa46fd453e') ]]]

Vamos a comprobarlo. Actualiza ahora y... ¡aún mejor!

## La función asset()

Quiero que te des cuenta de algo. Hasta ahora, Symfony no interviene para nada en cómo organizamos o utilizamos las imágenes o los archivos CSS. No. Nuestra configuración es muy sencilla: ponemos las cosas en el directorio `public/`... y luego nos referimos a ellas con sus rutas.

Pero, ¿tiene Symfony alguna función interesante para ayudar a trabajar con CSS y JavaScript? Por supuesto. Se llaman Webpack Encore y Stimulus. Y hablaremos de ambas hacia el final del tutorial.

Pero incluso en esta sencilla configuración -en la que sólo ponemos archivos en `public/` y apuntamos a ellos- Symfony tiene una característica menor: la función `asset()`.

Funciona así: en lugar de usar `/styles/app.css`, decimos `{{ asset() }}` y luego, entre comillas, movemos nuestra ruta allí... pero sin la apertura "/".

[[[ code('f3037ae679') ]]]

Así, la ruta sigue siendo relativa al directorio `public/`... sólo que no necesitas incluir el primer "/".

Antes de hablar de lo que hace esto... vamos a ver si funciona. Actualiza y... ¡no lo hace! Error:

> Función desconocida: ¿te has olvidado de ejecutar `composer require symfony/asset`.

Sigo diciendo que Symfony empieza con algo pequeño... y luego vas instalando cosas a medida que las necesitas. ¡Aparentemente, esta función `asset()` viene de una parte de Symfony que aún no tenemos! Pero conseguirla es fácil. Copia este comando composer require, pásalo a tu terminal y ejecútalo:

```terminal-silent
composer require symfony/asset
```

Se trata de una instalación bastante sencilla: sólo descarga este paquete... y no hay recetas.

Pero cuando probamos la página ahora... ¡funciona! Comprueba el código fuente HTML. Interesante: la etiqueta `link` `href` sigue siendo, literalmente, `/styles/app.css`. ¡Es exactamente lo que teníamos antes! Entonces, ¿qué diablos hace esta función `asset()`?

La respuesta es... no mucho. Pero sigue siendo una buena idea utilizarla. La función `asset()` te ofrece dos características. En primer lugar, imagina que te despliegas en un subdirectorio de un dominio. Por ejemplo, la página de inicio vive en https://example.com/mixed-vinyl.

Si ese fuera el caso, para que nuestro CSS funcione, el `href` tendría que ser `/mixed-vinyl/styles/app.css`. En esta situación, la función `asset()`detectaría el subdirectorio automáticamente y añadiría ese prefijo por ti.

Lo segundo -y más importante- que hace la función `asset()` es permitirte cambiar fácilmente a una CDN más adelante. Como esta ruta pasa ahora por la función`asset()`, podríamos, a través de un archivo de configuración, decir:

> ¡Hey Symfony! Cuando emitas esta ruta, por favor ponle el prefijo de la URL
> a mi CDN.

Esto significa que, cuando carguemos la página, en lugar de `href="/styles/app.css`, sería algo como `https://mycdn.com/styles/app.css`.

Así que la función `asset()` puede que no haga nada que necesites hoy, pero siempre que hagas referencia a un archivo estático, ya sea un archivo CSS, un archivo JavaScript, una imagen, lo que sea, utiliza esta función.

De hecho, aquí arriba, estoy haciendo referencia a tres imágenes. Usemos `asset`: `{{ asset()`... ¡y entonces se autocompleta la ruta! ¡Gracias plugin Symfony! Repite esto para la segunda imagen... y la tercera.

[[[ code('e67894d6cf') ]]]

Sabemos que esto no supondrá ninguna diferencia hoy... podemos refrescar el código fuente HTML para ver las mismas rutas... pero estamos preparados para una CDN en el futuro.

## HTML de la página de inicio y de navegación

¡Así que el diseño ahora se ve muy bien! Pero el contenido de nuestra página de inicio está... como colgando... con un aspecto raro... como yo en la escuela secundaria. De vuelta al directorio `tutorial/`, copia la plantilla de la página de inicio... y sobrescribe nuestro archivo original.

Ábrelo. Esto sigue extendiendo `base.html.twig`... y sigue anulando el bloque`body`. Y además, tiene un montón de HTML completamente codificado. Vamos a ver qué aspecto tiene. Actualiza y... ¡se ve genial!

Excepto que... está 100% codificado. Vamos a arreglarlo. En la parte superior, aquí está el nombre de nuestro disco, imprime la variable `title`.

Y luego, abajo para las canciones... tenemos una larga lista de HTML codificado. Convirtamos esto en un bucle. Añade `{% for track in tracks %}` como teníamos antes. Y... al final, `endfor`.

Para los detalles de la canción, utiliza `track.song`... y `track.artist`. Y ahora podemos eliminar todas las canciones codificadas.

[[[ code('a04b980086') ]]]

¡Genial! Vamos a probarlo. ¡Hey! ¡Está cobrando vida gente!

¡Falta una página más! La página `/browse`. Ya sabes lo que hay que hacer: copiar `browse.html.twig`, y pegar en nuestro directorio. Esto se parece mucho a la página de inicio: extiende`base.html.twig` y anula el bloque `body`.

En `VinylController`, no hemos renderizado antes una plantilla... así que hagámoslo ahora: `return $this->render('vinyl/browse.html.twig')` y pasemos el género. Añade una variable para ello: `$genre =` y si tenemos un slug... utiliza nuestro elegante código de mayúsculas y minúsculas, si no, ponlo en null. Luego borra lo de `$title`... y pasa`genre` a Twig.

[[[ code('91a16480e7') ]]]

De vuelta a la plantilla, utiliza esto en el `h1`. En Twig, también podemos utilizar una sintaxis de fantasía. Así que si tenemos un `genre`, imprime `genre`, si no imprime `All Genres`.

[[[ code('49da07784d') ]]]

Es hora de probar. Dirígete a `/browse`: "Navega por todos los géneros" Y luego`/browse/death-metal`: Navega por el Death Metal. Amigos, ¡esto empieza a parecerse a un sitio real!

Excepto que estos enlaces en el navegador... ¡no van a ninguna parte! Vamos a arreglar eso aprendiendo a generar URLs. También vamos a conocer la mega-poderosa herramienta de línea de comandos`bin/console`.
