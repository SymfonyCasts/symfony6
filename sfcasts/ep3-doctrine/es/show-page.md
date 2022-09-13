# Consulta de una entidad única para una página de "Show"

Nuestros usuarios realmente necesitan poder hacer clic en una mezcla y navegar a una página con más información sobre ella... ¡como eventualmente su lista de canciones! ¡Así que hagamos que eso sea posible! Vamos a crear una página para mostrar los detalles de una mezcla.

## Creación de la nueva Ruta y Controlador

Dirígete a `src/Controller/MixController.php`. Después de la acción `new`, añade`public function show()` con el atributo `[#Route()]` anterior. La URL para esto será... qué tal `/mix/{id}`, donde `id` será el ID de esa mezcla en la base de datos. A continuación, añade el argumento `$id` correspondiente. Y... sólo para ver si esto funciona, `dd($id)`.

[[[ code('32d49a76d0') ]]]

¡Genial! Gira y ve a, qué tal, `/mix/7`. ¡Genial! ¡Nuestra ruta y nuestro controlador están conectados!

## Consulta de un solo objeto

Bien, ahora que tenemos el ID, tenemos que buscar en la base de datos el `VinylMix` que coincida con él. Y ya sabemos cómo consultar: a través del repositorio. Añade un segundo argumento al método que se ha indicado con `VinylMixRepository` y llámalo`$mixRepository`. Ahora sustituye el `dd()` por `$mix = $mixRepository->` y, por primera vez, vamos a utilizar el método `find()`. Es muy sencillo: busca un único objeto utilizando la clave primaria. Así que pásale `$id`. Para asegurarnos de que funciona, `dd($mix)`.

[[[ code('859cc66b54') ]]]

Ahora mismo no sabemos qué IDs tenemos en nuestra base de datos, así que como solución, ve a `/mix/new` para crear una nueva mezcla. En mi caso, tiene el ID 16. Genial: ve a `/mix/16` y... ¡hola `VinylMix` `id: 16` ! Lo importante es que esto devuelve un objeto `VinylMix`. A menos que hagas algo personalizado, Doctrine siempre nos devuelve un único objeto o una matriz de objetos, dependiendo del método que llames.

## Renderización de la plantilla

Ahora que tenemos el objeto `VinylMix`, vamos a renderizar una plantilla y a pasarla. Hazlo con `return $this->render()` y llama a la plantilla `mix/show.html.twig`. La ruta de la plantilla podría ser cualquier cosa, pero como estamos dentro de `MixController`, el directorio `mix` tiene sentido. Y como estamos en la acción `show`,`show.html.twig` también tiene sentido. ¡La coherencia es una gran manera de hacer amigos con tus compañeros de equipo!

Pasa una variable llamada `mix` ajustada al objeto `VinylMix` `$mix` .

[[[ code('a2f566cccc') ]]]

Muy bien, vamos a crear esa plantilla. En `templates/`, añade un nuevo directorio llamado `mix/`... y dentro de él, un nuevo archivo llamado `show.html.twig`. Casi todas las plantillas van a empezar de la misma manera. Empieza diciendo`{% extends 'base.html.twig' %}`.

[[[ code('8faba2464f') ]]]

Como recordatorio, `base.html.twig` tiene varios bloques. El más importante aquí abajo es `block body`. Eso es lo que anularemos con nuestro contenido. En la parte superior, también hay un `block title`, que nos permite controlar el título de la página. Anulemos ambos.

Digamos `{% block title %}{% endblock %}` y, en medio, `{{ mix.title }} Mix`. Luego anulemos `{% block body %}` con `{% endblock %}` abajo. Dentro, sólo para empezar, añade un `<h1>` con `{{ mix.title }}`.

[[[ code('f7e4f8ec72') ]]]

Cuando lo probemos... ¡hola página! Esto es súper simple -el `<h1>` ni siquiera está en el lugar correcto- pero está funcionando. Ahora podemos añadir algo de dinamismo.

## Hacer que la página tenga un aspecto elegante

Voy a volver a mi plantilla y a pegar un montón de contenido nuevo. Puedes copiarlo del bloque de código de esta página. La parte superior es exactamente igual: se extiende `base.html.twig` y el `block title` tiene el mismo aspecto que antes. Pero luego, en el cuerpo, tenemos un montón de marcas nuevas, imprimimos el título de la mezcla... y aquí abajo, tengo unos cuantos `TODO`en los que imprimiremos más detalles.

