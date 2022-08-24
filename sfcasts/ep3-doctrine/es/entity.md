# Clase de entidad

Una de las cosas más chulas, y quizá más sorprendentes, de Doctrine es que quiere que finjas que la base de datos no existe Sí, en lugar de pensar en tablas y columnas, Doctrine quiere que pensemos en objetos y propiedades.

Por ejemplo, supongamos que queremos guardar los datos de un producto. La forma de hacerlo con Doctrine es crear una clase `Product` con propiedades que contengan los datos. Entonces instancias un objeto `Product`, pones los datos en él y pides amablemente a Doctrine que los guarde por ti. No tenemos que preocuparnos de cómo lo hace Doctrine.

Pero, por supuesto, entre bastidores Doctrine está hablando con una base de datos. Insertará los datos del objeto `Product` en una tabla `product` en la que cada propiedad está asignada a una columna. Esto se llama mapeador relacional de objetos, o ORM.

Más tarde, cuando queramos recuperar esos datos, no pensaremos en "consultar" esa tabla y sus columnas. No, simplemente le pedimos a Doctrine que encuentre el objeto que teníamos antes. Por supuesto, consultará la tabla... y luego volverá a crear el objeto con los datos. Pero no es un detalle en el que pensemos: pedimos el objeto `Product`, y nos lo da. Doctrine se encarga de guardar y consultar todo automáticamente.

## Generación de la entidad con make:entity

De todos modos, cuando utilizamos un ORM como Doctrine, si queremos guardar algo en la base de datos, tenemos que crear una clase que modele lo que queremos guardar, como una clase `Product`. En Doctrine, estas clases reciben un nombre especial: entidades, aunque en realidad son clases normales de PHP. Y aunque puedes crear estas clases de entidad a mano, hay un comando MakerBundle que te hace la vida mucho más agradable.

Ve a tu terminal y ejecuta:

```terminal
php bin/console make:entity
```

En este caso, no tenemos que ejecutar `symfony console make:entity` porque este comando no hablará con la base de datos: sólo genera código. Pero, si alguna vez no estás seguro, usar `symfony console` siempre es seguro.

Bien, queremos crear una clase para almacenar todas las mezclas de vinilos de nuestro sistema. Así que vamos a crear una nueva clase llamada `VinylMix`. A continuación, responde a `no` para transmitir las actualizaciones de las entidades: es una característica extra relacionada con Symfony Turbo.

Bien, aquí está la parte importante: nos pregunta qué propiedades queremos. Vamos a añadir varias. Empezamos con una llamada `title`. A continuación nos pregunta de qué tipo es este campo. Pulsa `?` para ver la lista completa.

Estos son tipos de Doctrine... y cada uno de ellos se asignará a un tipo de columna diferente en tu base de datos, dependiendo de la base de datos que estés utilizando, como MySQL o Postgres. Los tipos básicos están en la parte superior como `string`, `text` - que puede contener más que una cadena) - `boolean`, `integer` y `float`. Luego los campos de relación -de los que hablaremos en el próximo tutorial-, algunos campos especiales, como el almacenamiento de JSON y los campos de fecha.

Para `title`, utiliza `string`, que puede contener hasta 255 caracteres. Mantendré la longitud por defecto... luego nos pregunta si el campo puede ser nulo en la base de datos. Responderé `no`. Esto significa que la columna no puede ser nula. En otras palabras, la columna será obligatoria en la base de datos.

Y... ¡un campo hecho! Vamos a añadir algunos más. Necesitamos un `description`, y que sea del tipo `text`. `string` tiene un máximo de 255 caracteres, `text` puede contener un montón más. Esta vez, diré `yes` para que sea anulable. Así será una columna opcional en la base de datos. ¡Otra más para abajo!

Para la siguiente propiedad, llámala `trackCount`. Será un `integer` y será no nulo. Luego añade `genre`, como un `string`, de longitud 255... y también no nulo para que sea obligatorio en la base de datos.

