# Nuevo Bundle, nuevo servicio: KnpTimeBundle

En nuestro sitio, puedes crear tu propia mezcla de vinilo. (O podrás hacerlo con el tiempo... ahora mismo, este botón no hace nada). Pero otra gran característica de nuestro sitio es la posibilidad de explorar las mezclas de otros usuarios.

Ahora que lo estoy viendo, podría ser útil si pudiéramos ver cuándo se creó cada mezcla.

Si no recuerdas en qué parte de nuestro código se creó esta página, puedes utilizar un truco. Abajo, en la barra de herramientas de depuración de la web, pasa el ratón por encima del código de estado 200. Ah, ¡ja! Esto nos muestra que el controlador detrás de esta página es `VinylController::browse`.

¡Genial! Ve a abrir `src/Controller/VinylController.php`. Aquí está la acción `browse`:

[[[ code('1d9b35c7dc') ]]]

Por cierto, he actualizado un poco el código desde el primer episodio... así que asegúrate de tener una copia fresca si estás codificando conmigo.

Este método llama a `$this->getMixes()`... que es una función privada que he creado en la parte inferior:

[[[ code('12af057d07') ]]]

Esto devuelve una gran matriz de datos falsos que representa las mezclas que vamos a representar en la página. Eventualmente, obtendremos esto de una fuente dinámica, como una base de datos.

## Impresión de fechas en Twig

Observa que cada mezcla tiene un campo de fecha `createdAt`. Obtenemos estas mezclas en `browse()`... y las pasamos como una variable `mixes` a `vinyl/browse.html.twig`. Vamos a saltar a esa plantilla.

Aquí abajo, utilizamos el bucle `for` de Twig para recorrer `mixes`. ¡Es muy sencillo!

[[[ code('609c972403') ]]]

Ahora imprimamos también la fecha "creada en". Añade un `|`, otro `<span>` y luego digamos `{{ mix.createdAt }}`.

Sólo hay un problema. Si miras `createdAt`... es un objeto `DateTime`. Y no puedes imprimir simplemente objetos `DateTime`... obtendrás un gran error que te recordará... que no puedes imprimir simplemente objetos `DateTime`. ¡Qué mundo más cruel!

Afortunadamente, Twig tiene un práctico filtro `date`. Ya hablamos brevemente de los filtros en el primer episodio: los utilizamos añadiendo un `|` después de algún valor y el nombre del filtro. Este filtro en particular también toma un argumento, que es el formato en el que debe imprimirse la fecha. Para simplificar las cosas, vamos a utilizar `Y-m-d`, o "año-mes-día".

[[[ code('cc122a1e01') ]]]

Ve y actualiza y... ¡bien! Ahora podemos ver cuándo se creó cada uno, aunque el formato no es muy atractivo. Podríamos hacer más trabajo para arreglar esto... pero sería mucho más genial si pudiéramos imprimir esto en el formato "hace".

Probablemente lo hayas visto antes.... como en los comentarios de una entrada de blog... dicen algo así como "publicado hace tres meses" o "publicado hace 10 minutos".

Así que... la pregunta es: ¿Cómo podemos convertir un objeto `DateTime` en ese bonito formato "hace"? Bueno, eso me suena a trabajo y, como he dicho antes, el trabajo en Symfony lo hace un servicio. Así que la verdadera pregunta es: ¿Existe un servicio en Symfony que pueda convertir los objetos `DateTime` al formato "ago"? La respuesta es... no. Pero hay un bundle de terceros que puede darnos ese servicio.

## Instalación de KnpTimeBundle

Ve a https://github.com/KnpLabs/KnpTimeBundle. Si miras la documentación de este bundle, verás que nos proporciona un servicio que puede hacer esa conversión. Así que... ¡vamos a instalarlo!

Desplázate hasta la línea `composer require`, cópiala, gira a nuestro terminal y pégala.

```terminal-silent
composer require knplabs/knp-time-bundle
```

¡Genial! Esto agarró `knplabs/knp-time-bundle`... así como `symfony/translation`: el componente de traducción de Symfony, que es una dependencia de `KnpTimeBundle`. Cerca de la parte inferior, también configuró dos recetas. Veamos qué hacen. Ejecuta:

```terminal
git status
```

¡Impresionante! Cada vez que instales un paquete de terceros, Composer siempre modificará tus archivos `composer.json` y `composer.lock`. Esto también ha actualizado el archivo`config/bundles.php`:

[[[ code('35747b5619') ]]]

Eso es porque acabamos de instalar un bundle - `KnpTimeBundle` - y su receta se encargó de ello automáticamente. También parece que la receta de traducción añadió un archivo de configuración y un directorio `translations/`. El traductor es necesario para utilizar KnpTimeBundle... pero no necesitaremos trabajar con él directamente.

Entonces... ¿qué nos ha aportado la instalación de este nuevo bundle? Servicios, por supuesto ¡Busquemos y utilicemos esos a continuación!