# Fundición: Accesorios que te encantarán

Construir accesorios es bastante sencillo, pero algo aburrido. Y sería súper aburrido crear manualmente 25 mezclas dentro del método `load()`. Por eso vamos a instalar una impresionante biblioteca llamada "Foundry". Para ello, ejecuta:

```terminal
composer require zenstruck/foundry --dev
```

Ejecuta: `--dev` porque sólo necesitamos esta herramienta cuando estamos desarrollando o ejecutando pruebas. Cuando termine, ejecuta

```terminal
git status
```

para ver que la receta ha habilitado un bundle y también ha creado un archivo de configuración... que no necesitaremos mirar.

## Fábricas: make:factory

En resumen, Foundry nos ayuda a crear objetos de entidad. Es... casi más fácil verlo en acción. En primer lugar, para cada entidad de tu proyecto (ahora mismo, sólo tenemos una), necesitarás una clase fábrica correspondiente. Créala ejecutando

```terminal
php bin/console make:factory
```

que es un comando Maker que viene de Foundry. Luego, puedes seleccionar para qué entidad quieres crear una fábrica... o generar una fábrica para todas tus entidades. Nosotros generaremos una para `VinylMix`. Y... eso creó un único archivo: `VinylMixFactory.php`. Vamos a comprobarlo:`src/Factory/VinylMixFactory.php`.

[[[ code('f4cf88fd24') ]]]

¡Qué bien! Encima de la clase, puedes ver que se describen un montón de métodos... que ayudarán a nuestro editor a saber qué superpoderes tiene esto. Esta fábrica es realmente buena para crear y guardar objetos `VinylMix`... o para crear muchos de ellos, o para encontrar uno al azar, o un conjunto al azar, o un rango al azar. ¡Uf!

## getDefaults()

El único código importante que vemos dentro de esta clase es `getDefaults()`, que devuelve los datos por defecto que deben utilizarse para cada propiedad cuando se crea un `VinylMix`. Hablaremos más de eso en un minuto.

Pero antes... ¡vamos a avanzar a ciegas y a utilizar esta clase! En `AppFixtures`, borra todo y sustitúyelo por `VinylMixFactory::createOne()`.

[[[ code('b572b3c087') ]]]

¡Ya está! Gira y vuelve a cargar los accesorios con:

```terminal
symfony console doctrine:fixtures:load
```

Y... ¡falla! Boo

> Tipo de argumento esperado "DateTime", "null" dado en la ruta de la propiedad "createdAt"

Nos está diciendo que algo intentó llamar a `setCreatedAt()` en `VinylMix`... pero en lugar de pasar un objeto `DateTime`, pasó `null`. Hmm. Dentro de`VinylMix`, si te desplazas hacia arriba y abres `TimestampableEntity`, ¡sí! Tenemos un método`setCreatedAt()` que espera un objeto `DateTime`. Algo llamado así... pero que pasa `null`.

Esto ayuda a mostrar cómo funciona Foundry. Cuando llamamos a`VinylMixFactory::createOne()`, crea un nuevo `VinylMix` y luego le pone todos estos datos. Pero recuerda que todas estas propiedades son privadas. Así que no establece la propiedad del título directamente. En su lugar, llama a `setTitle()` y `setTrackCount()`Aquí abajo, para `createdAt` y `updatedAt`, llamó a `setCreatedAt()`y le pasó a `null`.

En realidad, no necesitamos establecer estas dos propiedades porque las establecerá automáticamente el comportamiento timestampable.

Si probamos esto ahora...

```terminal-silent
symfony console doctrine:fixtures:load
```

¡Funciona! Y si vamos a ver nuestro sitio... impresionante. Esta mezcla tiene 928.000 pistas, un título aleatorio y 301 votos. Todo esto proviene del método `getDefaults()`.

## Datos falsos con Faker

Para generar datos interesantes, Foundry aprovecha otra biblioteca llamada "Faker", cuyo único trabajo es... crear datos falsos. Así que si quieres un texto falso, puedes decir `self::faker()->`, seguido de lo que quieras generar. Hay muchos métodos diferentes que puedes invocar en `faker()` para obtener todo tipo de datos falsos divertidos. ¡Es muy útil!

## Creando muchos objetos

Nuestra fábrica ha hecho un trabajo bastante bueno... pero vamos a personalizar las cosas para hacerlas un poco más realistas. En realidad, en primer lugar, tener un `VinylMix` sigue sin ser muy útil. Así que, dentro de `AppFixtures`, cambia esto por `createMany(25)`.

[[[ code('68aa0810e8') ]]]

Aquí es donde Foundry brilla. Si ahora recargamos nuestras instalaciones:

```terminal-silent
symfony console doctrine:fixtures:load
```

Con una sola línea de código, ¡tenemos 25 accesorios aleatorios con los que trabajar! Sin embargo, los datos aleatorios podrían ser un poco mejores... así que vamos a mejorar eso.

## Personalizar getDefaults()

Dentro de `VinylMixFactory`, cambia el título. En lugar de `text()` -que a veces puede ser un muro de texto-, cambia a `words()`... y utiliza 5 palabras, y pasa true para que lo devuelva como una cadena. De lo contrario, el método `words()` devuelve una matriz. Para `trackCount`, sí queremos un número aleatorio, pero... probablemente un número entre 5 y 20. Para `genre`, vamos a buscar un `randomElement()` para elegir aleatoriamente `pop` o `rock`. Esos son los dos géneros con los que hemos trabajado hasta ahora. Y, vaya... asegúrate de llamar a esto como una función, ya está. Por último, para `votes`, elige un número aleatorio entre -50 y 50.

[[[ code('8aa7ee7c72') ]]]

¡Mucho mejor! Ah, y puedes ver que `make:factory` ha añadido aquí un montón de nuestras propiedades por defecto, pero no las ha añadido todas. Una de las que falta es`description`. Añádela: `'description' => self::faker()->` y luego usa `paragraph()`. Por último, para `slug`, no la necesitamos en absoluto porque se establecerá automáticamente.

[[[ code('5a3fff72f5') ]]]

¡Ufff! ¡Vamos a probarlo! Recarga los accesorios:

```terminal-silent
symfony console doctrine:fixtures:load
```

Luego dirígete y actualiza. Esto se ve mucho mejor. Tenemos una imagen rota... pero eso es sólo porque la API que estoy utilizando tiene algunas "lagunas"... nada de lo que preocuparse.

Foundry puede hacer un montón de cosas interesantes, así que no dudes en consultar su documentación. Es especialmente útil cuando se escriben pruebas, y funciona muy bien con las relaciones de la base de datos. Así que lo volveremos a ver de forma más compleja en el próximo tutorial.

A continuación, ¡vamos a añadir la paginación! Porque, al final, no podremos listar todas las mezclas de nuestra base de datos de una sola vez.
