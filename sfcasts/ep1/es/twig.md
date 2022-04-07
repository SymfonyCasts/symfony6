# Twig ❤️

Las clases de controlador de Symfony no necesitan extender una clase base. Mientras tu función de controlador devuelva un objeto `Response`, a Symfony no le importa el aspecto de tu controlador. Pero normalmente, extenderás una clase llamada`AbstractController`.

¿Por qué? Porque nos da métodos de acceso directo.

## Renderización de una plantilla

Y el primer atajo es `render()`: el método para renderizar una plantilla. Así que devuelve `$this->render()` y le pasa dos cosas. La primera es el nombre de la plantilla. ¿Qué tal `vinyl/homepage.html.twig`.

No es necesario, pero es habitual tener un directorio con el mismo nombre que la clase de tu controlador y un nombre de archivo que sea el mismo que el de tu método, pero puedes hacer lo que quieras. El segundo argumento es un array con las variables que quieras pasar a la plantilla. Vamos a pasar una variable llamada`title` y a ponerle el título de nuestra cinta de mezclas: "PB and Jams".

[[[ code('28e791440a') ]]]

Hecho aquí. Ah, pero, ¡examen sorpresa! ¿Qué crees que devuelve el método `render()`? Sí, es lo que siempre repito: un controlador siempre debe devolver un objeto `Response`. `render()` es sólo un atajo para renderizar una plantilla, obtener esa cadena y ponerla en un objeto `Response`. `render()` devuelve un objeto `Response`.

## Crear la plantilla

Sabemos por lo que hemos dicho antes, que cuando renderizas una plantilla, Twig busca en el directorio `templates/`. Así que crea un nuevo subdirectorio `vinyl/`... y dentro de él, un archivo llamado `homepage.html.twig`. Para empezar, añade un `h1` y luego imprime la variable `title` con una sintaxis especial de Twig: `{{ title }}`. Y... Añadiré un texto TODO codificado.

[[[ code('285607e2a3') ]]]

¡Vamos a ver si esto funciona! Estábamos trabajando en nuestra página web, así que ve allí y... ¡hola Twig!

## Sintaxis de Twigs 3

Twig es una de las partes más bonitas de Symfony, y también una de las más fáciles. Vamos a repasar todo lo que necesitas saber... básicamente en los próximos diez minutos.

Twig tiene exactamente tres sintaxis diferentes. Si necesitas imprimir algo, utiliza `{{`. A esto lo llamo la sintaxis "decir algo". Si digo `{{ saySomething }}`se imprimiría una variable llamada `saySomething`. Una vez que estás dentro de Twig, se parece mucho a JavaScript. Por ejemplo, si lo encierro entre comillas, ahora estoy imprimiendo la cadena `saySomething`. Twig tiene funciones... por lo que llamaría a la función e imprimiría el resultado.

Así que la sintaxis nº 1 -la de "decir algo"- es `{{`

La segunda sintaxis... no cuenta realmente. Es `{#` para crear un comentario... y ya está.

[[[ code('285607e2a3') ]]]

La tercera y última sintaxis la llamo "hacer algo". Esto es cuando no estás imprimiendo, estás haciendo algo en el lenguaje. Ejemplos de "hacer algo" serían las sentencias if, los bucles for o la configuración de variables.

## El bucle for

Vamos a probar un bucle `for`. Vuelve al controlador. Voy a pegar una lista de pistas... y luego pasaré una variable `tracks` a la plantilla ajustada a esa matriz.

[[[ code('8123e02600') ]]]

Ahora, a diferencia de `title`, tracks es una matriz... así que no podemos imprimirla. Pero, ¡podemos intentarlo! ¡Ja! Eso nos da una conversión de matriz a cadena. No, tenemos que hacer un bucle sobre las pistas.

Añade una cabecera y un `ul`. Para hacer el bucle, usaremos la sintaxis "hacer algo", que es`{%` y luego la cosa que quieras hacer, como `for`, `if` o `set`. Te mostraré la lista completa de etiquetas de hacer algo en un minuto. Un bucle for tiene este aspecto:`for track in tracks`, donde pistas es la variable sobre la que hacemos el bucle y `track`será la variable dentro del bucle.

Después de esto, añade `{% endfor %}`: la mayoría de las etiquetas "hacer algo" tienen una etiqueta de fin. Dentro del bucle, añade un `li` y luego utiliza la sintaxis de decir algo para imprimir `track`.

[[[ code('dc265ad487') ]]]

## Uso de Sub.keys

Cuando lo probemos... ¡qué bien! Pero vamos a ponernos más complicados. De vuelta en el controlador, en lugar de utilizar un simple array, lo reestructuraré para que cada pista sea un array asociativo con las claves `song` y `artist`. Pondré ese mismo cambio para el resto.

[[[ code('647cdcfd7e') ]]]

¿Qué ocurre si lo probamos? Ah, volvemos a la conversión de "matriz a cadena". Cuando hacemos el bucle, cada pista es ahora una matriz. ¿Cómo podemos leer las claves `song`y `artist`?

¿Recuerdas cuando dije que Twig se parece mucho a JavaScript? Pues bien, no debería sorprender que la respuesta sea `track.song` y `track.artist`.

[[[ code('fc54d0388b') ]]]

Y... eso hace que nuestra lista funcione.

Ahora que ya tenemos lo básico de Twig, vamos a ver la lista completa de etiquetas "hacer algo", a conocer los "filtros" de Twig y a abordar el importantísimo sistema de herencia de plantillas.
