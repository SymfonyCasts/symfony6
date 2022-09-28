# URLs limpias con Sluggable

Utilizar un ID de base de datos en tu URL es... un poco cutre. Es más habitual utilizar slugs. Un slug es una versión segura para la URL del nombre o título de un elemento. En este caso, el título de nuestra mezcla.

Para que esto sea posible, sólo tenemos que hacer una cosa: dar a nuestra clase `VinylMix` una propiedad `slug` que contenga esta cadena segura para la URL. Entonces, será súper fácil consultarla. El único truco es que... algo tiene que mirar el título de la mezcla y establecer esa propiedad `slug` cada vez que se guarde una mezcla. Y, lo ideal sería que eso ocurriera automáticamente... porque me siento un poco perezoso... y no quiero hacer ese trabajo manualmente en todas partes. Pues bien, ese es el trabajo del comportamiento ralentizable de las extensiones de Doctrine.

## Activar la escucha lenta

Vuelve a `config/packages/stof_doctrine_extensions.yaml` y añade`sluggable: true`

[[[ code('35894f3389') ]]]

Una vez más, esto habilita un oyente que mirará cada entidad, cada vez que se guarde una, para ver si el comportamiento sluggable está activado en ella. ¿Cómo lo hacemos?

## Añadiendo la propiedad Slug

En primer lugar, necesitamos una propiedad `slug` en nuestra entidad. Para añadirla, en tu terminal, ejecuta

```terminal
php bin/console make:entity
```

Actualizar `VinylMix` para añadir un nuevo campo `slug`. Será una cadena, y limitémosla a 100 caracteres. Haz también que no sea anulable: debe ser obligatorio en la base de datos. Y ya está Pulsa "intro" una vez más para terminar.

Esto, como es lógico, ha añadido una propiedad `slug`... además de los métodos `getSlug()` y `setSlug()`en la parte inferior.

[[[ code('48c554d427') ]]]

Una cosa que el comando `make:entity` no te pregunta es si quieres que la propiedad sea única en la base de datos. En el caso de `slug`, sí queremos que sea única, así que añade `unique: true`. Eso añadirá una restricción `unique` en la base de datos para asegurarnos de que nunca tengamos duplicados.

[[[ code('87b124d933') ]]]

Antes de pensar en cualquier magia de la babosa, genera una migración para la nueva propiedad:

```terminal
symfony console make:migration
```

Como siempre, abriré ese nuevo archivo para asegurarme de que se ve bien. Y... ¡lo está! Añade `slug` incluyendo un `UNIQUE INDEX` para `slug`. Y cuando lo ejecutamos con

```terminal
symfony console doctrine:migrations:migrate
```

explota... por la misma razón que la última vez: `Not null violation`. Estamos añadiendo una nueva columna `slug` a nuestra tabla que no es nula... lo que significa que cualquier registro existente no funcionará. Como dije en el capítulo anterior, si tu base de datos ya está en producción, tendrías que arreglar esto. Pero como la nuestra no lo está, podemos hacer trampa y reiniciar la base de datos como hicimos antes:

```terminal
symfony console doctrine:database:drop --force
```

Entonces:

```terminal
symfony console doctrine:database:create
```

Por último, vuelve a ejecutar todas las migraciones desde el principio:

```terminal
symfony console doctrine:migrations:migrate
```

Y... ¡sí! 4 migraciones ejecutadas.

## Añadir el atributo Sluggable

Llegados a este punto, hemos activado el oyente sluggable y hemos añadido una columna `slug`. Pero aún nos falta un paso. Lo probaré yendo a `/mix/new` y... error:

> [...] la columna "slug" de la relación "vinyl_mix" viola la restricción not-null.

Sí Todavía no hay nada que establezca la propiedad `slug`. Para indicar a la biblioteca de extensiones que se trata de una propiedad `slug` que debe establecer automáticamente, tenemos que añadir -sorpresa- un atributo Se llama `#[Slug]`. Pulsa "tab" para autocompletarlo, lo que añadirá la declaración `use` que necesitas en la parte superior. A continuación, di `fields`, que se establece en una matriz, y dentro, sólo `title`.

[[[ code('1fd8df0329') ]]]

Esto dice:

> utiliza el campo "título" para generar este slug.

Y ahora... ¡parece que funciona! Si comprobamos la base de datos...

```terminal
symfony console doctrine:query:sql 'SELECT * FROM vinyl_mix'
```

¡Woohoo! El `slug` está aquí abajo... y puedes ver que la biblioteca también es lo suficientemente inteligente como para añadir un poco de `-1`, `-2`, `-3` para mantenerlo único.

## Actualizando nuestra ruta para usar {slug}

Ahora que tenemos esta columna `slug`, en `MixController`, vamos a hacer que nuestra ruta sea más moderna utilizando `{slug}`.

[[[ code('28ca5110ee') ]]]

¿Qué más tenemos que cambiar aquí? Nada Como el comodín de la ruta se llama ahora `{slug}`, Doctrine utilizará este valor para consultar la propiedad `slug`. ¡Genial!

## Actualizar los enlaces a la ruta

Sin embargo, tenemos que actualizar los enlaces que generemos a esta ruta. Observa: copia el nombre de la ruta - `app_mix_show` - y busca dentro de este archivo. ¡Sí! Lo utilizamos aquí abajo para redirigirnos después de votar. Ahora, en lugar de pasar el comodín `id`, pasa `slug` ajustado a `$mix->getSlug()`.

[[[ code('234578269e') ]]]

Y si has buscado, hay otro lugar donde generamos una URL a esta ruta:`templates/vinyl/browse.html.twig`. Aquí mismo, tenemos que cambiar el enlace de la página "Examinar" a `slug: mix.slug`.

[[[ code('34ca4a0c54') ]]]

¡Hora de probar! Déjame refrescar un par de veces... luego vuelve a la página de inicio... haz clic en "Examinar mezclas", y... ¡ahí está nuestra lista! Si hacemos clic en una de estas mezclas... ¡hermoso! Ha utilizado el slug y ha consultado a través del slug. La vida es buena.

Vale, ahora, para añadir datos ficticios y poder utilizar el sitio, hemos creado esta acción `new`. Pero esa es una forma bastante pobre de manejar datos ficticios: es manual, requiere refrescar la página y, aunque tenemos algo de aleatoriedad, ¡crea datos aburridos!

Así que, a continuación, vamos a añadir un sistema de fijación de datos adecuado para remediarlo.