[[[ code('29a9b90ee9') ]]]

Si refrescas ahora... ¡qué bien! Incluso tenemos el simpático SVG del disco... que probablemente reconozcas de la página de inicio. Eso es genial... excepto que duplicar todo este SVG en ambas plantillas es... no tan genial. Vamos a arreglar esa duplicación.

## Evitar la duplicación con una plantilla parcial

Selecciona todo este contenido de `<svg>`, cópialo, y en el directorio `mix/`, crea un nuevo archivo llamado `_recordSvg.html.twig`. ¡Pégalo aquí!

[[[ code('25e389c19c') ]]]

La razón por la que he prefijado el nombre con `_` es para indicar que se trata de un parcial de plantilla. Eso significa que es una plantilla que no incluye una página completa, sino sólo parte de una página. El `_` es opcional... y sólo es algo que se hace como convención común: no cambia ningún comportamiento.

Gracias a esto, podemos entrar en `show.html.twig` y`{{ include('mix/_recordSvg.html.twig) }}`

[[[ code('d32f13df1d') ]]]

Vamos a hacer lo mismo en la plantilla de la página de inicio: `templates/vinyl/homepage.html.twig`. Aquí está el mismo SVG, así que incluiremos esa misma plantilla.

[[[ code('1887b55a99') ]]]

Muy bien Si vamos a comprobar la página de inicio... ¡sigue teniendo un aspecto estupendo! Y si volvemos a la página de la mezcla y la actualizamos... ¡también se ve muy bien!

Para terminar la plantilla, vamos a rellenar los detalles que faltan. Añade un `<h2>` con`class="mb-4"`, y dentro, digamos `{{ mix.trackCount }} songs`, seguido de una etiqueta `<small>`con `(genre: {{ mix.genre }})`... y debajo de ésta, una etiqueta `<p>` con`{{ mix.description }}`.

[[[ code('ec3ff968e2') ]]]

Y ahora... ¡esto empieza a cobrar vida! Todavía no tenemos una lista de canciones... porque esa es otra tabla de la base de datos que crearemos en un futuro tutorial. Pero es un buen comienzo.

## Vinculación con la página de espectáculos

Para completar la nueva función, cuando estemos en la página `/browse`, tenemos que enlazar cada mezcla con su página de espectáculo. Abre `templates/vinyl/browse.html.twig` y desplázate hacia abajo hasta el lugar en el que hacemos el bucle. Bien: cambia la etiqueta `<div>` que lo rodea todo por una etiqueta `<a>`. Luego... rompe esto en varias líneas y añade `href=""`. Como puedes ver, PhpStorm fue lo suficientemente inteligente como para actualizar la etiqueta de cierre por una `a` automáticamente.

Para enlazar a una página en Twig, utilizamos la función `path()` y pasamos el nombre de la ruta. ¿Cuál... es el nombre de la ruta a nuestra página de presentación? La respuesta es... ¡no tiene ninguno! Vale, Symfony autogenera un nombre... pero no queremos confiar en eso. En cuanto queramos enlazar a una ruta, debemos darle un nombre adecuado. ¿Qué te parece `app_mix_show`.

[[[ code('46272d3c62') ]]]

Copia eso, vuelve a `browse.html.twig` y pégalo.

Pero esta vez, ¡pegar el nombre de la ruta no va a ser suficiente! Comprueba este dulce error:

> Faltan algunos parámetros obligatorios ("id") para generar una URL para la ruta
> "app_mix_show".

¡Eso tiene sentido! Symfony está intentando generar la URL de esta ruta, pero tenemos que decirle qué valor comodín debe utilizar para `{id}`. Lo hacemos pasando un segundo argumento en forma de matriz con `{}`. Dentro ponemos `id` a `mix.id`.

[[[ code('4ef34eaf67') ]]]

Y ahora... ¡la página funciona! Y podemos hacer clic en cualquiera de ellas para entrar y ver más detalles.

Bien, ¡ya tenemos el camino feliz funcionando! ¿Pero qué pasa si no se encuentra ninguna mezcla para un determinado ID? Siguiente: hablemos de las páginas 404 y aprendamos cómo podemos ser aún más perezosos haciendo que Symfony consulte el objeto `VinylMix` por nosotros.
