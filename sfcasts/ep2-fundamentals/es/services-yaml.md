# Todo sobre services.yaml

Cuando Symfony arranca por primera vez, necesita obtener la lista completa de todos los servicios que deben estar en el contenedor. Esto incluye el ID del servicio, su nombre de clase y todos los argumentos de su constructor. La primera y mayor fuente de servicios son los bundles. Si ejecutas

```terminal
php bin/console debug:container
```

la gran mayoría de estos servicios provienen de bundles. El segundo lugar del que el contenedor obtiene servicios es nuestro código. Y para conocer nuestros servicios, Symfony lee `services.yaml`.

## La sección especial _defaults

En el momento en que Symfony comienza a analizar la primera línea de este archivo, nada en nuestro directorio `src/` ha sido registrado como servicio en el contenedor. Esto es realmente importante. Añadir nuestras clases al contenedor es, de hecho, el trabajo de este archivo Y la forma en que lo hace es bastante sorprendente. ¡Hagamos un recorrido!

Fíjate en que la configuración está bajo una clave `services`. Al igual que `parameters`, ésta es una clave especial. Y, como su nombre indica, todo lo que está bajo ella está destinado a configurar servicios.

La primera subclave bajo ésta es `_defaults`. `_defaults` es una clave mágica que nos permite definir algunas opciones por defecto que se añadirán a todos los servicios que se registren en este archivo. Así que todos los servicios que registremos a continuación tendrán automáticamente `autowire: true` y `autoconfigure: true`.

Veamos un ejemplo. Lo más básico que puedes hacer con la clave `services`es... ¡registrar un servicio! Eso es lo que estamos haciendo en la parte inferior. Esto le dice al contenedor que debe haber un servicio `App\Service\MixRepository` en el contenedor y especificamos una opción: `bind`.

En realidad, los servicios pueden tener un montón de opciones, incluyendo `autowire` y`autoconfigure`. Así que sería totalmente legal decir: `autowire: true` y`autoconfigure: true` aquí. Esto funcionaría perfectamente. Pero gracias a la sección`_defaults`, ¡no son necesarias! El `_defaults` dice:

> A menos que se haya anulado en un servicio específico, establece `autowire` y
> `autoconfigure` a `true` para todos los servicios de este archivo.

¿Y qué hace `autowire`? Muy sencillo Le dice al contenedor de Symfony:

> ¡Oye! Por favor, intenta adivinar los argumentos de mi constructor mirando sus sugerencias de tipo.

Esta función es bastante impresionante... y por eso está activada automáticamente para todos nuestros servicios. La otra opción - `autoconfigure` - es más sutil y hablaremos de ella más adelante.

## Registro automático de servicios

Muy bien, cuando llegamos a la línea `_defaults`, hemos establecido alguna configuración por defecto... pero aún no hemos registrado ningún servicio. Ese es el trabajo de la siguiente sección... y es la clave de todo. Esta sintaxis especial dice

> Mira dentro del directorio `src/` y registra automáticamente todas
> las clases de PHP como servicio... excepto estas tres cosas.

Por eso, inmediatamente después de crear la clase `MixRepository`, ¡ya estaba en el contenedor! Y gracias a la sección `_defaults`, cualquier servicio registrado por ésta tendrá automáticamente `autowire: true` y`autoconfigure: true`. ¡Eso es un gran trabajo en equipo! Este mecanismo se denomina "Registro automático de servicios".

Pero recuerda que cada servicio del contenedor debe tener un ID único. Si vuelves a mirar `debug:container`, la mayoría de los ID de servicio son de tipo serpiente. Permíteme alejar el zoom un poco para que sea más fácil de ver. ¡Mejor! Así, por ejemplo, el servicio `Twig` tiene el ID de `twig` en forma de serpiente. Pero si te desplazas hasta la parte superior de esta lista, nuestro ID de`MixRepository` es... el nombre completo de la clase.

¡Sí! Cuando utilizas el registro automático de servicios, éste utiliza el nombre de la clase como ID de la clase y del servicio. Esto se hace por simplicidad... pero también para el autocableado. Cuando intentemos autocablear `MixRepository` en nuestro controlador o en cualquier otro lugar, para saber qué servicio pasarnos, Symfony buscará un servicio cuyo ID coincida exactamente con `App\Service\MixRepository`. Así que el auto-registro de servicios no sólo registra nuestras clases como servicios, sino que lo hace de forma que las hace auto-cableables. ¡Eso es increíble!

## ¿Autorregistro de no servicios?

