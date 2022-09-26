# Mensaje flash y modelos ricos vs anémicos

Después de enviar un formulario con éxito, siempre redirigimos. A menudo, también querremos mostrar al usuario un mensaje de éxito para que sepa que todo ha funcionado. Symfony tiene una forma especial de manejar esto: los mensajes flash.

Para establecer un mensaje flash, antes de redirigir, llama a `$this->addFlash()` y pasa, en esta situación, `success`. Para el segundo argumento, pon el mensaje que quieres mostrar al usuario, como `Vote counted!`.

[[[ code('9733851b63') ]]]

La clave `success` puede ser cualquier cosa... es una especie de "categoría" para el mensaje flash... y verás cómo la utilizamos en un minuto.

Los mensajes flash tienen un nombre elegante, pero son una idea sencilla; Symfony almacena los mensajes flash en la sesión del usuario. Lo que los hace especiales es que Symfony eliminará el mensaje automáticamente en cuanto lo leamos. Son como mensajes que se autodestruyen. Bastante chulo.

## Lectura de mensajes Flash

Entonces... ¿cómo los leemos? La forma en que me gusta hacerlo es abriendo mi plantilla base -`base.html.twig` - y leyéndolos y renderizándolos aquí. Pongámoslo justo después de la navegación pero antes de `{% block body %}`. Digamos `{% for message in %}`. Entonces, queremos leer los mensajes flash de la categoría `success` que podamos tener. Para ello, podemos aprovechar la única variable global Twig de Symfony: `app`. Ésta tiene varios métodos, como `environment`, `request`, `session`, el actual `user`, o uno llamado `app.flashes`. Pásale la categoría (en nuestro caso,`success`). Como ya he dicho, puede ser cualquier cosa. Si pusieras `dinosaur` como clave en un controlador, entonces leerías los mensajes de `dinosaur` aquí. Termina con `{% endfor %}`.

[[[ code('ca33c9b940') ]]]

Normalmente, sólo tendrás un mensaje de éxito en tu flash a la vez, pero técnicamente puedes tener varios. Por eso estamos haciendo un bucle sobre ellos.

Dentro de esto, renderiza un `<div>` con `class="alert alert-success"` para que parezca un mensaje de felicidad. Luego, imprime `message`.

[[[ code('23490be0be') ]]]

Así, si esto funciona correctamente, leerá todos nuestros mensajes flash `success` y los renderizará. Y una vez leídos, Symfony los eliminará para que no se vuelvan a renderizar en la siguiente carga de la página. Al poner esto en la plantilla base, ahora podemos establecer mensajes flash desde cualquier parte de nuestra aplicación y se renderizarán en la página. Muy bonito.

Observa. Vuelve a nuestra página, sube la nota y... ¡guapa! Probablemente querremos eliminar este margen extra en un proyecto real, pero lo dejaremos por ahora.

## Hacer más inteligente nuestra clase de entidad

Muy bien, vuelve a mirar a `MixController`. La lógica para hacer nuestra votación "arriba" y "abajo" es bastante sencilla... pero creo que puede ser mejor. ¡Prueba esto! Abre`VinylMix`... y baja hasta `setVotes()`. Justo después de esto, sólo para mantener las cosas organizadas, crea un nuevo `public function` llamado `upVote()` y devuelve `void`. Dentro, di `$this->votes++`. Copia eso, y crea un segundo método que llamaremos -lo has adivinado- `downVote()`... con `$this->votes--`.

[[[ code('f6a52d95fb') ]]]

Gracias a estos métodos, en `MixController`, en lugar de tener `$mix->setVotes()`para `$mix->getVotes() + 1`, podemos decir simplemente `$mix->upVote()`... y `$mix->downVote()`.

[[[ code('49312b26a8') ]]]

Eso está muy bien. Nuestro controlador se lee con mucha más claridad, y hemos encapsulado la lógica de `upVote()` y `downVote()` en nuestra entidad. Si nos dirigimos y refrescamos, sigue funcionando.

## Modelos inteligentes frente a modelos anémicos

Esto pone de manifiesto un tema interesante. Ahora hemos añadido cuatro métodos personalizados a nuestra entidad: dos que ayudan a leer los datos de forma especial, y dos que ayudan a establecer los datos. Cuando ejecutamos `make:entity`, se crean métodos getter y setter para cada una de las propiedades. Eso está muy bien, porque hace que nuestra entidad sea infinitamente flexible: cualquiera, desde cualquier lugar, puede leer o establecer cualquier propiedad. Pero a veces, puede que no quieras o necesites eso. Por ejemplo, ¿realmente queremos un método `setVotes()`? ¿Hay realmente un caso de uso en nuestro código para que algo establezca el recuento de votos a cualquier número que quiera? Probablemente no. Es probable que sólo necesitemos `upVote()` y `downVote()`. Yo mantendré el método `setVotes()`... sin embargo, porque lo utilizamos cuando generamos nuestro objeto ficticio `VinylMix`.

Pero, en general, si eliminas los métodos getter y setter innecesarios en tu entidad y los sustituyes por métodos más descriptivos como `upVote()`, `downVote()`,`getVoteString()`, o `getImageUrl()` -métodos que se ajustan a tu lógica de negocio- puedes, poco a poco, dar más claridad a tus entidades. Nuestros métodos `upVote()` y`downVote()` son súper claros y descriptivos. Alguien que los llame no necesita saber ni preocuparse de cómo funcionan internamente.

Las entidades que sólo tienen métodos getter y setter se denominan a veces "modelos anémicos". Las entidades que las eliminan y las sustituyen por métodos específicos para su lógica de negocio se denominan a veces "modelos ricos". Algunas personas llevan esto al extremo y casi no tienen métodos getter o setter. Aquí, en SymfonyCasts, tendemos a ser pragmáticos. Normalmente tenemos métodos getter y setter, pero siempre buscamos formas de ser más descriptivos, como añadir `upVote()` y `downVote()`.

A continuación, vamos a instalar una impresionante biblioteca llamada DoctrineExtensions. Se trata de una biblioteca mágica llena de superpoderes, como los comportamientos automáticos de creación de marcas de tiempo y babosas.
