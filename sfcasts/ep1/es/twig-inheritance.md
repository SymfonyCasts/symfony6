# Herencia Twig

Dirígete a https://twig.symfony.com... y haz clic para consultar su documentación. Hay mucho material bueno aquí. Pero lo que quiero que hagas es que te desplaces hasta la referencia a Twig. ¡Sí!

## Etiquetas

Lo primero que debes mirar, a la izquierda, son estas cosas llamadas etiquetas. Esta lista representa todas las cosas posibles que puedes utilizar con la sintaxis de hacer algo. Sí, siempre será `{%` y luego una de estas cosas, como `for` o `if`. Y sinceramente, sólo vas a utilizar unas 5 de ellas en el día a día. Si quieres saber la sintaxis de uno de ellos, sólo tienes que hacer clic para ver su documentación.

## Filtros

Además de las 20 etiquetas, Twig también tiene algo llamado filtros. Los filtros son básicamente funciones, pero con una sintaxis más moderna. Twig también tiene funciones, pero son menos: Twig prefiere los filtros: ¡son mucho más chulos!

Por ejemplo, hay un filtro llamado `upper`. Usar un filtro es como usar la tecla`|` en la línea de comandos. Tienes un valor y luego lo "canalizas" en el filtro que quieres, como `upper`.

¡Vamos a probar esto! Imprime `track.artist|upper`.

[[[ code('73974377cd') ]]]

Y ahora... ¡está en mayúsculas! Si quieres confundir a tus compañeros de trabajo, puedes canalizarlo a `lower`... que devuelve las cosas a minúsculas. No hay ninguna razón real para hacer esto, pero los filtros pueden encadenarse así.

[[[ code('016bee2e4d') ]]]

De todos modos, echa un vistazo a la lista de filtros porque probablemente haya algo que te resulte útil.

Y... ¡eso es todo! Además de las funciones, también hay algo llamado "pruebas", que son útiles en las sentencias if: puedes decir cosas como "si el número es divisible por 5".

## Herencia de Plantillas

Vale, sólo una cosa más que aprender sobre Twig, y es genial.

Mira el código fuente HTML de la página. Fíjate en que no hay estructura HTML: no hay etiquetas `html`, `head` o `body`. Literalmente el HTML que tenemos dentro de nuestra plantilla, es lo que obtenemos. Nada más.

Entonces, ¿hay... algún tipo de sistema de diseño en Twig en el que podamos añadir un diseño base a nuestro alrededor? Por supuesto. Y es increíble. Se llama herencia de plantillas. Si tienes una plantilla y quieres que utilice algún diseño base, en la parte superior del archivo, utiliza una etiqueta "hacer algo" llamada `extends`. Pásale el nombre del archivo de diseño: `base.html.twig`.

[[[ code('48dc6d8fec') ]]]

Esto se refiere a esta plantilla de aquí. Antes de comprobarlo, si lo intentamos ahora, ¡vaya! Gran error:

> Una plantilla que extiende otra no puede incluir contenido fuera de los bloques Twig.

Para saber qué significa esto, abre `base.html.twig`. Este es tu archivo de diseño base... y eres totalmente libre de personalizarlo como quieras. Ahora mismo... es en su mayor parte sólo etiquetas HTML aburridas... excepto por una serie de estos "bloques".

Los bloques son básicamente "agujeros" en los que una plantilla hija puede colocar contenido. Permíteme explicarlo de otra manera. Cuando decimos `extends 'base.html.twig'`, eso dice básicamente:

> ¡Yo Twig! Cuando renderices esta plantilla, quiero que realmente renderices
> `base.html.twig`... y luego pongas mi contenido dentro de ella.

Twig responde educadamente:

> Vale, genial... Puedo hacerlo. Pero, ¿en qué parte de `base.html.twig` quieres que ponga
> todo tu contenido? ¿Quieres que lo ponga al final de la página? ¿En la parte
> parte superior? ¿En algún lugar al azar en el medio?

La forma de decirle a Twig dónde poner nuestro contenido dentro de `base.html.twig` es anulando un bloque. Fíjate en que `base.html.twig` ya tiene un bloque llamado `body`... y ahí es justo donde queremos poner el HTML de nuestra plantilla.

Para ponerlo ahí, en nuestra plantilla, rodea todo el contenido con`{% block body %}`... y luego `{% endblock %}`.

[[[ code('01d2fbf6f5') ]]]

A esto se le llama herencia de la plantilla porque estamos sobrescribiendo ese bloque `body` con este nuevo contenido. Así que ahora, cuando Twig renderice `base.html.twig`... y llegue a esta parte `block body`, va a imprimir el HTML `block body` de nuestra plantilla

Observa: actualiza y... el error ha desaparecido. Y si ves el código fuente de la página, ¡tenemos una página HTML completa!

## Nombres de los bloques

Ah, y los nombres de estos bloques no son importantes. Si quieres cambiarles el nombre por el de tu personaje favorito de una sitcom de los 90, hazlo. Sólo recuerda actualizar también su nombre en cualquier plantilla hija.

También puedes añadir más bloques. Cada bloque que añadas es otro punto de anulación potencial.

## Contenido del bloque por defecto

Ah, y habrás notado que los bloques pueden tener contenido por defecto. Mira la página ahora mismo: el título dice "Bienvenido". Eso es porque el bloque `title` tiene un contenido por defecto... y no lo vamos a anular. Vamos a cambiar el título por defecto a "Vinilo mixto".

[[[ code('086e2afb43') ]]]

Así que ahora ese será el título de todas las páginas de nuestro sitio... a menos que lo anulemos. En nuestra plantilla, ya sea por encima del cuerpo del bloque o por debajo -el orden de los bloques no importa-, añade `{% block title %}`, `{% endblock %}` y, en medio, "Crear un nuevo disco".

[[[ code('4ebc00a77a') ]]]

Y ahora... ¡sí! Esta página tiene un título personalizado.

## Añadir al bloque padre (en lugar de sustituirlo)

Ah, y puede que te preguntes

> ¿Qué pasa si no quiero sustituir un bloque por completo.... sino que quiero
> añadir a un bloque?

Eso es totalmente posible. En `base.html.twig`, el bloque `title` está configurado como "Vinilo mixto". Si quisiéramos añadirle nuestro título personalizado, podríamos decir "Crear un nuevo disco" y luego utilizar la etiqueta "decir algo" para imprimir una función llamada `parent()`.

[[[ code('9729cce31f') ]]]

Eso hace exactamente lo que esperarías: encuentra el contenido de la plantilla padre para este bloque... y lo imprime. Actualiza y... eso es muy bonito.

## La herencia de plantillas es la herencia de clases

Si alguna vez estás confundido sobre cómo funciona la herencia de plantillas, es útil, al menos para mí, pensar en ella exactamente como en la herencia orientada a objetos. Cada plantilla es como una clase y cada bloque es como un método. Así, la "clase" de la página de inicio extiende la "clase" de `base.html.twig`, pero anula dos de sus métodos. Si eso sólo te ha confundido, no te preocupes.

Así que... eso es todo para Twig. Básicamente eres un experto en Twig, lo que me han dicho que es un tema popular en las fiestas.

A continuación: una de las características más destacadas de Symfony son sus herramientas de depuración. Vamos a instalarlas y a comprobarlas.