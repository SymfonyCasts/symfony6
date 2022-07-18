# Configurar el servicio de caché

Así que... quiero saber cómo puedo configurar el servicio de caché... como para almacenar la caché en otro lugar. En el mundo real, podemos simplemente buscar "Cómo configuro el servicio de caché de Symfony". Pero... también podemos averiguarlo por nuestra cuenta, utilizando los comandos que acabamos de aprender.

Ya nos hemos dado cuenta de que hay un archivo `cache.yaml`. Parece que FrameworkBundle se encarga de crear el servicio de caché... y tiene una clave sub `cache` donde podemos pasar algunos valores para controlarlo. Todo esto está comentado por el momento.

Para obtener más información sobre FrameworkBundle, ejecuta:

```terminal
php bin/console config:dump framework
```

FrameworkBundle es el bundle principal dentro de Symfony. Así que puedes ver que esto vuelca... wow... una tonelada. FrameworkBundle proporciona muchos servicios... así que hay mucha configuración.

## Depurando la configuración de la caché

Para... ampliar un poco, vuelve a ejecutar el comando, pasando `framework` y luego`cache` para filtrar por esa subclave:

```terminal-silent
php bin/console config:dump framework cache
```

Y... ¡genial! Puede que esto no sea siempre súper comprensible, pero es un gran punto de partida. Definitivamente, esto acaba de ayudarnos a responder a la pregunta

> ¿Por qué el sistema de caché almacena cosas en el directorio var/cache?

Porque... hay una clave `directory` que por defecto es `%kernel.cache_dir%`... que es una forma elegante de apuntar al directorio `/var/cache/dev`. Y luego vemos`/pools/app`, que es el directorio real que contiene nuestra caché.

## Utilizando dump() y el perfilador

Así que éste es el objetivo: en lugar de almacenar cosas en la caché del sistema de archivos, quiero cambiar el sistema de caché para almacenar en otro lugar. Antes de hacerlo, entra en`VinylController` y, para que podamos ver el resultado del cambio que vamos a hacer, en`dump($cache)`. Hasta ahora hemos utilizado `dd()`, que significa "volcar y morir". Pero en este caso quiero `dump()`... pero deja que la página se cargue.

Actualiza ahora. Espera, ¿dónde está mi volcado? Esto es una... ¡función! Cuando utilices `dump()`, en realidad no lo verás en la página: se esconde aquí abajo, en la barra de herramientas de depuración de la web. Si miras allí, la caché es una especie de `TraceableAdapter`. Pero dentro de ella hay un objeto llamado `FilesystemAdapter`. Eso es una prueba de que el sistema de caché está guardando en el sistema de archivos.

## Configurar el adaptador de caché

Para hacer que se almacene en otro lugar, entra en `cache.yaml` y cambia esta clave `app`. Puedes configurarla con varias cadenas especiales diferentes, llamadas adaptadores. Si quisiéramos almacenar nuestra caché en Redis, utilizaríamos `cache.adapter.redis`.

Para facilitar las cosas, utiliza `cache.adapter.array`. El adaptador `array` es una caché falsa en la que sí se almacenan cosas... pero sólo vive mientras dura la petición. Así que, al final de cada petición, se olvida de todo. Es una caché falsa, pero es suficiente para ver cómo el cambio de esta clave afectará al propio servicio de caché.

Observa lo que ocurre. Actualmente, tenemos un `FilesystemAdapter`. Cuando refrescamos... ¡la caché es un `ArrayAdapter`! Y como el `ArrayAdapter` se olvida de su caché al final de la petición, puedes ver que cada petición hace ahora una petición HTTP.

## Para llevar: Se trata de controlar cómo se instancian los servicios

Si estás un poco confundido por esto, déjame intentar aclarar las cosas. El objetivo de este capítulo no es enseñarte a cambiar esta clave específica en el archivo de caché. En última instancia, si necesitas configurar algo en Symfony, simplemente buscarás en los documentos... que te dirán exactamente qué hacer y qué clave cambiar.

No, la gran conclusión es que el único propósito de estos archivos de configuración es configurar los servicios de nuestra aplicación. Cada vez que cambias una clave en cualquiera de estos archivos, el resultado final es que acabas de cambiar la forma de instanciar algún servicio. Modificar una clave puede cambiar todo el nombre de la clase de un objeto de servicio, como en este caso, o puede cambiar el segundo o tercer argumento del constructor que se pasará cuando se instancie el servicio. En realidad no importa lo que cambie, siempre que te des cuenta de que esta configuración se refiere a los servicios y a cómo se instancian.

De hecho, nada de esta configuración puede leerse directamente desde tu aplicación. No podrías, por ejemplo, pedir la configuración de la "caché" desde dentro de un controlador. No, Symfony lee esta configuración, la utiliza para configurar cómo se instanciará cada objeto de servicio, y luego la desecha. Los servicios son supremos.

A continuación, a veces necesitarás que cierta configuración sea diferente en función de si estás desarrollando localmente o ejecutando en producción. Symfony tiene un sistema para esto llamado "entornos". Vamos a aprender todo sobre eso.