De todos modos, después de esta sección aquí, cada clase en `src/` está ahora registrada como servicio en el contenedor. Excepto, bueno... que no queremos que todas las clases de`src/` sean un servicio.

En realidad hay dos tipos de clases en tu aplicación: las "clases de servicio" que hacen el trabajo, y las "clases modelo" -a veces llamadas "DTO"- cuyo trabajo consiste principalmente en mantener los datos, como una clase `Product` con las propiedades `name` y `price`. Queremos que el contenedor se encargue de instanciar nuestros servicios. Pero en el caso de las clases modelo, las crearemos siempre que las necesitemos, como con `$product = new Product()`. Por lo tanto, éstas no serán servicios en el contenedor.

En el siguiente tutorial, crearemos clases de entidad Doctrine, que son clases modelo para la base de datos. Éstas vivirán en el directorio `src/Entity/`... y como no están destinadas a ser servicios, ese directorio está excluido. Así que registramos todo en el directorio `src/` como servicio, excepto estas tres cosas.

Pero... ¡dato curioso! Esta clave `exclude` no es tan importante. Podrías eliminarla y todo seguiría funcionando Si accidentalmente registras como servicio algo que no debe serlo, ¡no te preocupes! Como nunca intentarás autoconectar y utilizar esa clase como un servicio, Symfony se dará cuenta de que no se está utilizando y la eliminará del contenedor. ¡Vaya, qué inteligente!

## Configuración de servicios personalizados

Así que todo en `src/` se registra automáticamente como un servicio sin que tengamos que hacer nada ni tocar este archivo.

Pero... de vez en cuando, necesitarás añadir una configuración extra a un servicio concreto. Eso es lo que ocurrió con `MixRepository` gracias a su argumento no autoadministrable`$isDebug`.

Para solucionarlo, al final de este archivo, registraremos un nuevo servicio cuyo ID y clase es `App\Service\MixRepository`. En realidad, esto anulará el servicio que se creó durante el registro automático de servicios, ya que ambos ID coincidirán con`App\Service\MixRepository`. Por tanto, estamos definiendo un nuevo servicio.

Pero gracias a `_defaults`, tiene automáticamente `autowire: true` y`autoconfigure: true`. Entonces añadimos la opción adicional `bind`.

Así que lo único que tenemos que poner al final de este archivo son los servicios que necesitan una configuración adicional para funcionar. Y... en realidad hay una forma más genial de arreglar los argumentos no autoconfigurables que te mostraré a continuación.

## ¡Todos los archivos de configuración son iguales!

Pero antes de llegar a eso, quiero mencionar una cosa más: este archivo,`services.yaml`, se carga mediante el mismo sistema que carga todos los archivos en`config/packages/`. De hecho, no hay ninguna diferencia técnica entre este archivo y digamos... `framework.yaml`. Así es Si quisiéramos, podríamos copiar y borrar el contenido de `services.yaml`, pegarlo en `framework.yaml`, y todo funcionaría exactamente igual.

Excepto que... tendríamos que, ya sabes, corregir estas rutas ya que estamos un directorio más abajo. ¡Observa! Moveré esto rápidamente y... ¡esto sigue funcionando bien! ¡Genial! Volvamos a ponerlo como estaba y... ya está.

La única razón por la que tenemos un archivo `service.yaml` es para organizarnos. Es bueno tener un solo archivo para "configurar tus servicios". Lo verdaderamente importante es que toda esta configuración vive bajo la clave `services`. De hecho, cerca de la parte superior de este archivo, verás que hay una clave `parameters` vacía.

En `cache.yaml`, creamos allí una clave `parameters` para registrar un nuevo parámetro. Realmente depende de nosotros decidir dónde queremos definir este parámetro. Podemos hacerlo en `cache.yaml` o, para mantener todos los parámetros en un solo lugar, podríamos copiar esto y trasladarlo a `services.yaml`.

En `cache.yaml`, también cogeré el `when@dev`, lo borraré y lo pegaré en`services.yaml`. A nivel técnico, eso no supone ninguna diferencia y nuestra aplicación sigue funcionando. Pero me gusta más esto. Los servicios y los parámetros son una idea global en tu app... así que es bueno organizarlos todos en un solo archivo.

De acuerdo, la única razón por la que escribimos algún código en la parte inferior de `services.yaml`fue para decirle al contenedor qué debe pasar al argumento no autoadministrable `$isDebug`. Pero, ¿y si te digo que hay una forma más automática de resolver estos argumentos problemáticos? Eso a continuación.
