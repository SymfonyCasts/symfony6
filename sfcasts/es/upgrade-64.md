# Actualización a Symfony 6.4

¡Hola a todos! ¡Symfony 7 ha salido! ¡Guau! Bueno, por supuesto que estoy emocionado, me encanta todo lo relacionado con Symfony y Twig. Pero, ¿qué significa realmente que Symfony 7 haya salido?

## El deliciosamente predecible calendario de lanzamientos de Symfony

Sinceramente... ¡no mucho! Gracias al calendario de lanzamientos de Symfony, una nueva versión principal no es gran cosa... aunque intentamos fingir que lo es para el marketing.

Cada 6 meses -en mayo y noviembre- se lanza una nueva versión menor, como la 6.1 o la 6.2. Ésas son las versiones que contienen nuevas funciones. Así que tiene todo el sentido entusiasmarse con Symfony 6.3 o con las fantásticamente increíbles nuevas funciones de Symfony 6.4. Luego, cada versión ".4", como la 6.4, se lanza el mismo día que la versión .0 de la siguiente mayor: la 7.0. ¡Sí, la 6.4 y la 7.0 se lanzaron exactamente el mismo día y son, efectivamente, idénticas! ¡Son gemelas!

La única diferencia es que, en la 7.0, se eliminan todas las rutas de código obsoletas. Y esto es lo que hace especial a Symfony. El calendario de lanzamientos y la política de obsoletos significan que, como usuarios, podemos actualizar nuestras aplicaciones para siempre a través de las versiones principales... sin que sea un gran problema ni se rompan nuestras aplicaciones. Y eso es exactamente lo que vamos a hacer en este tutorial... seguido de un recorrido por algunas de mis nuevas características favoritas.

## Configuración del proyecto

Como siempre, para sacar el máximo partido a este tutorial, codifica conmigo descargando el código del curso desde esta página. Después de descomprimir el archivo, encontrarás un directorio `start/`con el mismo código que ves aquí. El archivo `README.md` cuenta una historia inspiradora sobre cómo poner en marcha la aplicación. Yo ya he realizado la mayoría de los pasos, incluyendo la ejecución de `yarn install` y `yarn watch` en esta pestaña.

El último paso consiste en utilizar el binario `symfony` para Ejecuta:

```terminal
symfony serve -d
```

para iniciar un servidor web de desarrollo. Haré clic en el enlace. Saluda a Mixed Vinyl: La aplicación de varios de nuestros tutoriales sobre Symfony 6, que actualmente está en la versión 6.1.2.

## Utilizar una versión de PHP más reciente

Abre `composer.json`. Cerca de la parte superior, nuestra aplicación requiere `php` 8.1 o superior. En mis aplicaciones, debajo de `config.platform.php`, también me gusta establecer la versión específica de PHP que estamos utilizando en producción:

[[[ code('94f233f6b4') ]]]

Esto garantiza que Composer sólo me proporcione dependencias compatibles con esa versión.

Localmente, si ejecuto `php -v`, ya tengo instalado PHP 8.3. También tengo instalado un segundo binario `php` para la versión 8.1. Y gracias al `8.1` en `composer.json`, cuando inicié el servidor web `symfony`, utilizó esa versión anterior.

Cambia esto por, sólo PHP `8.3`. Luego ejecuta:

```terminal
composer up
```

En `composer.json`, todas mis dependencias -ya sean de Symfony o de otra cosa- están escritas de forma que sólo permiten que cambie el último número o el penúltimo. Suponiendo que los mantenedores de paquetes hagan su trabajo, esas actualizaciones no contendrán rupturas de retrocompatibilidad. Deberíamos poder actualizar de la 6.1 a la 6.4... o de la 2.0 a la 2.4, ¡y nuestra aplicación debería seguir funcionando con normalidad!

Así que ejecutar `composer up` para obtener estas actualizaciones, en teoría, es totalmente seguro.

## Encore y cambios menores

En mi pestaña `yarn`, la actualización provocó un error: algo sobre un controlador no existe. Esto es especial para Symfony UX y Encore. Cuando actualices tus dependencias de PHP, puede que tengas que reinstalar tus dependencias de `node`. Pulsa Ctrl+C, y luego Ejecuta:

```terminal
yarn install --force
```

O `npm install --force` si estás utilizando `npm`. Luego

```terminal
yarn watch
```

de nuevo. ¡Es feliz! En la pestaña principal, ejecuta:

```terminal
git status
```

Junto a los sospechosos habituales, hay un nuevo controlador en `controllers.json`... que procede de una actualización de `ux-turbo`. No lo utilizaremos, pero está bien ahí. En `package.json`, se ha añadido una nueva entrada para el bundle Stimulus. Se trata de un bundle relativamente nuevo que se instaló durante la actualización, y del que hablaremos más adelante.

## Actualización a 6.4

Ahora estamos utilizando PHP 8.3 y hemos actualizado un poco nuestras dependencias. Pero seguimos usando Symfony 6.1. Para actualizar a 7, primero tenemos que actualizar a 6.4. Eso nos dará la oportunidad de prepararnos para 7.0 encontrando -y arreglando- todas las obsoletas.

Y... ¡actualizar es fácil! Busca `6.1.*`, sustitúyelo por `6.4.*` y sustitúyelo todo.

[[[ code('6eb3d24aa3') ]]]

Aunque, ten cuidado. La mayoría de las veces, las restricciones de versión de Symfony se parecen a esto. Sin embargo, podrían parecerse a `^6.1`. Así que no las pases por alto: el objetivo es actualizar todos los paquetes `symfony` que procedan del repositorio principal. Eso... puede ser confuso porque, mezclados con los paquetes que sí queremos actualizar, hay otros paquetes que viven bajo `symfony`, pero que son independientes y siguen su propio calendario de publicación y versionado. Ignóralos por ahora, pero nos aseguraremos de que todos los paquetes se actualicen al final.

Además, cerca de la parte inferior, en `extra.symfony.require`, asegúrate de que también se actualiza a `6.4.*`. Es una optimización de Composer que le indica que sólo se preocupe de las versiones 6.4 de Symfony.

De vuelta al terminal, ¡hagamos esto!

```terminal
composer up
```

¡Mira qué bonitas actualizaciones de la 6.1 a la 6.4! Y... cuando probamos el sitio, ¡la apestosa cosa sigue funcionando! 

Ah, pero fíjate en la versión de PHP: 8.1.27. Cuando iniciamos el servidor web `symfony`, leyó la versión PHP 8.1 de `composer.json`, encontró esa versión instalada en mi máquina y la utilizó. La cambiamos por la 8.3, pero necesitamos reiniciar el servidor para utilizarla. Ejecuta:

```terminal
symfony server:stop
```

Entonces:

```terminal
symfony serve -d
```

Sí: encontró la versión PHP 8.3.1 en mi sistema. Y en el sitio... ¡ya está!

Ok, esto funciona en Symfony 6.4. Nuestro trabajo ahora es encontrar cada deprecación y arreglarlas. En la barra de herramientas de depuración web, ¡aparentemente encontramos 22 rutas de código obsoletas en esta página! Para empezar a arreglarlas, haremos... trampas... tomaremos un atajo, actualizando nuestras recetas Flex.
