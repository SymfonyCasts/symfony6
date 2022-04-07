# Recetas Flex

Acabamos de instalar un nuevo paquete ejecutando `composer require templates`. Normalmente, al hacerlo, Composer actualizará los archivos `composer.json` y `composer.lock`, pero nada más.

Pero cuando ejecutamos

```terminal
git status
```

Hay otros cambios. Esto es gracias al sistema de recetas de Flex. Cada vez que instalamos un nuevo paquete, Flex comprueba en un repositorio central si ese paquete tiene una receta. Y si la tiene, la instala.

## ¿Dónde viven las recetas?

¿Dónde viven estas recetas? En la nube... o más concretamente en GitHub. Compruébalo. Ejecutar:

```terminal
composer recipes
```

Este es un comando añadido a Composer por Flex. Enumera todas las recetas que se han instalado. Y si quieres más información sobre una, ejecútala:

```terminal
composer recipes symfony/twig-bundle
```

Esta es una de las recetas que se acaba de ejecutar. Y... ¡guay! ¡Nos muestra un par de cosas bonitas! La primera es un árbol de los archivos que ha añadido a nuestro proyecto. La segunda es una URL de la receta que se instaló. Haré clic para abrirla.

¡Sí! Las recetas de Symfony viven en un repositorio especial llamado `symfony/recipes`. Se trata de un gran directorio organizado por nombre de paquete. Hay un directorio `symfony` que contiene las recetas de todos los paquetes que empiezan por `symfony/`. El que acabamos de ver... está aquí abajo: `twig-bundle`. Y luego hay diferentes versiones de la receta en función de tu versión del paquete. Nosotros estamos utilizando la última versión 5.4.

Cada receta tiene un archivo `manifest.json`, que controla lo que hace. El sistema de recetas sólo puede realizar un conjunto específico de operaciones, como añadir nuevos archivos a tu proyecto y modificar algunos archivos concretos. Por ejemplo, esta sección `bundles`le dice a flex que añada esta línea a nuestro archivo `config/bundles.php`.

Si volvemos a ejecutar `git status`... ¡sí! Ese archivo ha sido modificado. Si lo difundimos:

```terminal-silent
git diff config/bundles.php
```

Ha añadido dos líneas, probablemente una para cada una de las dos recetas.

## ¿Bolsos Symfony?

Por cierto, `config/bundles.php` no es un archivo en el que tengas que pensar mucho. Un bundle, en la tierra de Symfony, es básicamente un plugin. Así que si instalas un nuevo bundle en tu aplicación, eso te da nuevas características de Symfony. Para activar ese bundle, su nombre tiene que estar en este archivo.

Así que lo primero que hizo la receta para Twig-bundle, gracias a esta línea de aquí arriba, fue activarse dentro de `bundles.php`... para que no tuviéramos que hacerlo manualmente. Las recetas son como una instalación automática.

## Archivos nuevos y copiados

La segunda sección del manifiesto se llama `copy-from-recipe`. Es sencillo: dice que hay que copiar los directorios `config/` y `templates/` de la receta en el proyecto. Si nos fijamos... la receta contiene un archivo `config/packages/twig.yaml`... y también un archivo `templates/base.html.twig`.

De vuelta al terminal, ejecuta de nuevo `git status`. Vemos estos dos archivos en la parte inferior:`config/packages/twig.yaml`... y dentro de `templates/`, `base.html.twig`.

Esto me encanta. Piénsalo: si instalas una herramienta de plantillas en tu aplicación, vamos a necesitar alguna configuración en algún lugar que le diga a esa herramienta de plantillas en qué directorio debe buscar nuestras plantillas. Ve a ver ese archivo`config/packages/twig.yaml`. Hablaremos más de estos archivos Yaml en el próximo tutorial. Pero a alto nivel, este archivo controla cómo se comporta Twig, el motor de plantillas de Symfony. Y fíjate en la clave `default_path` establecida en `%kernel.project_dir%/templates`. No te preocupes por esta sintaxis porcentual: es una forma elegante de referirse a la raíz de nuestro proyecto.

La cuestión es que esta configuración dice

> ¡Hey Twig! Cuando busques plantillas, búscalas en el directorio `templates/`.

Y la receta incluso ha creado ese directorio con un archivo de diseño dentro. Lo usaremos en unos minutos.

## symfony.lock y el compromiso de los archivos

El último archivo no explicado que se ha modificado es `symfony.lock`. Esto no es importante: sólo mantiene un registro de las recetas que se han instalado... y deberías confirmarlo.

De hecho, deberíamos confirmar todo esto. La receta puede darnos archivos, pero luego son nuestros para modificarlos. Ejecuta:

```terminal
git add .
```

Entonces:

```terminal
git status
```

Genial. ¡Vamos a confirmarlo!

```terminal
git commit -m "Adding Twig and its beautiful recipe"
```

## Actualizar las recetas

¡Ya está! Por cierto, es posible que dentro de unos meses haya cambios en algunas de las recetas que has instalado. Y si los hay, cuando ejecutes

```terminal
composer recipes
```

verás un pequeño "actualización disponible" junto a ellas. Ejecuta `composer recipes:update`para actualizar a la última versión.

Ah, y antes de que se me olvide, además de `symfony/recipes`, también hay un repositorio`symfony/recipes-contrib`. Así que las recetas pueden vivir en cualquiera de estos dos lugares. Las recetas de `symfony/recipes` están aprobadas por el equipo central de Symfony, por lo que su calidad está un poco más controlada. Aparte de eso, no hay ninguna diferencia.

## Nuestro proyecto comenzó como un archivo

Ahora, el sistema de recetas es tan potente que cada uno de los archivos de nuestro proyecto se añadió mediante una receta Puedo demostrarlo. Ve a https://github.com/symfony/skeleton.

Cuando ejecutamos originalmente ese comando `symfony new` para iniciar nuestro proyecto, lo que realmente hizo fue clonar este repositorio... y luego ejecutó `composer install` dentro de él, que descarga todo en el directorio `vendor/`.

Sí Nuestro proyecto -el que vemos aquí- era originalmente un único archivo: `composer.json`. Pero luego, cuando se instalaron los paquetes, las recetas de esos paquetes añadieron todo lo que vemos. Ejecuta:

```terminal
composer recipes
```

de nuevo. Una de las recetas es para algo llamado `symfony/console`. Comprueba sus detalles:

```terminal
composer recipes symfony/console
```

Y... ¡sí! ¡La receta de `symfony/console` añadió el archivo `bin/console`! La receta de `symfony/framework-bundle` -uno de los otros paquetes que se instaló originalmente- añadió casi todo lo demás, incluido el archivo `public/index.php`. ¿No es genial?

Bien, a continuación: ¡hemos instalado Twig! ¡Así que volvamos al trabajo y utilicémoslo para renderizar algunas plantillas! Te va a encantar Twig.
