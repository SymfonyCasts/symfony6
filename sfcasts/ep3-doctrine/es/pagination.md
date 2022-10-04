# Paginación

Al final, esta página se va a hacer superlarga. Cuando tengamos mil mezclas, ¡probablemente ni siquiera se cargue! Podemos solucionarlo añadiendo la paginación. ¿Doctrine tiene la capacidad de paginar los resultados? Sí, la tiene Aunque yo suelo instalar otra biblioteca que añade más funciones además de las de Doctrine.

Busca tu terminal y ejecuta:

```termninal
composer require babdev/pagerfanta-bundle pagerfanta/doctrine-orm-adapter
```

Esto instala un bundle de Pagerfanta, que es una envoltura de una biblioteca realmente buena llamada Pagerfanta. Pagerfanta puede paginar muchas cosas, como los resultados de Doctrine, los resultados de Elasticsearch y mucho más. También instalamos su adaptador ORM de Doctrine, que nos dará todo lo que necesitamos para paginar nuestros resultados de Doctrine. En este caso, cuando ejecutamos

```terminal
git status
```

añadió un bundle, pero la receta no necesitó hacer nada más. ¡Genial! Entonces, ¿cómo funciona esta biblioteca?

Abre `src/Controller/VinylController` y busca la acción `browse()`. En lugar de consultar todas las mezclas, como estamos haciendo ahora, vamos a decirle a la biblioteca Pagerfanta en qué página se encuentra el usuario, cuántos resultados debe mostrar por página, y entonces nos consultará los resultados correctos.

## Devolver un QueryBuilder

Para que esto funcione, en lugar de llamar a `findAllOrderedByVotes()` y obtener todos los resultados, tenemos que llamar a un método de nuestro repositorio que devuelva un QueryBuilder. Abre `src/Repository/VinylMixRepository` y desplázate hasta`findAllOrderedByVotes()`. Por el momento sólo estamos utilizando este método, así que cámbiale el nombre a `createOrderedByVotesQueryBuilder()`... y esto devolverá ahora un `QueryBuilder` - el de Doctrine ORM. Eliminaré la documentación de PHP en la parte superior... y lo único que tenemos que hacer aquí abajo es eliminar`getQuery()` y `getResult()` para que sólo devolvamos `$queryBuilder`.

[[[ code('6c1a403e0d') ]]]

En `VinylController`, cambia esto por`$queryBuilder = $mixRepository->createOrderedByVotesQueryBuilder($slug)`

[[[ code('46e927c5d9') ]]]

Inicializar Pagerfanta son dos líneas. Primero, crea el adaptador -`$adapter = new QueryAdapter()` y pásale `$queryBuilder`. Luego crea el objeto `Pagerfanta` con`$pagerfanta = Pagerfanta::createForCurrentPageWithMaxPerPage()`

Esto es un bocado. Pásale el `$adapter`, la página actual - en este momento, voy a codificar en duro `1` - y finalmente el máximo de resultados por página que queremos. Vamos a utilizar `9` ya que nuestras mezclas aparecen en tres columnas.

[[[ code('269af6c02f') ]]]

Ahora que tenemos este objeto Pagerfanta, vamos a pasarlo a la plantilla en lugar de `mixes`. Sustitúyelo por una nueva variable llamada `pager` ajustada a `$pagerfanta`.

[[[ code('e6c7cdde64') ]]]

Lo bueno de este objeto `$pagerfanta` es que puedes hacer un bucle sobre él. Y en cuanto lo hagas, ejecutará la consulta correcta para obtener sólo los resultados de esta página. En `templates/vinyl/browse.html.twig`, en lugar de `{% for mix in mixes %}`, di`{% for mix in pager %}`.

[[[ code('27de4f1c48') ]]]

Eso es todo. Cada resultado del bucle seguirá siendo un objeto `VinylMix`.

Si vamos y recargamos... ¡lo tenemos! Muestra nueve resultados: ¡los resultados de la página 1!

## Enlace a la página siguiente

Lo que necesitamos ahora son enlaces a las páginas siguientes y anteriores... y esta biblioteca también puede ayudarnos con eso. De vuelta a tu terminal, ejecuta:

```terminal
composer require pagerfanta/twig
```

Una de las cosas más complicadas de la biblioteca Pagerfanta es que, en lugar de ser una biblioteca gigante que tiene todo lo que necesitas, está dividida en un montón de bibliotecas más pequeñas. Así que si quieres el soporte del adaptador ORM, tienes que instalarlo como hemos hecho antes. Si quieres el soporte de Twig para añadir enlaces, también tienes que instalarlo. Sin embargo, una vez que lo hagas, es bastante sencillo.

