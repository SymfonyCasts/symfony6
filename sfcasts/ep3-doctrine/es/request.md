# El objeto de petición

Nuevo equipo objetivo: permitir que los usuarios voten a favor y en contra de una mezcla. Para conseguirlo, en la entidad `VinylMix`, cuando un usuario vota, necesitamos enviar una consulta UPDATE para cambiar la propiedad entera `$votes` en la base de datos.

## Añadir un formulario sencillo

Centrémonos primero en la interfaz de usuario. Abre `templates/mix/show.html.twig`. Para empezar, imprime `{{ mix.votesString }} votes` para que podamos verlo aquí.

[[[ code('6754b906b7') ]]]

Y... ¡perfecto! Para añadir la funcionalidad de upvote y downvote, podríamos utilizar algún JavaScript sofisticado. Pero vamos a hacerlo sencillo añadiendo un botón que publique un formulario. En realidad, esto será más elegante de lo que parece. En el primer tutorial, instalamos la biblioteca Turbo JavaScript. Así que aunque usaremos una etiqueta y un botón normales de`<form>`, Turbo lo enviará automáticamente vía AJAX para una experiencia fluida.

Por cierto, Symfony tiene un componente de formulario y hablaremos de él en un futuro tutorial. Pero este formulario va a ser tan sencillo que realmente no lo necesitamos. Añade una bonita y aburrida etiqueta `<form>` con `action` establecida en la función`path()`.

El formulario se enviará a un nuevo controlador que... ¡todavía tenemos que crear!

Dirígete a `MixController` y añade un nuevo `public function` llamado `vote()`. Dale el atributo `#[Route()]` con la URL `/mix/{id}/vote`. Y como tenemos que enlazar con esto, añade un nombre: `app_mix_vote`.

[[[ code('83dd0e386e') ]]]

El comodín de la ruta `{id}` contendrá el id del `VinylMix` específico que el usuario está votando. Para consultarlo, utiliza el truco que aprendimos antes: añade un argumento de tipo `VinylMix` y llámalo `$mix`. Ah, y aunque no es necesario, añadiré el tipo de retorno `Response`. Añadir esto es sólo una buena práctica.

Dentro, para asegurarnos de que las cosas funcionan, `dd($mix)`.

[[[ code('13482639eb') ]]]

¡Genial! Copia el nombre de la ruta, vuelve a la plantilla - `show.html.twig` - y dentro de `path()`, pega. Y como esta ruta tiene un comodín `{id}`, pasa`id` a `mix.id`. También dale al formulario `method="POST"`... porque siempre que el envío de un formulario cambie los datos en tu servidor, debe enviarse con `POST`.

[[[ code('ea16ec1820') ]]]

Incluso podemos imponer este requisito en nuestra ruta añadiendo`methods: ['POST']`. Eso es opcional, pero ahora, si alguien, por alguna razón, va directamente a esta URL, que es una petición GET, no coincidirá con la ruta. ¡Muy útil!

[[[ code('6664c70b9d') ]]]

Vuelve al formulario. Este formulario... será algo extraño. En lugar de tener campos en los que el usuario pueda escribir, sólo necesitamos un botón. Añade `<button>` con`type="submit"`... y luego algunas clases para el estilo. Para el texto, utiliza un icono de Font Awesome: un `<span>` con `class="fa fa-thumbs-up"`.

[[[ code('ac3486b8ec') ]]]

¡Perfecto! Vamos a comprobarlo. Actualiza y... ¡pulgares arriba! Y cuando hagamos clic en él... ¡hermoso! ¡Llega a la ruta! Fíjate en que la URL no ha cambiado... eso es porque Turbo ha enviado el formulario vía Ajax... y luego nuestro `dd()` lo ha detenido todo.

Bien, en un minuto, vamos a añadir otro botón con el pulgar hacia abajo. Así que, de alguna manera, en nuestro controlador, vamos a tener que averiguar qué botón, el de arriba o el de abajo, se acaba de pulsar.

