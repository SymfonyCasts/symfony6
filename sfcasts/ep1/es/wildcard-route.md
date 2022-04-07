# Rutas comodín

La página de inicio será el lugar donde el usuario podrá diseñar y construir su próxima cinta de mezclas. Pero además de crear nuevas cintas, los usuarios también podrán explorar las creaciones de otras personas.

## Crear una segunda página

Vamos a crear una segunda página para eso. ¿Cómo? Añadiendo un segundo controlador: función pública, qué tal `browse`: el nombre no importa realmente. Y para ser responsable, añadiré un tipo de retorno `Response`.

Por encima de esto, necesitamos nuestra ruta. Ésta será exactamente igual, salvo que pondremos la URL en `/browse`. Dentro del método, ¿qué es lo que siempre devolvemos de un controlador? Así es: ¡un objeto `Response`! Devuelve un nuevo `Response`... con un mensaje corto para empezar.

[[[ code('00c844ab6c') ]]]

¡Vamos a probarlo! Si actualizamos la página de inicio, no cambia nada. Pero si vamos a `/browse`... ¡lo machacamos! ¡Una segunda página en menos de un minuto! ¡Caramba!

En esta página, acabaremos por listar las cintas de mezclas de otros usuarios. Para ayudar a encontrar algo que nos guste, quiero que los usuarios también puedan buscar por género. Por ejemplo, si voy a `/browse/death-metal`, eso me mostraría todas las cintas de vinilo de death metal. Hardcore.

Por supuesto, si probamos esta URL ahora mismo... no funciona.

> No se ha encontrado la ruta

No se han encontrado rutas coincidentes para esta URL, por lo que nos muestra una página 404. Por cierto, lo que estás viendo es la elegante página de excepciones de Symfony, porque estamos desarrollando. Nos da muchos detalles cuando algo va mal. Cuando finalmente despliegues a producción, puedes diseñar una página de error diferente que verían tus usuarios.

## {Cartel de la muerte} Rutas

De todos modos, la forma más sencilla de hacer que esta URL funcione es simplemente... cambiar la URL a`/browse/death-metal`

[[[ code('7cf6861477') ]]]

Pero... no es súper flexible, ¿verdad? Necesitaríamos una ruta para cada género... ¡que podrían ser cientos! Y además, ¡acabamos de matar la URL `/browse`! Ahora es 404.

Lo que realmente queremos es una ruta que coincida con `/browse/<ANYTHING>`. Y podemos hacerlo con un comodín. Sustituye el código duro `death-metal` por `{}` y, dentro,`slug`. Slug es sólo una palabra técnica para designar un "nombre seguro para la URL". En realidad, podríamos haber puesto cualquier cosa dentro de las llaves, como `{genre}` o `{coolMusicCategory}`: no hay ninguna diferencia. Pero sea lo que sea que pongamos dentro de este comodín, se nos permite tener un argumento con ese mismo nombre: `$slug`.

[[[ code('5a1436e579') ]]]

Sí, si vamos a `/browse/death-metal`, coincidirá con esta ruta y pasará la cadena`death-metal` a ese argumento. La coincidencia se hace por nombre: `{slug}` conecta con `$slug`.

Para ver si funciona, devolvamos una respuesta diferente: `Genre` y luego la `$slug`.

[[[ code('90a1e7b05e') ]]]

¡Hora de probar! Vuelve a `/browse/death-metal` y... ¡sí! Prueba con `/browse/emo` y ¡sí! ¡Estoy mucho más cerca de mi cinta de mezcla de Dashboard Confessional!

Ah, y es opcional, pero puedes añadir un tipo `string` al argumento `$slug`. Eso no cambia nada... es sólo una bonita forma de programar: el `$slug` ya iba a ser siempre una cadena.

[[[ code('dd04d150f1') ]]]

Un poco más adelante, aprenderemos cómo puedes convertir un comodín numérico -como el número 5- en un número entero si así lo deseas.

## Usando el componente de cadena de Symfony

Hagamos esta página un poco más elegante. En lugar de imprimir el slug exactamente, vamos a convertirlo en un título. Digamos `$title = str_replace()` y sustituyamos los guiones por espacios. Luego, aquí abajo, utiliza el título en la respuesta. En un futuro tutorial, vamos a consultar la base de datos para estos géneros, pero, por ahora, al menos podemos hacer que tenga un aspecto más agradable.

[[[ code('2d1891ae08') ]]]

Si lo probamos, el Emo no se ve diferente... pero el death metal sí. ¡Pero quiero que sea más elegante! Añade otra línea con `$title =` y luego escribe `u` y autocompleta una función que se llama literalmente... `u`.

No utilizamos muchas funciones de Symfony, pero éste es un ejemplo raro. Proviene de una biblioteca de Symfony llamada `symfony/string`. Como he mencionado, Symfony tiene muchas bibliotecas diferentes -también llamadas componentes- y vamos a aprovechar esas bibliotecas todo el tiempo. Esta te ayuda a hacer transformaciones de cadenas... y resulta que ya está instalada.

Mueve el `str_replace()` al primer argumento de `u()`. Esta función devuelve un objeto sobre el que podemos hacer operaciones de cadena. Uno de los métodos se llama `title()`. Digamos `->title(true)` para convertir todas las palabras en mayúsculas y minúsculas.

[[[ code('7ef6fbf8e0') ]]]

Ahora, cuando lo probamos... ¡qué bien! ¡Pone las letras en mayúsculas! El componente de la cadena no es especialmente importante, sólo quiero que veas cómo podemos aprovechar partes de Symfony para hacer nuestro trabajo.

## Hacer que el comodín sea opcional

Bien: un último reto. Ir a `/browse/emo` o `/browse/death-metal` funciona. Pero ir a `/browse`... no funciona. ¡Está roto! Un comodín puede coincidir con cualquier cosa, pero, por defecto, se requiere un comodín. Tenemos que ir a`/browse/<something>`.

¿Podemos hacer que el comodín sea opcional? Por supuesto Y es deliciosamente sencillo: haz que el argumento correspondiente sea opcional.

[[[ code('9ba3296dd1') ]]]

En cuanto lo hagamos, le dirá a la capa de enrutamiento de Symfony que no es necesario que el `{slug}` esté en la URL. Así que ahora cuando refrescamos... funciona. Aunque no es un buen mensaje para la página.

Veamos. Si hay un slug, pon el título como estábamos. Si no, pon`$title` a "Todos los géneros". Ah, y mueve el "Género:" aquí arriba... para que abajo en el `Response` podamos pasar simplemente `$title`.

[[[ code('8cef2cd6cb') ]]]

Inténtalo. En `/browse`... "Todos los géneros". En `/browse/emo`... "Género: Emo".

Siguiente: poner un texto como éste en un controlador.... no es muy limpio ni escalable, especialmente si empezamos a incluir HTML. No, tenemos que hacer una plantilla. Para ello, vamos a instalar nuestro primer paquete de terceros y seremos testigos del importantísimo sistema de recetas de Symfony en acción.