De vuelta a nuestra plantilla, busca el objeto `{% endfor %}`, y justo después, di`{{ pagerfanta() }}`, pasándole el objeto `pager`.

[[[ code('436293d547') ]]]

¡Compruébalo! Cuando actualizamos... ¡tenemos enlaces en la parte inferior! Son... feos, pero lo arreglaremos en un momento.

## Leer la página actual

Si haces clic en el enlace "Siguiente", arriba en nuestra URL, vemos `?page=2`. Aunque... los resultados no cambian realmente. Seguimos viendo los mismos resultados de la página 1. Y... eso tiene sentido. Recuerda que en `VinylController`, codifiqué la página actual en `1`. Así que, aunque tengamos `?page=2` aquí arriba, Pagerfanta sigue pensando que estamos en la Página 1.

Lo que tenemos que hacer es leer este parámetro de consulta y pasarlo como este segundo argumento ¡No hay problema! ¿Cómo leemos los parámetros de consulta? Bueno, es información de la petición, así que necesitamos el objeto `Request`.

Justo antes de nuestro argumento opcional, añade un nuevo argumento `$request` de tipo`Request`: el de HttpFoundation. Ahora, aquí abajo, en lugar de `1`, di `$request->query` (así es como se obtienen los parámetros de consulta), con`->get('page')`... y por defecto esto es `1` si no hay `?page=` en la URL.

[[[ code('e22d104f10') ]]]

Por cierto, si quieres, también puedes añadir `{page}` aquí arriba. De este modo, Pagerfanta pondrá automáticamente el número de página dentro de la URL en lugar de establecerlo como parámetro de consulta.

Si nos dirigimos y refrescamos... ahora mismo, tenemos `?page=2`. Aquí abajo... ¡sabe que estamos en la página 2! Si vamos a la siguiente página... ¡sí! ¡Vemos un conjunto diferente de resultados!

## Estilizando los enlaces de paginación

Aunque, esto sigue siendo súper feo. Afortunadamente, el bundle nos da una forma de controlar el marcado que se utiliza para los enlaces de paginación. E incluso viene con soporte automático para el marcado compatible con CSS de Bootstrap. Sólo tenemos que decirle al bundle que lo utilice.

Así que... tenemos que configurar el bundle. Pero... el bundle no nos ha dado ningún archivo de configuración nuevo cuando se ha instalado. No pasa nada No todos los bundles nuevos nos dan archivos de configuración. Pero en cuanto necesites uno, ¡crea uno! Como este bundle se llama`BabdevPagerfantaBundle`, voy a crear un nuevo archivo llamado`babdev_pagerfanta.yaml`. Como aprendimos en el último tutorial, el nombre de estos archivos no es importante. Lo importante es la clave raíz, que debe ser`babdev_pagerfanta`. Para cambiar la forma en que se muestra la paginación, añade `default_view: twig`y, a continuación, `default_twig_template`, que debe ser`@BabDevPagerfanta/twitter_bootstrap5.html.twig`.

[[[ code('80afb85b20') ]]]

Como cualquier otra configuración, no hay forma de que sepas que ésta es la configuración correcta simplemente adivinando. Tienes que consultar la documentación.

Si volvemos y refrescamos... eh, no ha cambiado nada. Este es un pequeño error que a veces se encuentra en Symfony cuando se crea un nuevo archivo de configuración.  Symfony no se dio cuenta... y por eso no sabía que tenía que reconstruir su caché. Esta es una situación súper rara, pero si alguna vez crees que puede estar ocurriendo, es bastante fácil borrar manualmente la caché ejecutando:

```terminal
php bin/console cache:clear
```

Y... oh... explota. Seguramente te habrás dado cuenta de por qué. ¡Me encanta este error!

> No hay ninguna extensión capaz de cargar la configuración para "baberdev_pagerfanta"

Se supone que es `babdev_pagerfanta`. ¡Ups! Y ahora... ¡perfecto! Está contento. Y cuando refrescamos... ¡lo ve! En un proyecto real, probablemente querremos añadir algo de CSS adicional para hacer este "modo oscuro"... pero ya lo tenemos.

Bien, equipo, ¡ya hemos terminado! Como extra, vamos a refactorizar esta paginación para convertirla en un scroll eterno impulsado por JavaScript... ¡excepto el giro argumental! Vamos a hacerlo sin escribir una sola línea de JavaScript. Eso a continuación.
