# Consulta de la base de datos

Ahora que hemos guardado algunas cosas en la base de datos, ¿cómo podemos leerlas o consultarlas? Una vez más, al menos para las cosas sencillas, Doctrine no quiere que te preocupes de consultarlas. En lugar de eso, simplemente pedimos a Doctrine los objetos que queremos.

Dirígete a `src/Controller/VinylController.php` y busca la acción `browse()`.

[[[ code('0ff369b74b') ]]]

Aquí, estamos cargando todo el `$mixes` en nuestro proyecto... y actualmente lo estamos haciendo a través de esta clase de servicio `MixRepository` que creamos en el último episodio. Esta clase habla con un repositorio de GitHub y lee desde un archivo de texto codificado.

Vamos a dejar de usar este `MixRepository` y en su lugar cargaremos estos `$mixes`desde la base de datos.

## Consulta a través del Gestor de Entidades

Bien: para guardar los objetos, aprovechamos el servicio `EntityManagerInterface`, que es el más importante con diferencia en Doctrine. Además, este servicio puede consultar los objetos. Aprovechemos eso. Añade un nuevo argumento a `browse()`, de tipo `EntityManagerInterface`... y llámalo `$entityManager`.

[[[ code('58ed044c51') ]]]

A continuación, sustituye la línea `$mixes` por dos líneas. Empieza con`$mixRepository = $entityManager->getRepository()` pasándole el nombre de la clase desde la que queremos consultar. Sí, pensamos en consultar desde una clase de entidad, no desde una tabla. En este caso, queremos consultar desde `VinylMix::class`.

Hablaremos más sobre este concepto de repositorio en un minuto. A continuación, para obtener las mezclas en sí, digamos `$mixes = $mixRepository->` y llamemos a uno de los métodos de la misma: `findAll()`.

Para ver qué nos da esto, vamos a `dd($mixes)`.

[[[ code('c3c83f32f3') ]]]

Bien, ¡es hora de probar! Gira, vuelve a la página de inicio, haz clic en "Examinar las mezclas" para realizar esa acción, y... ¡voilá! ¡Obtenemos seis resultados! Y cada uno de ellos, lo más importante, es un objeto `VinylMix`.

Entre bastidores, Doctrine consultó la tabla y las columnas. Pero en lugar de darnos esos datos en bruto, los puso en objetos y nos los dio, lo cual es mucho más agradable.

## Trabajar con objetos en Twig

Si eliminamos el `dd()`... este array de objetos `VinylMix` se pasará a la plantilla, en lugar del array de datos que teníamos antes. Pero... la página sigue funcionando. Sin embargo, estas imágenes están rotas porque, al parecer, el servicio que estoy utilizando para cargarlas no funciona en este momento. Ah... las alegrías de la grabación de vídeo. ¡Pero eso no nos detendrá!

El hecho de que todos los datos se sigan renderizando sin errores es... en realidad un poco por suerte. Cuando renderizamos la plantilla - `templates/vinyl/browse.html.twig` - hacemos un bucle sobre todos los `mixes`. La plantilla funciona porque el antiguo archivo de texto del repositorio de GitHub tenía las mismas claves (como `title`, `trackCount` y `genre`) que nuestra clase`VinylMix`.

[[[ code('542a2ad0f1') ]]]

Sin embargo, aquí ocurre algo interesante. Cuando decimos `mix.genre`,`mix` es ahora un objeto... y esta propiedad `genre` es privada. Eso significa que no podemos acceder a ella directamente. Pero Twig es inteligente. Se da cuenta de que es privada y busca un método `getGenre()`. Así que en nuestra plantilla, decimos `mix.genre`, pero en realidad, llama al método `getGenre()`. Eso es bastante asombroso.

## Visualización de las consultas de la página

¿Sabes qué más es impresionante? ¡Podemos ver las consultas que hace cualquier página! En la barra de herramientas de depuración de la web, Doctrine nos ofrece un nuevo y elegante icono. Oooo. Y si hacemos clic en él... ¡tah dah! Hay una consulta a la base de datos... e incluso podemos ver de qué se trata. También se puede ver una versión formateada de la misma... aunque tengo que actualizar la página para que esto funcione... porque la biblioteca Turbo JavaScript que instalamos en el primer tutorial no siempre se lleva bien con esta zona del perfilador. De todos modos, también podemos ver una versión ejecutable de la consulta o ejecutar "Explicar" sobre ella.

## El "Repositorio"

Muy bien, de vuelta al controlador, aunque podemos consultar a través de`EntityManagerInterface`, normalmente consultamos a través de algo llamado repositorio.`dd()` este objeto `$mixRepository` para obtener más información sobre él 

[[[ code('0c8f951058') ]]]

Luego vuelve a la página `/browse` y... es un objeto`App\Repository\VinylMixRepository`. Oye, ¡conocemos esa clase! Vive en nuestro código, en el directorio `src/Repository/`. Fue generada por MakerBundle.

Dentro del atributo `ORM\Entity` sobre nuestra clase de entidad, MakerBundle generó una opción `repositoryClass` que apunta a esto. Gracias a esta configuración, nuestra entidad, `VinylMix`, está vinculada a `VinylMixRepository`. Así que cuando le pides a Doctrine que nos dé el repositorio de la clase `VinylMix`, sabe que debe devolver el objeto`VinylMixRepository`.

El repositorio de una entidad lo sabe todo sobre cómo consultar sus datos. Y, sin que nosotros hagamos nada, ya tiene un montón de métodos útiles para las consultas básicas, como `findAll()`, `findOneBy()` y varios más. Dentro de un rato, aprenderemos a añadir nuevos métodos al repositorio para realizar consultas personalizadas.

De todos modos, `VinylMixRepository` es en realidad un servicio en el contenedor... así que podemos obtenerlo más fácilmente autoconectándolo directamente. Añade un argumento`VinylMixRepository $mixRepository`... y entonces no necesitaremos esta línea. Esto es más sencillo... ¡y sigue funcionando!

[[[ code('a79c813794') ]]]

La conclusión es ésta: si quieres consultar una tabla, lo harás a través del repositorio de la entidad cuyos datos necesitas.

Siguiente: El hecho de que hayamos cambiado nuestro código para cargarlo desde la base de datos y no hayamos tenido que actualizar nuestra plantilla Twig en absoluto fue algo impresionante Y por cortesía de un poco de magia Twig. Vamos a hablar más de esa magia y a crear una propiedad virtual que podemos imprimir en la plantilla.
