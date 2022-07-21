# Autoconexión con nombre y clientes HTTP con alcance

En `MixRepository`, sería genial que no tuviéramos que especificar el nombre del host cuando hacemos la petición HTTP. Sería genial que eso estuviera preconfigurado y sólo tuviéramos que incluir la ruta. Además, muy pronto, vamos a configurar un token de acceso que se utilizará cuando hagamos peticiones a la API de GitHub. Podríamos pasar ese token de acceso manualmente aquí en nuestro servicio, pero ¿a qué sería genial que el servicio HttpClient viniera preconfigurado para incluir siempre el token de acceso?

Entonces, ¿tiene Symfony una forma de "preconfigurar" el servicio HttpClient?  ¡La tiene! Se llama "scoped clients": una característica de HttpClient que permite crear varios servicios HttpClient, cada uno preconfigurado de forma diferente.

## Crear un Scoped Client

Así es como funciona. Abre `config/packages/framework.yaml`. Para crear un scoped client, bajo la clave `framework`, añade `http_client` seguido de `scoped_clients`. Ahora, dale a tu scoped client un nombre, como `githubContentClient`... ya que estamos utilizando una parte de su API que devuelve el contenido de los archivos. Añade también `base_uri`, ve copiando el nombre del host por aquí... y pégalo:

[[[ code('ebc7952f44') ]]]

Recuerda: el objetivo de estos archivos de configuración es cambiar los servicios del contenedor. El resultado final de este nuevo código es que se añadirá un segundo servicio HttpClient al contenedor. Lo veremos dentro de un minuto. Y, por cierto, no hay forma de que adivines que necesitas las claves `http_client` y `scoped_clients`para que esto funcione. La configuración es el tipo de cosa en la que realmente tienes que confiar en la documentación.

De todos modos, ahora que hemos preconfigurado este cliente, deberíamos poder entrar en`MixRepository` y hacer una petición directamente a la ruta:

[[[ code('2f3d36b908') ]]]

Pero si nos dirigimos y refrescamos... ah...

> URL no válida: falta el esquema [...]. ¿Te has olvidado de añadir "http(s)://"?

No creí que nos hubiéramos olvidado... ya que lo configuramos mediante la opción `base_uri`... pero parece que no funcionó. Y puede que hayas adivinado por qué. Busca tu terminal y ejecuta:

```terminal
php bin/console debug:autowiring client
```

Ahora hay dos servicios HttpClient en el contenedor: El normal, no configurado, y el que acabamos de configurar. Aparentemente, en`MixRepository`, Symfony nos sigue pasando el servicio HttpClient no configurado.

¿Cómo puedo estar seguro? Bueno, piensa en cómo funciona el autocableado. Symfony mira el tipo de pista de nuestro argumento, que es`Symfony\Contracts\HttpClient\HttpClientInterface`, y luego busca en el contenedor un servicio cuyo ID coincida exactamente. Es así de sencillo

## Obtener la versión con nombre de un servicio

Entonces... si hay varios servicios con el mismo "tipo" en nuestro contenedor, ¿sólo el principal es autoconvocable? Afortunadamente, ¡no! Podemos utilizar algo llamado "autoconexión con nombre"... y ya nos muestra cómo hacerlo. Si tecleamos un argumento con`HttpClientInterface` y nombramos el argumento `$githubContentClient`, Symfony nos pasará el segundo.

Probemos: cambiemos el argumento de `$httpClient` a `$githubContentClient`:

[[[ code('319f5e30f8') ]]]

y ahora... no funciona. Ups...

> Propiedad no definida `MixRepository::$httpClient`

Eso es... que me he descuidado. Cuando cambié el nombre del argumento, cambió el nombre de la propiedad. Así que... hay que ajustar el código de abajo:

[[[ code('2764098093') ]]]

Y ahora... ¡está vivo! ¡Acabamos de autocablear un servicio específico de HttpClientInterface!

A continuación, vamos a abordar otro problema complicado con la autoconexión, aprendiendo a obtener uno de los muchos servicios de nuestro contenedor que no está disponible para la autoconexión.
