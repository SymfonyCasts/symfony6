# Accesorios de datos sencillos de Doctrine

se denomina "Data Fixtures" a los datos ficticios que añades a tu aplicación mientras desarrollas o ejecutas pruebas para facilitarte la vida. Es mucho más agradable trabajar en una nueva función cuando realmente tienes datos decentes en tu base de datos. Hemos creado algunos datos fijos, en cierto sentido, mediante esta acción de `new`. Pero Doctrine tiene un sistema específicamente diseñado para esto.
# Instalar DoctrineFixturesBundle

Busca "doctrinefixturesbundle" para encontrar su repositorio en GitHub. Y puedes leer su documentación en Symfony.com. Copia la línea de instalación y, en tu terminal, ejecútala:

```terminal
composer require --dev orm-fixtures
```

`orm-fixtures` es, por supuesto, un alias de Flex, en este caso, a`doctrine/doctrine-fixtures-bundle`. Y... ¡listo! Ejecuta

```terminal
git status
```

para ver que esto ha añadido un bundle, así como un nuevo directorio `src/DataFixtures/`. Ve a abrirlo. Dentro, tenemos un único archivo nuevo llamado `AppFixtures.php`.

[[[ code('766a29102b') ]]]

DoctrineFixturesBundle es un bundle deliciosamente sencillo. Nos proporciona un nuevo comando de consola llamado `doctrine:fixtures:load`. Cuando lo ejecutemos, vaciará nuestra base de datos y luego ejecutará el método `load()` dentro de `AppFixtures`. Bueno, en realidad ejecutará el método `load()` en cualquier servicio que tengamos que extienda esta clase `Fixture`. Así que podemos tener varias clases en este directorio si queremos.

Si lo ejecutamos ahora mismo... con un método `load()` vacío, limpia nuestra base de datos, llama a ese método vacío y... ¡el resultado en la página "Examinar" es que no tenemos nada!

```terminal-silent
php bin/console doctrine:fixtures:load
```

## Rellenando el método load()

¡Eso no es muy interesante, así que vamos a rellenar ese método `load()`! Empieza en`MixController`: roba todo el código de `VinylMix`... y pégalo aquí. Pulsa "Ok" para añadir la declaración `use`.

[[[ code('76e06fff28') ]]]

Fíjate en que el método `load()` acepta algún argumento de `ObjectManager`. En realidad es el `EntityManager`, ya que estamos utilizando el ORM. Si miras aquí abajo, ya tiene la llamada `flush()`. Lo único que nos falta es la llamada `persist()`:`$manager->persist($mix)`.

[[[ code('fb84406a7e') ]]]

Así que la variable se llama aquí `$manager`... pero estas dos líneas son exactamente las que tiene nuestro controlador: `persist()` y `flush()`.

Prueba de nuevo el comando:

```terminal
php bin/console doctrine:fixtures:load
```

Vacía la base de datos, ejecuta nuestros accesorios y tenemos... ¡una nueva mezcla!

Vale, esto es genial. Tenemos un nuevo comando `bin/console` para cargar cosas. Pero para el desarrollo, quiero un conjunto realmente rico de datos de fijos, como... tal vez 25 mezclas. Podríamos añadirlas a mano aquí... o incluso crear un bucle. Pero hay una forma mejor, a través de una biblioteca llamada "Foundry". ¡Vamos a explorarla a continuación!
