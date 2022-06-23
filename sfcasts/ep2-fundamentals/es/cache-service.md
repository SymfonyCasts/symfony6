# El servicio de caché

Ahora, cuando actualizamos la página de navegación, ¡las mezclas provienen de un repositorio en GitHub! Hacemos una petición HTTP a la API de GitHub, que obtiene este archivo de aquí, llamamos a `$response->toArray()` para decodificar ese JSON en una matriz `$mixes`... y luego lo representamos en la plantilla. Sí, ¡este archivo de GitHub es nuestra falsa base de datos temporal!

Un problema práctico es que ahora cada carga de la página hace una petición HTTP... y las peticiones HTTP son lentas. Si desplegáramos esto en producción, nuestro sitio sería tan popular, por supuesto, que alcanzaríamos rápidamente nuestro límite de la API de GitHub. Y entonces esta página explotaría.

Así que... estoy pensando: ¿y si guardamos en caché el resultado? Podríamos hacer esta petición HTTP y luego almacenar los datos en caché durante 10 minutos, o incluso una hora. ¿Cómo se almacenan las cosas en caché en Symfony? Lo has adivinado: ¡con un servicio! ¿Qué servicio? ¡No lo sé! Así que vamos a averiguarlo.

## Encontrar el servicio de caché

Ejecuta

```terminal
php bin/console debug:autowiring cache
```

para buscar servicios con "caché" en su nombre. Y... ¡sí! De hecho, ¡hay varios! Hay uno llamado `CacheItemPoolInterface`, y otro llamado`StoreInterface`. Algunos de ellos no son exactamente lo que buscamos, pero`CacheItemPoolInterface`, `CacheInterface`, y `TagAwareCacheInterface` son todos servicios diferentes que puedes utilizar para el almacenamiento en caché. Todos hacen efectivamente lo mismo... pero el más fácil de usar es `CacheInterface`.

Así que vamos a coger ese .... haciendo nuestro elegante truco de autoconexión Añade otro argumento a nuestro método escrito con `CacheInterface` (asegúrate de obtener el de`Symfony\Contracts\Cache`) y llámalo, qué tal, `$cache`:

[[[ code('6b4b596a2d') ]]]

Para utilizar el servicio `$cache`, copia estas dos líneas de antes, bórralas y sustitúyelas por `$mixes = $cache->get()`, como si fueras a sacar alguna clave de la caché. Podemos inventar la clave de la caché que queramos: 
¿qué tal `mixes_data`.

El objeto caché de Symfony funciona de una manera única. Llamamos a `$cache->get()` y le pasamos esta clave. Si ese resultado ya existe en la caché, se devolverá inmediatamente. Si aún no existe en la caché, entonces llamará a nuestro segundo argumento, que es una función. Aquí, nuestro trabajo es devolver los datos que deben ser almacenados en la caché. Pega las dos líneas de código que hemos copiado antes. Este `$httpClient`no está definido, así que tenemos que añadir `use ($httpClient)` para que entre en el ámbito.

Ya está Y en lugar de establecer la variable `$mixes`, simplemente `return` esta línea`$response->toArray()`:

[[[ code('c6c78aea17') ]]]

Si no has utilizado antes el servicio de caché de Symfony, esto puede parecer extraño, pero a mí me encanta La primera vez que actualicemos la página, todavía no habrá ningún `mixes_data`en la caché. Así que llamará a nuestra función, devolverá el resultado y el sistema de caché lo almacenará en la caché. La próxima vez que actualicemos la página, la clave estará en la caché y devolverá el resultado inmediatamente. Así que no necesitamos ninguna sentencia "if" para ver si algo ya está en la caché... ¡sólo esto!

## Depuración con el Perfilador de Caché

Pero... ¿se mezclará? Vamos a averiguarlo. Refresca y... ¡bien! El primer refresco seguía haciendo la petición HTTP con normalidad. Abajo, en la barra de herramientas de depuración de la web, podemos ver que hubo tres llamadas a la caché y una escritura en la caché. Abre esto en una nueva pestaña para saltar a la sección de caché del perfilador.

Genial: esto nos muestra que hubo una llamada a la caché para `mixes_data`, una escritura en la caché y un fallo en la caché. Un "miss" de la caché significa que se ha llamado a nuestra función y se ha escrito en la caché.

En la siguiente actualización, observa este icono de aquí. Desaparece Eso es porque no hubo ninguna petición HTTP. Si vuelves a abrir el perfil de la caché, esta vez hubo una lectura y un acierto. Ese acierto significa que el resultado se ha cargado desde la caché y no ha hecho ninguna petición HTTP. ¡Eso es exactamente lo que queríamos!

## Configurar el tiempo de vida de la caché

Ahora te preguntarás: ¿cuánto tiempo permanecerá esta información en la caché? Ahora mismo... para siempre. Ooooh. Ese es el valor por defecto.

Para que caduque antes que para siempre, dale a la función un argumento `CacheItemInterface`-asegúrate de pulsar "tab" para añadir esa declaración de uso- y llámala`$cacheItem`. Ahora podemos decir `$cacheItem->expiresAfter()` y, para hacerlo más fácil, decir `5`:

[[[ code('205124d7cf') ]]]

El artículo expirará después de 5 segundos.

## Borrar la caché

Por desgracia, si intentamos esto, el elemento que ya está en la caché está configurado para no caducar nunca. Así que... esto no funcionará hasta que borremos la caché. Pero... ¿dónde se almacena la caché? ¡Otra gran pregunta! Hablaremos más sobre esto en un segundo... pero, por defecto, se almacena en `var/cache/dev/`... junto con un montón de otros archivos de caché que ayudan a Symfony a hacer su trabajo.

Podríamos borrar este directorio manualmente para limpiar la caché... ¡pero Symfony tiene una forma mejor! Es, por supuesto, otro comando de `bin/console`.

Symfony tiene un montón de diferentes "categorías" de caché llamadas "cache pools". Si ejecutas

```terminal
php bin/console cache:pool:list
```

verás todas ellas. La mayoría de ellas están pensadas para que Symfony las utilice internamente. El pool de caché que estamos utilizando se llama `cache.app`. Para borrarla, ejecuta:

```terminal
php bin/console cache:pool:clear cache.app
```

¡Eso es todo! Esto no es algo que necesites hacer muy a menudo, pero es bueno saberlo, por si acaso.

Bien, comprueba esto. Cuando refrescamos... obtenemos un fallo en la caché y puedes ver que hizo una llamada HTTP. Pero si volvemos a actualizar rápidamente... ¡ya no está! Vuelve a actualizar y... ¡ha vuelto! Eso es porque los cinco segundos acaban de expirar.

Bien, equipo: ahora estamos aprovechando un servicio de cliente HTTP y un servicio de caché... ambos preparados para nosotros por uno de nuestros bundles para que podamos simplemente... ¡utilizarlos!

Pero tengo una pregunta. ¿Qué pasa si necesitamos controlar estos servicios? Por ejemplo, ¿cómo podríamos decirle al servicio de caché que, en lugar de guardar cosas en el sistema de archivos de este directorio, queremos almacenarlas en Redis... o en memcache? Vamos a explorar la idea de controlar nuestros servicios mediante la configuración.
