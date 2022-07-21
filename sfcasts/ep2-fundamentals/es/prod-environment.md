# El entorno "prod"

Nuestra aplicación se está ejecutando actualmente en el entorno `dev`. Cambiémosla a `prod`... que es el que usarías en producción. Cambia temporalmente `APP_ENV=dev` por`prod`... y luego dirígete a él y actualízalo. ¡Vaya! La barra de herramientas de depuración web ha desaparecido. ¡Eso... tiene sentido! Todo el bundle del perfilador web no está activado en el entorno `prod`.

También observarás que el volcado de nuestro controlador aparece en la parte superior de la página. El perfilador web normalmente captura eso y lo muestra abajo en la barra de herramientas de depuración web. Pero... como todo ese sistema ya no está habilitado, ahora hace el volcado justo donde lo llamas.

Y hay muchas otras diferencias, como el registrador, que ahora se comporta de forma diferente gracias a la configuración en `monolog.yaml`.

## Borrar la caché de prod

La forma de construir las páginas también ha cambiado. Por ejemplo, Symfony almacena en caché muchos archivos... pero eso no se nota en el entorno `dev`. Eso es porque Symfony es súper inteligente y reconstruye esa caché automáticamente cuando cambiamos ciertos archivos. Sin embargo, en el entorno `prod`, eso no ocurre.

¡Compruébalo! Abre `templates/base.html.twig`... y cambia el título de la página a `Stirred Vinyl`. Si vuelves y actualizas... ¡mira aquí! No hay cambios! Las propias plantillas Twig se almacenan en la caché. En el entorno `dev`, Symfony reconstruye esa caché por nosotros. ¿Pero en el entorno `prod`? No Tenemos que borrarla manualmente.

¿Cómo? En tu terminal, ejecuta:

```terminal
php bin/console cache:clear
```

Fíjate que dice que está borrando la caché del entorno prod. Así, al igual que nuestra aplicación se ejecuta siempre en un entorno concreto, los comandos de la consola también se ejecutan en un entorno concreto. Y lee la misma bandera `APP_ENV`. Así que, como tenemos aquí `APP_ENV=prod`, `cache:clear` sabe que debe ejecutarse en el entorno prod y borra la caché para ese entorno.

Gracias a esto, cuando refrescamos... ahora el título se actualiza. Volveré a cambiar esto por nuestro nombre genial, `Mixed Vinyl`.

## Cambiar el adaptador de caché sólo para prod

¡Vamos a probar otra cosa! Abre `config/packages/cache.yaml`. Nuestro servicio de caché utiliza actualmente el `ArrayAdapter`, que es una caché falsa. Eso puede estar bien para el desarrollo, pero no será de mucha ayuda en producción.

Veamos si podemos volver a cambiar al adaptador del sistema de archivos, pero sólo para el entorno`prod`. ¿Cómo? Aquí abajo, usa `when@prod` y luego repite las mismas claves. Así que `framework`, `cache`, y luego `app`. Pon esto en el adaptador que queremos, que se llama `cache.adapter.filesystem`.

Va a ser muy fácil ver si esto funciona porque seguimos volcando el servicio de caché en nuestro controlador. Ahora mismo, es un `ArrayAdapter`. Si refrescamos... ¡sorpresa! Sigue siendo un `ArrayAdapter`. ¿Por qué? Porque estamos en el entorno de producción... y prácticamente cada vez que haces un cambio en el entorno `prod`, tienes que reconstruir tu caché.

Vuelve a tu terminal y ejecuta

```terminal
php bin console cache:clear
```

de nuevo y ahora... lo tienes - ¡ `FilesystemAdapter`!

Pero... vamos a invertir esta configuración. Copia `cache.adapter.array` y cámbialo por`filesystem`. Lo usaremos por defecto. Luego, en la parte inferior, cambia a `when@dev`, y esto a `cache.adapter.array`.

¿Por qué hago esto? Bueno, esto literalmente no supone ninguna diferencia en los entornos `dev` y`prod`. Pero si decidimos empezar a escribir pruebas más adelante, que se ejecuten en el entorno de pruebas, con esta nueva configuración, el entorno de pruebas utilizará el mismo servicio de caché que el de producción... lo que probablemente sea más realista y mejor para las pruebas.

Para asegurarte de que esto sigue funcionando, borra la caché una vez más. Refresca y... ¡funciona! Seguimos teniendo `FilesystemAdapter`. Y... si volvemos al entorno `dev`en `.env`... y refrescamos... ¡sí! La barra de herramientas de depuración de la web ha vuelto, y aquí abajo, ¡volvemos a utilizar `ArrayAdapter`!

Ahora bien, en realidad, es probable que nunca cambies al entorno `prod` mientras estés desarrollando localmente. Es difícil trabajar con él... ¡y no tiene sentido! ¡El entorno `prod` está realmente pensado para producción! Y así, ejecutarás ese comando `bin/console cache:clear` durante el despliegue... pero probablemente casi nunca en tu máquina local.

Antes de continuar, entra en `VinylController`, baja a `browse()`, y saca ese `dump()`.

Bien, ¡comprobación de estado! Primero, todo en Symfony lo hace un servicio. Segundo, los bundles nos dan servicios. Y tercero, podemos controlar cómo se instancian esos servicios a través de las diferentes configuraciones de bundle en `config/packages/`.

Ahora, vamos a dar un paso importante creando nuestro propio servicio.