Para ello, en el botón, añade `name="direction"` y `value="up"`. Ahora, si pulsamos este botón, se enviará un dato POST llamado `direction` con el valor `up`... casi como si el usuario escribiera la palabra `up` en un campo de texto.

[[[ code('1c7b86aadc') ]]]

## Obtención del DAta de petición

Bien... ¿pero cómo leemos los datos POST en Symfony? Siempre que necesites leer algo de la petición -como datos POST, parámetros de consulta, archivos subidos o cabeceras- necesitarás el objeto `Request` de Symfony. Y hay dos formas de obtenerlo.

La primera es autocableando un servicio llamado `RequestStack`. Entonces puedes obtener la petición actual diciendo `$requestStack->getCurrentRequest()`.

Esto funciona en cualquier lugar donde puedas autocablear un servicio. Pero en un controlador, hay una forma más fácil. Deshaz eso... y en su lugar, añade un argumento que se indique con`Request`. Consigue el de HttpFoundation de Symfony. Llamémoslo `$request`.

[[[ code('4365dc9283') ]]]

Al principio, esto parece un autocable, ¿no? Parece que `Request` es un servicio y lo estamos autocableando como argumento. Pero... ¡sorpresa! `Request` no es un servicio. No, es otra "cosa" que puedes tener como argumento para tu controlador.

Repasemos. Ahora conocemos cuatro tipos diferentes de argumentos que puedes tener en un método del controlador. Uno: puedes tener comodines de ruta como `$id`. Dos: Puedes autoconectar servicios. Tres: Puedes tener entidades con sugerencias de tipo. Y cuatro: Puedes teclear la clase `Request`. Sí, el objeto `Request` es tan importante que Symfony ha creado un caso especial sólo para él.

Y... es bastante bonito. Todo nuestro trabajo como desarrolladores es "leer la petición entrante" y utilizarla para "crear una respuesta". Así que es... casi poético que podamos tener un método que tome el `Request` como argumento y devuelva un `Response`. Entrada `Request`, salida `Response`.

## Obtención de datos POST

Pero estoy divagando. Hay un montón de métodos y propiedades diferentes en la petición para obtener lo que necesites. Para leer datos POST, di `$request->request->get()` y luego el nombre del campo. En este caso, `direction`.

[[[ code('8171c6f517') ]]]

No vamos a hablar mucho del objeto `Request`... porque es... un simple objeto que contiene datos. Si necesitas leer algo de él, sólo tienes que mirar la documentación y te dirá cómo hacerlo.

Muy bien, vuelve aquí, refresca la página... sube la nota y... ¡ya está! Bien, quita el `dd()` y ponlo en una variable de dirección con `$direction =`.

Si, por alguna razón, faltan los datos de `direction` POST (esto no debería ocurrir a no ser que alguien esté trasteando con nuestro sitio), ponlo por defecto en `up`.

[[[ code('e208c4c9c2') ]]]

Ahora vamos a añadir el voto negativo. Copia todo el botón... pégalo... cambia el valor a `down` y actualiza la clase del icono a `fa fa-thumbs-down`.

[[[ code('43ef88914d') ]]]

Bien, ya sabemos que el valor será o bien `up` o bien `down`. En nuestro controlador, usemos esto. `if ($direction === 'up')`, entonces`$mix->setVotes($mix->getVotes() + 1)`. Si no, haz lo mismo... excepto que será `- 1`. Abajo, `dd($mix)`.

[[[ code('c300b69e9f') ]]]

En un sitio real, probablemente también almacenaremos qué usuario está votando para que no pueda votar una y otra vez. Aprenderemos a hacerlo en un futuro tutorial, pero esto funcionará bien por ahora.

Muy bien, vuelve a actualizar. Tenemos 49 votos. Si hacemos clic en el botón de "upvote"... ¡50! Si refrescamos y hacemos clic en el botón de "downvote"... ¡48!

¡Sí! Pero todavía no hemos guardado este valor en la base de datos. Cuando actualizamos, siempre vuelve al "49" original.

Así que... a continuación, ¡vamos a hacerlo! Haremos una consulta UPDATE a la base de datos y también terminaremos la ruta redirigiendo a otra página.
