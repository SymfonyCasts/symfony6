# Actualizaciones de recetas Flex

Cuando instalamos paquetes, muchos de ellos tienen recetas Flex. Éstas añaden nuevos archivos y a veces modifican los existentes. Hacen todo lo necesario para que el paquete funcione inmediatamente. Eso me encanta

Y, con el tiempo, estas recetas tienden a cambiar. Quizá decidan añadir una nueva línea a un archivo de configuración o cambiar un valor por defecto.

Afortunadamente, Flex tiene un elegante sistema de actualización de recetas. Y aunque no es necesario que actualices tus recetas, es una forma estupenda de mantener el aspecto y la sensación de modernidad de tu aplicación. Las actualizaciones también ayudarán a solucionar algunas de las advertencias de desaprobación que vimos al final del capítulo anterior.

Antes de empezar, asegúrate de haber confirmado los cambios en `git` -yo ya lo he hecho- porque el sistema de actualización de recetas funciona a través de Git.

Para ver las recetas, ejecuta:

```terminal
composer recipes
```

¡Genial! Parece que tenemos unas 8 actualizaciones. Así que manos a la obra:

```terminal
composer recipes:update
```

¿Actualizar recetas? Sí, es una de mis cosas favoritas: nos da la oportunidad de echar un vistazo a lo que ha ido cambiando en estos paquetes... mientras hemos estado ocupados, ya sabes, haciendo nuestro verdadero trabajo. Voy a darle a enter para ir bajando por la lista uno a uno.

## actualización de la receta doctrine/doctrine-bundle

El primero es Doctrine Bundle: y es una actualización compleja. ¡Incluso ha provocado un conflicto!

A veces podemos ver que la actualización de una receta cambia algo -como actualizar una línea en un archivo de configuración-, pero no entendemos muy bien por qué. Para ayudarnos, el comando enumera todas las peticiones pull que hay detrás de estos cambios. Por ejemplo, esto de los fantasmas perezosos... podemos hacer clic en el enlace para ver el PR y la explicación que hay detrás.

De vuelta en mi editor, ¡woh! ¡Supongo que el conflicto estaba en `doctrine.yaml`! Concretamente,`server_version` cambió. La receta original nos daba una configuración para trabajar con Postgres 13. Ahora viene con código para Postgres 16.

No necesitas mantener los nuevos cambios. Si tu base de datos de producción utiliza Postgres 13, ¡consérvala! Pero yo actualizaré a 16.

En el terminal, ejecuta:

```terminal
git status
```

Añade ese archivo a `git` para resolverlo. Luego mira todos los cambios con:

```terminal
git diff --cached
```

La mayoría son cambios de versión: MySQL de 5.7 a 8 y Postgres de 13 a 16. La configuración de `doctrine.yaml` tiene algunas líneas nuevas. Son banderas en las que estamos optando por algún cambio de bajo nivel en el sistema. Y es muy probable que no tener esta configuración provoque una depreciación. Te dejaré que profundices en ellas si te interesa, pero probablemente no afecten a nada.

`docker-compose.yaml` contiene más cambios que van de Postgres 13 a 16. Así que, de nuevo, puedes conservarlas o deshacerte de ellas.

Y luego, al acecho en la parte inferior, `symfony.lock` hace un seguimiento de qué versión de la receta tenemos instalada. Así que, ¡ya está! Confirma estos cambios... y utiliza un mensaje de confirmación mejor que el mío.

Para utilizar la nueva versión de Postgres de `docker-compose.yaml`, ejecuta:

```terminal
docker compose down
```

Luego

```terminal
docker compose up -d
```

Ya tenemos Postgres 16 funcionando. Observa: la página de inicio sigue funcionando porque no habla con la base de datos. Pero cuando hacemos clic en "examinar mezclas", ¡rompido! Una tabla indefinida porque estamos utilizando una base de datos nueva. Arréglalo ejecutando:

```terminal
symfony console doctrine:migrations:migrate
```

Genial. Y

```terminal
symfony console doctrine:fixtures:load
```

Doble guay. Ahora... ¡ya está!

## doctrine/doctrine-migrations-bundle Actualización de la receta

De vuelta a la terminal... y de vuelta al trabajo:

```terminal
composer recipes:update
```

En cubierta está `doctrine-migrations-bundle`. Esto es menor. El bundle viene con una integración del perfilador: es este pequeño icono en la barra de herramientas de depuración web. No es súper útil... y por eso se ha cambiado para que no esté activado por defecto. Vamos a confirmarlo... y a actualizar el siguiente.

```terminal-silent
composer recipes:update
```

## symfony/framework-bundle Receta Actualizar

¡Framework bundle! ¡El núcleo de Symfony! Ejecuta `git diff --cached` para ver los cambios. Al igual que Doctrine, la mayoría son de bajo nivel, en los que optamos por un nuevo comportamiento. Por ejemplo, las anotaciones están obsoletas, así que las desactivamos.`handle_all_throwables` significa que Symfony transformará las excepciones en páginas de error, pero también otros tipos de errores. Y `storage_factory_id` se ha eliminado porque es el valor por defecto.

Muy fácil Confirma eso... y sigue adelante:

```terminal-silent
composer recipes:update
```

## symfony/monolog-bundle Actualización de la receta

Lo siguiente es monolog-bundle. El único cambio es una nueva clave `formatter` al final de `monolog.yaml`. Se trata de un cambio de coherencia. Aquí abajo, en la configuración de `prod`, el gestor de registro principal ya tiene esta clave `formatter`. Se ha añadido en `deprecations` para que todo tenga el mismo formato. Menor, ¡pero bonito! Pronto hablaremos más sobre este registro de desaprobación.

Así que, ¡compromételo! Y...

```terminal-silent
composer recipes:update
```

## actualización de la receta symfony/routing

Enrutamiento. Muy sencillo. El código que importa los atributos `#[Route]`, al parecer, necesita una clave `namespace`. Lo que quieras.

## symfony/traducción Actualización de la receta

Commit... y a

```terminal-silent
composer recipes:update
```

symfony/traducción. Otra fácil: `translation.yaml` solía tener algunos proveedores comentados como ejemplo... y ahora han desaparecido. Pero si instalas uno de estos paquetes de proveedores, su receta volverá a añadir la línea.

Confírmalo... ¡y nos quedan las 2 últimas recetas! Ambas están relacionadas con cambios en Webpack Encore y un nuevo StimulusBundle. Eso merece su propio capítulo, ¡así que hagámoslo a continuación!
