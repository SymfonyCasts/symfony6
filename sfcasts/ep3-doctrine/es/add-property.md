# Añadir nuevas propiedades

En nuestra entidad `VinylMix`, olvidé añadir antes una propiedad: `votes`. Vamos a llevar la cuenta del número de votos a favor o en contra que tiene una determinada mezcla.

## Modificación con make:entity

Bien... ¿cómo podemos añadir una nueva propiedad a una entidad? Bueno, podemos hacerlo a mano: todo lo que tenemos que hacer es crear la propiedad y los métodos getter y setter. Pero, una forma mucho más fácil es volver a nuestro comando favorito `make:entity`:

```terminal-silent
php bin/console make:entity
```

Éste se utiliza para crear entidades, pero también podemos utilizarlo para actualizarlas. Escribe `VinylMix` como nombre de la clase y... ¡ve que existe! Añade una nueva propiedad: `votes`... conviértela en `integer`, di "no" a anulable... y pulsa "intro" para terminar.

¿El resultado final? Nuestra clase tiene una nueva propiedad... y métodos getter y setter a continuación.

[[[ code('c4d23cc903') ]]]

## Generar una segunda migración

Bien, pensemos. Tenemos una tabla `vinyl_mix` en la base de datos... pero aún no tiene la nueva columna `votes`. Tenemos que modificar la tabla para añadirla. ¿Cómo podemos hacerlo? Exactamente igual que antes: ¡con una migración! En tu terminal, ejecuta:

```terminal
symfony console make:migration
```

Luego ve a ver la nueva clase.

[[[ code('4d17ed3986') ]]]

¡Esto es increíble! Dentro del método `up()`, dice

> ALTER TABLE vinyl_mix ADD votes INT NOT NULL

Así que vio nuestra entidad `VinylMix`, comprobó la tabla `vinyl_mix` en la base de datos y generó una diferencia entre ellas. Se dio cuenta de que, para que la base de datos se pareciera a nuestra entidad, tenía que alterar la tabla y añadir esa columna `votes`. Eso es simplemente increíble.

De vuelta al terminal, si ejecutas

```terminal
symfony console doctrine:migrations:list
```

verás que reconoce ambas migraciones y sabe que no ha ejecutado la segunda. Para ello, ejecuta:

```terminal
symfony console doctrine:migrations:migrate
```

Doctrine es lo suficientemente inteligente como para saltarse la primera y ejecutar la segunda. ¡Qué bien!

Cuando despliegues a producción, todo lo que tienes que hacer es ejecutar `doctrine:migrations:migrate`cada vez. Se encargará de ejecutar todas y cada una de las migraciones que la base de datos de producción aún no haya ejecutado.

## Dar valores por defecto a las propiedades

Bien, una cosa más rápida mientras estamos aquí. Dentro de `VinylMix`, la nueva propiedad `votes`tiene como valor por defecto `null`. Pero cuando creamos un nuevo `VinylMix`, tendría mucho sentido poner los votos por defecto a cero. Así que cambiemos esto a `= 0`.

¡Genial! Y si hacemos eso, la propiedad en PHP ya no necesita permitir `null`... así que elimina el `?`. Como estamos inicializando a un entero, esta propiedad siempre será un `int`: nunca será nulo.

[[[ code('cb3daa06fb') ]]]

Pero... Me pregunto... porque he hecho este cambio, ¿tengo que modificar algo en mi base de datos? La respuesta es no. Puedo probarlo ejecutando un comando muy útil:

```terminal
symfony console doctrine:schema:update --dump-sql
```

Es muy parecido al comando `make:migration`... pero en lugar de generar un archivo con el SQL, sólo imprime el SQL necesario para actualizar tu base de datos. En este caso, muestra que nuestra base de datos ya está sincronizada con nuestra entidad.

La cuestión es: si inicializamos el valor de una propiedad en PHP... eso es sólo un cambio en PHP. No cambia la columna en la base de datos ni le da un valor por defecto, lo cual está totalmente bien.

## Autoconfiguración de createdAt

Vamos a inicializar otro campo: `$createdAt`. Sería increíble que algo estableciera automáticamente esta propiedad cada vez que creamos un nuevo objeto `VinylMix`... en lugar de tener que establecerla nosotros manualmente.

Podemos hacerlo creando un método PHP `__construct()` a la vieja usanza. Dentro, digamos `$this->createdAt = new \DateTimeImmutable()`, que por defecto será ahora mismo.

[[[ code('9527d880e7') ]]]

Y ya está Y... ya no necesitamos el `= null` ya que se inicializará aquí abajo... y tampoco necesitamos el `?`, porque siempre será un objeto`DateTimeImmutable`.

[[[ code('0598821470') ]]]

¡Muy bien! Gracias a esto, la propiedad `$createdAt` se establecerá automáticamente cada vez que instanciemos nuestro objeto. Y eso es sólo un cambio de PHP: no cambia la columna en la base de datos.

Muy bien, tenemos una entidad `VinylMix` y la tabla correspondiente. A continuación, vamos a instanciar un objeto `VinylMix` y guardarlo en la base de datos.
