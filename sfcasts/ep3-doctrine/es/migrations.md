# Migraciones

¡Hemos creado una clase de entidad! Pero... eso es todo. La tabla correspondiente aún no existe en nuestra base de datos.

Pensemos. En teoría, Doctrine conoce nuestra entidad, todas sus propiedades y sus atributos `ORM\Column`. Entonces... ¿no debería Doctrine ser capaz de crear esa tabla por nosotros automáticamente? Sí Puede hacerlo.

## El comando make:migration

Cuando instalamos Doctrine anteriormente, venía con una biblioteca de migraciones que es increíble. ¡Échale un vistazo! Cada vez que hagas un cambio en la estructura de tu base de datos, como añadir una nueva clase de entidad, o incluso añadir una nueva propiedad a una entidad existente, debes ir a tu terminal y ejecutar

```terminal
symfony console make:migration
```

En este caso, estoy ejecutando `symfony console` porque esto va a hablar con nuestra base de datos. Ejecuta eso y... ¡perfecto! Se ha creado un nuevo archivo en el directorio `migrations/`con una marca de tiempo para la fecha de hoy. Vamos a comprobarlo! Busca `migrations/` y abre el nuevo archivo.

[[[ code('2a34551803') ]]]

Esto contiene una clase con los métodos `up()` y `down()`... aunque nunca ejecuto las migraciones en sentido "descendente", así que nos centraremos sólo en `up()`. Y... ¡esto es genial! El comando de migraciones vio nuestra entidad `VinylMix`, se dio cuenta de que faltaba su tabla en la base de datos y generó el SQL necesario en Postgres para crearla, incluyendo todas las columnas. Ha sido muy fácil.

## Ejecutar la migración

Bien... ¿cómo ejecutamos esta migración? De vuelta a tu terminal, ejecuta:

```terminal
symfony console doctrine:migrations:migrate
```

Di `y` para confirmar y... ¡precioso! Nos dice que es `Migrating up to`esa versión específica. Parece... ¡que ha funcionado! Para asegurarte, puedes probar con otro comando `bin/console`: `symfony console doctrine:query:sql`
con `SELECT * FROM vinyl_mix`.

```terminal-silent
symfony console doctrine:query:sql 'SELECT * FROM vinyl_mix'
```

Cuando probamos eso... ¡Ups! Perdona mi error tipográfico... aquí no hay nada que ver. Inténtalo de nuevo y... ¡perfecto! ¡No nos da ningún error! Sólo dice que`The query yielded an empty result set`. Si esa tabla no existiera, como`vinyl_foo`, Doctrine nos habría gritado.

Así pues, ¡la migración se ha ejecutado!

## Cómo funcionan las migraciones

Este hermoso sistema merece algunas explicaciones. Ejecuta

```terminal
symfony console doctrine:migrations:migrate
```

de nuevo. ¡Compruébalo! ¡Es lo suficientemente inteligente como para evitar ejecutar esa migración por segunda vez! Sabe que ya lo hizo. Pero... ¿cómo? Prueba a ejecutar otro comando:

```terminal
symfony console doctrine:migrations:status
```

Esto da alguna información general sobre el sistema de migración. La parte más importante está en `Storage` donde dice `Table Name` y `doctrine_migration_versions`.

El asunto es el siguiente: la primera vez que ejecutamos la migración, Doctrine creó esta tabla especial, que almacena literalmente una lista de todas las clases de migración que se han ejecutado. Entonces, cada vez que ejecutamos `doctrine:migrations:migrate`, busca en nuestro directorio `migrations/`, encuentra todas las clases, comprueba en la base de datos cuáles no se han ejecutado ya, y sólo llama a esas. Una vez que las nuevas migraciones terminan, las añade como filas a la tabla `doctrine_migration_versions`.

Puedes visualizar esta tabla ejecutando:

```terminal
symfony console doctrine:migrations:list
```

Ve nuestra única migración y sabe que ya la ha ejecutado. ¡Incluso tiene la fecha!

Esto es genial... pero vamos a ir más allá. A continuación, añadamos una nueva propiedad a nuestra entidad y generemos una segunda migración para añadir la columna.
