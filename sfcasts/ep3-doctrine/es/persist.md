# Persistir en la base de datos

Ahora que tenemos una clase de entidad y la tabla correspondiente, ¡estamos listos para guardar algunas cosas! Entonces... ¿cómo insertamos filas en la tabla? Pregunta equivocada! Sólo vamos a centrarnos en crear objetos y guardarlos. Doctrine se encargará de las consultas de inserción por nosotros.

Para ayudar a hacer esto de la forma más sencilla posible, vamos a hacer una página falsa de "nueva mezcla de vinilo".

En el directorio `src/Controller/`, crea una nueva clase `MixController` y haz que ésta extienda la normal `AbstractController`. Perfecto Dentro, añade un`public function` llamado `new()` que devolverá un `Response` de HttpFoundation. Para que esto sea una página, arriba, utiliza el atributo `#[Route]`, dale a "tab" para autocompletarlo y llamemos a la URL `/mix/new`. Por último, para ver si esto funciona, `dd('new mix')`.

[[[ code('550f9491ea') ]]]

En el mundo real, esta página podría mostrar un formulario. Entonces, al enviar ese formulario, tomaríamos sus datos, crearíamos un objeto `VinylMix()` y lo guardaríamos. Trabajaremos en cosas así en un futuro tutorial. Por ahora, vamos a ver si esta página funciona. Dirígete a `/mix/new` y... ¡ya está!

Bien, ¡vamos a crear un objeto `VinylMix()`! Hazlo con `$mix = new VinylMix()`... ¡y entonces podremos empezar a poner datos en él! Vamos a crear una mezcla de uno de mis artistas favoritos de la infancia. Voy a establecer rápidamente algunas otras propiedades... tenemos que establecer, como mínimo, todas las propiedades que tienen columnas necesarias en la base de datos. Para `trackCount`, qué tal un poco de aleatoriedad para divertirse. Y, para `votes`, lo mismo... incluyendo votos negativos... aunque Internet nunca sería tan cruel como para votar a la baja ninguna de mis mezclas. Por último, `dd($mix)`.

[[[ code('1fe585eee9') ]]]

Hasta ahora, esto no tiene nada que ver con la Doctrine. Sólo estamos creando un objeto y poniendo datos en él. Estos datos están codificados, pero puedes imaginar que se sustituyen por lo que el usuario acaba de enviar a través de un formulario. Independientemente de dónde obtengamos los datos, cuando actualicemos... tendremos un objeto con datos en él. ¡Genial!

## Servicios vs. Entidades

Por cierto, nuestra clase de entidad, `VinylMix`, es la primera clase que hemos creado que no es un servicio. En general, hay dos tipos de clases. En primer lugar, están los objetos de servicio, como `TalkToMeCommand` o el `MixRepository` que creamos en el último tutorial. Estos objetos funcionan... pero no contienen ningún dato, aparte de quizás alguna configuración básica. Y siempre obtenemos los servicios del contenedor, normalmente mediante autoconexión. Nunca los instanciamos directamente.

El segundo tipo de clases son las clases de datos como `VinylMix`. El trabajo principal de estas clases es mantener los datos. No suelen hacer ningún trabajo, salvo quizá alguna manipulación básica de datos. Y a diferencia de los servicios, no obtenemos estos objetos del contenedor. En su lugar, los creamos manualmente donde y cuando los necesitemos, ¡como acabamos de hacer!

## ¡Hola Gestor de Entidades!

De todos modos, ahora que tenemos un objeto, ¿cómo podemos guardarlo? Bueno, guardar algo en la base de datos es un trabajo. Y por eso, no es de extrañar, ¡ese trabajo lo hace un servicio! Añade un argumento al método, indicado con `EntityManagerInterface`. Llamémoslo `$entityManager`.

`EntityManagerInterface` es, con mucho, el servicio más importante para Doctrine. Lo vamos a utilizar para guardar, e indirectamente cuando hagamos una consulta. Para guardar, llamamos a`$entityManager->persist()` y le pasamos el objeto que queremos guardar (en este caso, `$mix`). Luego también tenemos que llamar a `$entityManager->flush()` sin argumentos.

[[[ code('7fcf0dd0be') ]]]

Pero... espera. ¿Por qué tenemos que llamar a dos métodos?

Esto es lo que pasa. Cuando llamamos a `persist()`, en realidad no guarda el objeto ni habla con la base de datos. Sólo le dice a Doctrine:

> ¡Oye! Quiero que seas "consciente" de este objeto, para que luego, cuando llamemos a `flush()`,
> sabrá que debe guardarlo.

La mayoría de las veces, verás estas dos líneas juntas: `persist()` y luego`flush()`. La razón por la que está dividido en dos métodos es para ayudar a la carga de datos por lotes... donde podrías persistir un centenar de objetos de `$mix` y luego vaciarlos en la base de datos todos a la vez, lo que es más eficiente. Pero la mayoría de las veces, llamarás a `persist()` y luego a `flush()`.

Bien, para que sea una página válida, vamos a `return new Response()` de HttpFoundation y usaré `sprintf` para devolver un mensaje:`mix %d is %d tracks of pure 80\'s heaven`... y para esos dos comodines, pasaré `$mix->getId()` y `$mix->getTrackCount()`.

[[[ code('120c0e0334') ]]]

¡Vamos a probarlo! Muévete, refresca y... ¡sí! Vemos el "Mix 1". ¡Qué bien! En realidad, nunca pusimos el ID (lo que tiene sentido). Pero cuando guardamos, Doctrine cogió el nuevo ID y lo puso en la propiedad `id`.

Si refrescamos unas cuantas veces más, obtendremos las mezclas 2, 3, 4, 5 y 6. Es súper divertido. Todo lo que hemos tenido que hacer es persistir y vaciar el objeto. Doctrine se encarga de todas las consultas por nosotros.

Otra forma de demostrar que esto funciona es ejecutando:

```terminal
symfony console doctrine:query:sql 'SELECT * FROM vinyl_mix'
```

Esta vez sí vemos los resultados. ¡Genial!

Bien, ahora que tenemos cosas en la base de datos, ¿cómo las consultamos? Vamos a abordar eso a continuación.
