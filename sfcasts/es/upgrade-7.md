# Actualización a Symfony 7

Todas las depreciaciones relevantes han desaparecido. ¡Así que estamos listos para Symfony 7.0!

## Actualizando composer.json

Hacer la actualización en sí es... casi decepcionantemente fácil. En `composer.json`sustituye `6.4.*` por `7.0.*`.

[[[ code('47734fbeca') ]]]

Eso es todo. Gira y ejecuta:

```terminal
composer up
```

## Encontrar paquetes bloqueantes

Preparaos porque puede que esto no funcione. ¡Sí! Algo está bloqueando la actualización! Lo primero que veo es `babdev/pagerfanta-bundle`. Aparentemente, funciona con Symfony 4, 5 y 6, pero no con 7.

Es muy probable que tenga que actualizarlo a una nueva versión que sí sea compatible con Symfony 7. Ejecuta:

```terminal
composer outdated
```

Efectivamente: hay tres paquetes pagerfanta que tienen todos una nueva versión mayor. En `composer.json`, busca pagerfanta. Cámbialos todos a `^4.0` para obtener esa nueva versión mayor.

[[[ code('0248cdb609') ]]]

Y como se trata de una actualización de versión mayor, no lo haré yo, pero deberías comprobar el repositorio de cada paquete y encontrar el registro de cambios o las notas de la versión que hablen de cualquier ruptura de retrocompatibilidad entre la versión 3 y la 4.

Vale, vuelve a intentar la actualización:

```terminal
composer up
```

Y... ¡sigue sin funcionar! Hmm: dice que la raíz `composer.json` -es decir, nuestro`composer.json` - requiere `symfony/proxy-manager-bridge` `7.0.*` pero no ha encontrado una versión 7.

Efectivamente, este paquete está directamente en nuestro archivo `composer.json`. Los proxies son algo que Doctrine utiliza entre bastidores para cargar las relaciones perezosas. Recientemente, Symfony ha añadido su propia versión de proxies llamados "objetos fantasma". Son espeluznantes. De todos modos, este paquete proxy ya no es necesario. Se añadió originalmente a nuestra aplicación cuando instalamos Doctrine: formaba parte de`orm-pack`.

Deshazte de él Después vuelve a probar `composer up`:

```terminal-silent
composer up
```

Esta vez... ¡funciona! ¡Mira todas esas bonitas actualizaciones de Symfony 7! Y lo mejor de todo, cuando vamos al sitio, ¡también funciona! ¡Claro que funciona! Nos hemos encargado de las deprecaciones, para que no haya sorpresas cuando por fin lleguemos a 7.0.

## Otros paquetes que actualizar

Llegados a este punto, me gusta comprobar qué otros paquetes no relacionados con Symfony están desactualizados. Ejecuta de nuevo `composer outdated`:

```terminal-silent
composer outdated
```

¡Woh! Sólo dos! `doctrine/lexer` y un `php-parser`. Para averiguar por qué no ha pasado a la versión 3, copia el nombre de ese paquete y ejecuta

```terminal
composer why-not doctrine/lexer 3.0
```

Hmm: nuestra versión de `doctrine/orm` requiere `doctrine/lexer` versión 2. Y como no vimos `doctrine/orm` como paquete obsoleto, significa que simplemente no existe todavía una versión de `doctrine/orm` que funcione con `doctrine/lexer` 3. Es un paquete de bajo nivel y no tenemos prisa.

El otro paquete - `php-parser` - puedo decirte, sin siquiera mirar, que lo necesita `symfony/maker-bundle`. En su próxima versión, se permitirá la versión 5.

## Recetas para nuevas versiones

Como acabamos de actualizar algunos paquetes, Ejecuta:

```terminal
composer recipes
```

¡Hay dos nuevas actualizaciones de recetas disponibles! Para actualizar, primero confirma nuestros cambios... con un emoji para celebrarlo... y luego ejecuta:

```terminal
composer recipes:update
```

Y `git diff --cached` para ver los cambios. Esto es genial: han desaparecido un montón de líneas. Se han eliminado porque ahora son los valores por defecto. La clave `session`ya no necesita estas cosas: son los valores por defecto... y lo mismo para`php_errors` y `handle_all_throwables`. Es sólo una bonita limpieza de la configuración.

Confírmalo y ejecuta `recipes:update` una vez más:

```terminal-silent
composer recipes:update
```

Comprueba los cambios. Lo mismo: elimina una opción de configuración que ahora es la predeterminada. Confírmalo. Nuestro proyecto está ahora un poco más limpio.

Así que estamos en Symfony 7, ¡nuestra aplicación funciona y nuestras recetas están actualizadas!

## ¡Cambiando el espacio de nombres de #[Route]!

Mientras estamos aquí, dentro de un controlador, resalta el atributo `Route`:

> El espacio de nombres de anotación de Symfony quedará obsoleto en Symfony 6.4 /7.0.

Mira la declaración `use`: ¡tiene `Annotation` en el espacio de nombres! Esta clase aún no está obsoleta, pero lo estará pronto. Y arreglarlo es sencillo. Elimina la sentencia `use`, baja aquí, haz clic en la clase, pulsa Alt+Enter, Importar clase, y luego coge la del espacio de nombres `Attribute`.

Cópiala... y repite la operación en los otros dos archivos del controlador. Esto nos ahorrará una depreciación en el futuro.

Ahora que estamos en Symfony 7, quiero hacer algo opcional, pero realmente genial: quiero eliminar Webpack Encore y sustituirlo por AssetMapper.
