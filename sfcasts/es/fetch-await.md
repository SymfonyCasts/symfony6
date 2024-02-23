# Modernizar con fetch() y await

Este capítulo no está relacionado con la modernización de Symfony. Pero el resto de nuestro código -incluido JavaScript- ¡también merece modernizarse!

## Utilizar fetch() en lugar de axios

Dentro de `song-controls_controller.js`, originalmente utilizaba `axios` para hacer llamadas Ajax. 

[[[ code('16975e1a74') ]]]

Ya no lo hago. En su lugar, utiliza la función incorporada `fetch()`.

Elimina `axios` con:

```terminal
php bin/console importmap:remove axios
```

Desaparece de `importmap.php`. Luego borra el `import`... y este comentario mientras estamos aquí. Sustituye `axios.get()` por sólo `fetch()`. Luego, para ver si esto funciona, `console.log(response)`.

[[[ code('36fbd09a05') ]]]

En la tierra de los navegadores, pulsa el botón de reproducción para activar el método. Genial! Las dos últimas líneas no funcionan, ¡pero vemos la respuesta! Ha hecho una llamada Ajax.

Cuando escribí esto originalmente, utilicé `.then()` para manejar la Promesa. Ya no suelo utilizarlo para manejar código asíncrono. En su lugar, utilizo el más sencillo `await`.

## Uso de await y async

Delante de `fetch`, pon `const response = await fetch()`. Luego copia el interior de la llamada de retorno y ponlo justo después.

[[[ code('d8eb83be1e') ]]]

Esto dice: haz la llamada a `fetch()`, espera a que termine y luego ejecuta este código. Es mucho más sencillo de leer y escribir.

Aunque, probablemente te hayas fijado en mi editor enfadado:

> el operador await sólo puede utilizarse en una función asíncrona.

Para utilizar `await`, tenemos que añadir `async` antes de la función en la que estamos directamente. No entraré en detalles, pero esto anuncia que nuestra función es ahora asíncrona. Si la llamaras y quisieras el valor de retorno, también necesitarías `await` esa llamada.

Pero en nuestro caso, Stimulus está llamando a este método... y definitivamente no le importa nuestro valor de retorno. Así que añadir `async` no cambia nada.

Cuando lo probamos... el mismo resultado, sin la llamada de retorno.

Así que terminemos con esto: `const data = await response.json()`.

Esto toma el JSON de la respuesta de nuestra ruta API y lo convierte en un objeto. Y sí, también es una función asíncrona, ¡así que `await` vuelve a ser útil! A continuación, pasa `data.url` a `Audio`.

[[[ code('7f91de49bd') ]]]

Luego celebra, ese dulce, dulce Rickroll. Código moderno, sin sistema de compilación: la vida es buena.

Ahora que estamos actualizados, vamos a dar una vuelta por algunas de mis nuevas funciones favoritas, empezando por las bondades del autocableado que podrían significar que nunca volverás a editar`services.yaml`.
