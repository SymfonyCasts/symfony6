# Crear un servicio

Sabemos que los bundles nos dan servicios y los servicios funcionan. De acuerdo. ¿Pero qué pasa si necesitamos escribir nuestro propio código personalizado que sí funcione? ¿Deberíamos... ponerlo en nuestra propia clase de servicio? Por supuesto Y es una gran manera de organizar tu código.

Ya estamos haciendo algo de trabajo en nuestra aplicación. En la acción `browse()`: hacemos una petición HTTP y almacenamos en caché el resultado. Poner esta lógica en nuestro controlador está bien. Pero al trasladarla a su propia clase de servicio, hará que el propósito del código sea más claro, nos permitirá reutilizarlo desde múltiples lugares... e incluso nos permitirá hacer pruebas unitarias de ese código si queremos.

## Crear la clase de servicio

Eso suena increíble, así que ¡hagámoslo! ¿Cómo creamos un servicio? En el directorio `src/`, crea una nueva clase PHP donde quieras. En serio, no importa qué directorios o subdirectorios crees en `src/`: haz lo que te parezca.

Para este ejemplo, crearé un directorio `Service/` -aunque de nuevo, podrías llamarlo `PizzaParty` o `Repository` - y dentro de él, una nueva clase PHP. Llamémosla... qué tal `MixRepository`. "Repositorio" es un nombre bastante común para un servicio que devuelve datos. Observa que cuando creo esto, PhpStorm añade automáticamente el espacio de nombres correcto. No importa cómo organicemos nuestras clases dentro de `src/`... siempre que nuestro espacio de nombres coincida con el directorio.

Una cosa importante sobre las clases de servicio: no tienen nada que ver con Symfony. Nuestra clase de controlador es un concepto de Symfony. Pero `MixRepository` es una clase que estamos creando para organizar nuestro propio código. Eso significa que... ¡no hay reglas! No necesitamos extender una clase base o implementar una interfaz. Podemos hacer que esta clase se vea y se sienta como queramos. ¡El poder!

Con esto en mente, vamos a crear un nuevo `public function` llamado, qué tal,`findAll()` que será `return` un `array` de todas las mezclas de nuestro sistema. De vuelta en `VinylController`, copia toda la lógica que obtiene las mezclas y pégala aquí. PhpStorm nos preguntará si queremos añadir una declaración `use` para el`CacheItemInterface`. ¡Lo hacemos totalmente! Entonces, en lugar de crear una variable `$mixes`, sólo `return`.

Hay algunas variables no definidas en esta clase... y esas serán un problema, pero ignóralas por un momento: Primero quiero ver si podemos utilizar nuestro nuevo y brillante`MixRepository`.

## ¿Nuestro Servicio ya está en el Contenedor?

Entra en `VinylController`. Pensemos: de alguna manera tenemos que informar al contenedor de servicios de Symfony sobre nuestro nuevo servicio para poder autocablearlo de la misma manera que estamos autocableando servicios centrales como `HtttpClientInterface` y `CacheInterface`.

Vaya, ¡tengo una sorpresa! Dirígete a tu terminal y ejecuta:

```terminal
php bin/console debug:autowiring --all
```

Desplázate hasta la parte superior y... ¡sorpresa! ¡ `MixRepository` ya es un servicio en el contenedor! Deja que te explique dos cosas. En primer lugar, la bandera `--all` no es tan importante. Básicamente le dice a este comando que te muestre los servicios principales como`$httpClient` y `$cache`, además de nuestros propios servicios como `MixRepository`.

En segundo lugar, el contenedor... de alguna manera ya vio nuestra clase de repositorio y la reconoció como un servicio. Aprenderemos cómo ocurrió eso en unos minutos... pero por ahora, basta con saber que nuestro nuevo `MixRepository` ya está dentro del contenedor y su id de servicio es el nombre completo de la clase. ¡Eso significa que podemos autocablearlo!

## Autoconexión del nuevo servicio

De vuelta a nuestro controlador, añade un tercer argumento de tipo `MixRepository` - pulsa el tabulador para añadir la declaración `use` - y llámalo... ¿qué tal `$mixRepository`? Luego, aquí abajo, ya no necesitamos nada de este código `$mixes`. Sustitúyelo por `$mixes = $mixRepository->findAll()`.

¿Qué te parece? ¿Funcionará? ¡Averigüémoslo! Actualiza y... ¡funciona! Vale, que funcione en este caso significa que obtenemos un mensaje `Undefined variable $cache` procedente de `MixRepository`. Pero el hecho de que nuestro código haya llegado hasta aquí significa que el autocableado de `MixRepository` ha funcionado: el contenedor lo ha visto, ha instanciado`MixRepository` y nos lo ha pasado para que podamos utilizarlo.

¡Así que hemos creado un servicio y lo hemos puesto a disposición del autocableado! Pero nuestro nuevo servicio necesita los servicios `$httpClient` y `$cache` para poder hacer su trabajo. ¿Cómo los conseguimos? La respuesta es uno de los conceptos más importantes de Symfony y de la codificación orientada a objetos en general: la inyección de dependencias. Vamos a hablar de ello a continuación.