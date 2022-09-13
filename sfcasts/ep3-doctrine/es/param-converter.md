# Convertidor de parámetros y 404

Hemos programado el camino feliz. Cuando voy a `/mix/13`, mi base de datos sí encuentra una mezcla con ese id y... la vida es buena. Pero, ¿y si lo cambio a `/99`? Vaya, eso es un error 500: no es algo que queramos que nuestro sitio haga nunca. En realidad debería ser un error 404. Entonces, ¿cómo activamos un 404?

## Activar una página 404

En el método, esta variable `$mix` será un objeto `VinylMix` o null si no se encuentra ninguno. Así que podemos decir `if (!$mix)`, y luego, para desencadenar un 404,`throw $this->createNotFoundException()`. Puedes darle un mensaje si quieres, pero sólo lo verán los desarrolladores.

[[[ code('743daf7827') ]]]

Este `createNotFoundException()`, como su nombre indica, crea un objeto de excepción. Así que en realidad estamos lanzando una excepción aquí... lo que está bien, porque significa que el código que sigue a esto no se ejecutará.

Ahora bien, normalmente si tú o algo de tu código lanza una excepción, provocará un error 500. Pero este método crea un tipo especial de excepción que se corresponde con un 404. ¡Observa! Aquí, en la parte superior derecha, cuando refresco... ¡404!

Por cierto, este no es el aspecto que tendrían las páginas 404 o 500 en producción. Si pasáramos al entorno `prod`, veríamos una página de error bastante genérica y sin detalles. Luego puedes personalizar su aspecto, incluso haciendo estilos separados para los errores 404, 403 Acceso Denegado, o incluso... gasp... 500 errores si algo va realmente mal. Consulta la documentación de Symfony para saber cómo personalizar las páginas de error.

## Convertidor de parámetros: Consulta automática

¡Muy bien! Hemos consultado un único objeto `VinylMix` e incluso hemos gestionado la ruta 404. Pero podemos hacerlo con mucho menos trabajo. ¡Compruébalo! Sustituye el argumento `$id` por un nuevo argumento, de tipo con nuestra clase de entidad `VinylMix`. Llámalo, qué tal, `$mix` para que coincida con la variable de abajo. Luego... elimina la consulta... y también el 404. Y ahora, ni siquiera necesitamos el argumento `$mixRepository`.

[[[ code('11e978077a') ]]]

Esto... merece alguna explicación. Hasta ahora, las "cosas" que se nos "permiten" como argumentos de nuestros controladores son (1) comodines de ruta como `$id` o (2) servicios. Ahora tenemos una tercera cosa. Cuando escribes una clase de entidad, Symfony consultará el objeto automáticamente. Como tenemos un comodín llamado `{id}`, tomará este valor (por ejemplo "99" o "16") y buscará un `VinylMix` cuyo `id`sea igual a ese. El nombre del comodín - `id` en este caso - debe coincidir con el nombre de la propiedad que debe utilizar para la consulta.

Pero si vuelvo y actualizo... ¡no funciona!

> No se puede autoconducir el argumento `$mix` de `MixController::show()`: hace referencia a
> `VinylMix` pero no existe tal servicio.

Sabemos que no es un servicio... así que tiene sentido. Pero... ¿por qué no consulta el objeto como acabo de decir?

Porque... para que esta función funcione, ¡tenemos que instalar otro bundle! Bueno, si estás usando Symfony 6.2 y un DoctrineBundle suficientemente nuevo - probablemente la versión 2.8 - entonces esto debería funcionar sin necesidad de nada más. Pero como estamos usando Symfony 6.1, necesitamos una librería extra.

Busca tu terminal y di:

```terminal
composer require sensio/framework-extra-bundle
```

Este es un bundle lleno de pequeños y bonitos atajos que, para Symfony 6.2, se habrán trasladado al propio Symfony. Así que, con el tiempo, no necesitarás esto.

Y ahora... sin hacer nada más... ¡funciona! ¡Se ha consultado automáticamente el objeto `VinylMix` y la página se renderiza! Y si vas a un ID malo, como`/99`... ¡sí! ¡Compruébalo! ¡Obtenemos un 404! Esta función se llama "ParamConverter"... que se menciona en el error:

> Objeto `VinylMix` no encontrado por la anotación `@ParamConverter`.

En cualquier caso, me encanta esta función. Si necesito consultar varios objetos, como en la acción `browse()`, utilizaré el servicio de repositorio correcto. Pero si necesito consultar un solo objeto en un controlador, utilizo este truco.

A continuación, vamos a hacer posible que nuestras mezclas sean votadas por arriba y por abajo aprovechando un simple formulario. Para ello, por primera vez, actualizaremos una entidad en la base de datos.
