# Actualización de una entidad

Hemos conseguido cambiar el valor de la propiedad `votes`. Ahora tenemos que hacer una consulta de actualización para guardarlo en la base de datos.

Para insertar un `VinylMix`, utilizamos el servicio `EntityManagerInterface`, y luego llamamos a`persist()` y `flush()`. Para actualizar, utilizaremos exactamente el mismo servicio.

## Actualizar una entidad con el Gestor de Entidades

Añade un nuevo argumento al método `vote()`, indicado con `EntityManagerInterface`. Lo llamaré `$entityManager`. Luego, muy sencillamente, después de haber establecido la propiedad `votes` con el nuevo valor, llama a `$entityManager->flush()`.

[[[ code('4b5e709f1f') ]]]

¡Eso es todo, gente! Antes de explicar esto, vamos a asegurarnos de que funciona. Actualiza. Ahora mismo tenemos 49 votos. Voy a pulsar arriba. Dice 50. Pero la verdadera prueba es que cuando refrescamos... ¡sigue mostrando 50! ¡Se ha guardado!

## Persistir y descargar: los detalles

Vale, cuando antes creamos un nuevo `VinylMix`, tuvimos que llamar a `persist()` -pasando el objeto `VinylMix` - y luego a `flush()`. Pero ahora, lo único que necesitamos es `flush()`. ¿Por qué?

Esta es la historia completa. Cuando llamas a `flush()`, Doctrine hace un bucle sobre todos los objetos de entidad que "conoce" y los "guarda". Y ese "guardado" es inteligente. Si Doctrine determina que una entidad no se ha guardado todavía, ejecutará una consulta INSERT, pero si se trata de un objeto que ya existe en la base de datos, Doctrine averiguará qué ha cambiado en el objeto -si es que ha cambiado algo- y ejecutará una consulta `UPDATE`. ¡Sí! Sólo tenemos que llamar a `flush()` y Doctrine averiguará qué hacer. Es... lo mejor desde las gominolas Starburst.

Pero... ¿por qué no tenemos que llamar a `persist()` cuando estamos actualizando? Bueno, puedes decir `$entityManager->persist($mix)` si quieres. Es que es... ¡totalmente redundante!

Cuando llamas a `persist()`, le dice a Doctrine:

> Quiero que conozcas este objeto para que, la próxima vez que llame a `flush()`,
> sepas que debes guardarlo.

Cuando creas un nuevo objeto entidad, Doctrine no conoce realmente ese objeto hasta que llamas a `persist()`. Pero cuando actualizas una entidad, significa que ya has pedido a Doctrine que consulte por ese objeto. Así que Doctrine ya lo conoce... y cuando llamemos a `flush()`, Doctrine comprobará -de forma automática- ese objeto para ver si se ha realizado algún cambio en él.

## Redirigir a otra página

Así que... ¡hemos guardado con éxito el nuevo recuento de votos en la base de datos! ¿Y ahora qué? Porque... No creo que esta declaración `die` vaya a quedar bien en producción.

Bueno, cada vez que se envía un formulario con éxito, siempre se hace lo mismo: redirigir a otra página. ¿Cómo redirigimos en Symfony? Con`return $this->redirect()` pasando la URL a la que quieras redirigir. Aunque, por lo general, estamos redirigiendo a otra página de nuestro sitio... así que utilizamos un atajo similar llamado `redirectToRoute()` y luego pasamos un nombre de ruta.

Vamos a redirigir a la página del programa. Copia el nombre de la ruta `app_mix_show`, pégalo... y al igual que con la función Twig `path()`, ésta acepta un segundo argumento: una matriz de los comodines de la ruta que debemos rellenar. En este caso, tenemos un comodín`{id}`... así que pasa `id` ajustado a `$mix->getId()`.

[[[ code('755bd00a27') ]]]

Ahora, recuerda: los controladores siempre devuelven un objeto `Response`. Y resulta que una redirección es una respuesta. Es una respuesta que, en lugar de contener HTML, básicamente dice

> Por favor, envía al usuario a esta otra URL

El método `redirectToRoute()` es un atajo que devuelve este objeto de respuesta especial, llamado `RedirectResponse`.

De todos modos, ¡probemos todo el flujo! Refresca, y... ¡lo tienes! Después de votar, acabamos de nuevo en esta página. Y, gracias a Turbo, todo esto sucede a través de llamadas Ajax... lo cual es un buen plus.

El único problema es que... es tan suave que no es súper obvio que mi voto se haya guardado realmente, aparte de ver cómo cambia el número de voto. Sería mejor si mostráramos un mensaje de éxito. Vamos a hacer eso a continuación, aprendiendo sobre los mensajes flash. También vamos a hacer más moderna nuestra entidad `VinylMix` explorando el concepto de modelos inteligentes frente a los anémicos.
