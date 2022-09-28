# Extensiones de la Doctrine: Timestampable

Me gusta mucho añadir el comportamiento timestampable a mis entidades. Es cuando tienes propiedades`$createdAt` y `$updatedAt` que se establecen automáticamente. Simplemente... ayuda a llevar la cuenta de cuándo sucedieron las cosas. Hemos añadido `$createdAt` y lo hemos establecido inteligentemente a mano en el constructor. ¿Pero qué pasa con `$updatedAt`? Doctrine tiene un impresionante sistema de eventos, y podríamos engancharnos a él para ejecutar un código en la "actualización" que establezca esa propiedad. Pero hay una biblioteca que ya hace eso. Así que vamos a instalarla.

## Instalación de stof/doctrine-extensions-bundle

En tu terminal, ejecuta:

```terminal
composer require stof/doctrine-extensions-bundle
```

Esto instala un pequeño bundle, que es una envoltura de una biblioteca llamada DoctrineExtensions. Como muchos paquetes, éste incluye una receta. Pero ésta es la primera receta que proviene del repositorio "contrib". Recuerda: Symfony tiene en realidad dos repositorios de recetas. Está el principal, que está estrechamente vigilado por el equipo principal de Symfony. Luego hay otro llamado `recipes-contrib`. Hay algunos controles de calidad en ese repositorio, pero está mantenido por la comunidad. La primera vez que Symfony instala una receta del repositorio "contrib", te pregunta si está bien. Voy a decir `p` por "sí permanentemente". Luego ejecuta:

```terminal
get status
```

¡Impresionante! Ha habilitado un bundle y ha añadido un nuevo archivo de configuración que veremos en un segundo.

## Habilitación de Timestampable

Obviamente, este bundle tiene su propia documentación. Puedes buscar`stof/doctrine-extensions-bundle` y encontrarla en Symfony.com. Pero la mayor parte de la documentación está en la biblioteca DoctrineExtensions subyacente... que contiene un montón de comportamientos realmente interesantes, incluyendo "sluggable" y "timestampable". Vamos a añadir primero "timestampable".

Primer paso: entra en `config/packages/` y abre el archivo de configuración que acaba de añadir. Aquí, añade `orm` porque estamos utilizando Doctrine ORM, luego `default`, y por último `timestampable: true`.

[[[ code('05164f0e85') ]]]

Esto no hará realmente nada todavía. Sólo activa un oyente de Doctrine que buscará entidades que soporten timestampable cada vez que se inserte o actualice una entidad. ¿Cómo hacemos que nuestro `VinylMix` admita timestampable? La forma más sencilla (y la que a mí me gusta hacer) es mediante un rasgo.

En la parte superior de la clase, di `use TimestampableEntity`.

[[[ code('11c47f274d') ]]]

Eso es todo. ¡Ya hemos terminado! ¡Hora de comer!

Para entender esta magia negra, mantén pulsado "cmd" o "ctrl" y haz clic en `TimestampableEntity`. Esto añade dos propiedades: `createdAt` y`updatedAt`. Y son campos normales, como el `createdAt` que teníamos antes. También tiene métodos getter y setter aquí abajo, igual que tenemos en nuestra entidad.

La magia es este atributo `#[Gedmo\Timestampable()]`. Esto dice que

> esta propiedad debe establecerse `on: 'update'`

y

> esta propiedad debe establecerse `on: 'create'`.

Gracias a este rasgo, ¡obtenemos todo esto gratis! Y... ya no necesitamos nuestra propiedad`createdAt`... porque ya vive en el trait. Así que elimina la propiedad... y el constructor... y aquí abajo, elimina los métodos getter y setter ¡Limpieza!

## Añadir la migración

El trait tiene una propiedad `createdAt` como la que teníamos antes, pero además añade un campo`updatedAt`. Así que tenemos que crear una nueva migración para eso. Ya conoces el procedimiento. En tu terminal, ejecuta:

```terminal
symfony console make:migration
```

Entonces... vamos a comprobar ese archivo... para asegurarnos de que queda como esperamos. Veamos aquí... ¡sí! Tenemos `ALTER TABLE vinyl_mix ADD updated_at`. Y aparentemente la columna `created_at` será un poco diferente a la que teníamos antes.

[[[ code('6ab7d128f7') ]]]

## Cuando las migraciones fallan

Bien, vamos a ejecutarlo:

```terminal
symfony console doctrine:migrations:migrate
```

Y... ¡falla!

> `[...] column "updated_at" of relation "vinyl_mix" contains null values`.

Esto es un `Not null violation`... lo cual tiene sentido. Nuestra base de datos ya tiene un montón de registros... así que cuando intentamos añadir una nueva columna `updated_at` que no permite valores nulos... se vuelve loco.

Si el estado actual de nuestra base de datos ya estuviera en producción, tendríamos que ajustar esta migración para dar a la nueva columna un valor por defecto para esos registros existentes. Entonces podríamos volver a cambiarla para que no permita nulos. Para saber más sobre el manejo de migraciones fallidas, consulta un [capítulo de nuestro tutorial de Doctrine de Symfony 5](https://symfonycasts.com/screencast/symfony5-doctrine/bad-migrations).

Pero como todavía no tenemos una base de datos de producción que contenga `viny_mix` filas, podemos tomar un atajo: eliminar la base de datos y empezar de nuevo con cero filas. Para ello, ejecuta

```terminal
symfony console doctrine:database:drop --force
```

para eliminar completamente nuestra base de datos. Y vuelve a crearla con

```terminal
symfony console doctrine:database:create
```

En este punto, tenemos una base de datos vacía sin tablas, incluso la tabla de migraciones ha desaparecido. Así que podemos volver a ejecutar todas nuestras migraciones desde el principio. Hazlo:

```terminal
symfony console doctrine:migrations:migrate
```

¡Genial! Se han ejecutado tres migraciones: todas con éxito.

De vuelta a nuestro sitio, si vamos a "Examinar mezclas", está vacío... porque hemos vaciado nuestra base de datos. Así que vayamos a `/mix/new` para crear la mezcla ID 1... y luego refresquemos unas cuantas veces más. Ahora dirígete a `/mix/7`... y sube la nota, lo que actualizará ese`VinylMix`.

De acuerdo ¡Veamos si la marca de tiempo ha funcionado! Comprueba la base de datos ejecutando:

```terminal
symfony console doctrine:query:sql 'SELECT * FROM vinyl_mix WHERE id = 7'
```

Y... ¡impresionante! El `created_at` está configurado y luego el `updated_at` está configurado justo unos segundos después de que hayamos votado la mezcla. Funciona. Ahora podemos añadir fácilmente `timestampable` a cualquier entidad nueva en el futuro, simplemente añadiendo ese rasgo.

A continuación: vamos a aprovechar otro comportamiento: sluggable. Esto nos permitirá crear URLs más elegantes guardando automáticamente una versión segura de la URL del título en una nueva propiedad.