Por último, añade un campo `createdAt` para que podamos saber cuándo se creó originalmente cada mezcla de vinilo. Esta vez, como el nombre del campo termina en "At", el comando sugiere un tipo `datetime_immutable`. Pulsa "intro" para utilizarlo, y también para que no sea nulo en la base de datos.

Ahora no necesitamos añadir más propiedades, así que pulsa "intro" una vez más para salir del comando.

Ya está ¿Qué ha hecho esto? Bueno, en primer lugar, puedo decirte que esto no ha hablado con nuestra base de datos ni la ha modificado en absoluto. No, simplemente ha generado dos clases. La primera es `src/Entity/VinylMix.php`. La segunda es `src/Repository/VinylMixRepository.php`. Ignora la `Repository` por ahora... hablaremos de su propósito en unos minutos.

[[[ code('33b9a4adde') ]]]

## Comprobación de la clase y los atributos de la entidad

Ve a abrir la entidad `VinylMix.php`. Saluda a... una... ¡vaya, una clase PHP bastante normal y aburrida! Generó una propiedad `private` para cada campo que añadimos, además de una propiedad extra `id`. El comando también añadió un método getter y setter para cada una de ellas. Así que... esto es básicamente una clase que contiene datos... y podemos acceder y establecer esos datos a través de los métodos getter y setter

Lo único que hace especial a esta clase son los atributos. El `ORM\Entity`sobre la clase le dice a Doctrine:

> Quiero poder guardar objetos de esta clase en la base de datos. Este
> es una entidad.

Luego, encima de cada propiedad, utilizamos `ORM\Column` para decirle a Doctrine que queremos guardar esta propiedad como una columna en la tabla. Esto también comunica otras opciones como la longitud de la columna y si debe ser anulable o no.`nullable: false` es el valor por defecto... así que el comando sólo generó `nullable: true`en la única propiedad que lo necesita.

La otra cosa que controla `ORM\Column` es el tipo de campo. Eso se establece mediante esta opción `type`. Como ya he dicho, esto no se refiere directamente a un tipo de MySQL o Postgres... es un tipo de Doctrine que luego se asignará a algo específico en función de nuestra base de datos.

## Adivinar el tipo de campo

Pero, interesante: la opción `type` sólo aparece en el campo `$description`. La razón de esto es realmente genial... ¡y nueva! Doctrine es inteligente. Mira el tipo de tu propiedad y adivina el tipo de campo a partir de él. Así que cuando tienes un tipo de propiedad `string`, Doctrine asume que quieres que ese sea su tipo `string`. Podrías escribir `Types::STRING` dentro de `ORM\Column`... pero eso sería totalmente redundante.

Sin embargo, lo necesitamos para el campo `description`... porque queremos utilizar el tipo`TEXT`, no el tipo `STRING`. Pero en cualquier otra situación, funciona. Doctrine adivina el tipo correcto a partir del tipo de propiedad `?int`... y lo mismo ocurre aquí abajo para el tipo `?\DateTimeImmutable`.

## Nombres de tablas y columnas

Además de controlar las cosas de cada columna, también podemos controlar el nombre de la tabla añadiendo un `ORM\Table` encima de la clase con el nombre establecido, por ejemplo,`vinyl_mix`. Pero, ¡sorpresa! ¡No necesitamos hacer eso! ¿Por qué? Porque Doctrine es muy bueno generando grandes nombres. Genera el nombre de la tabla transformando la clase en caso de serpiente. Así que incluso sin `ORM\Table`, éste será el nombre de la tabla. Lo mismo ocurre con las propiedades. `$trackCount` se asignará a una columna`track_count`. Doctrine se encarga de todo esto por nosotros: no tenemos que pensar en absoluto en los nombres de nuestras tablas o columnas.

Llegados a este punto, hemos ejecutado `make:entity` y nos ha generado una clase de entidad. Pero... todavía no tenemos una tabla `vinyl_mix` en nuestra base de datos. ¿Cómo creamos una? Con la magia de las migraciones de bases de datos. Eso a continuación.
