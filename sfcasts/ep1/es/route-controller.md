# Rutas, controladores y respuestas

Tengo que decir que echo de menos los años 90. Bueno, no los beanie babies y... definitivamente no la forma de vestir de entonces, pero... las cintas de mezclas. Si no eras un niño en los 80 o los 90, quizá no sepas lo difícil que era compartir tus canciones favoritas con tus amigos. Oh sí, estoy hablando de un mashup de Michael Jackson, Phil Collins y Paula Abdul. La perfección.

Para aprovechar esa nostalgia, pero con un toque hipster, vamos a crear una nueva aplicación llamada Mixed Vinyl: una tienda en la que los usuarios pueden crear cintas de mezclas, con Boyz || Men, Mariah Carey y Smashing Pumpkins... sólo que prensadas en un disco de vinilo. Hmm, puede que tenga que poner un tocadiscos en mi coche.

La página que estamos viendo, que es súper bonita y cambia de color cuando refrescamos... no es una página real. Es sólo una forma de que Symfony nos diga "hola" y nos enlace a la documentación. Y por cierto, la documentación de Symfony es genial, así que no dudes en consultarla mientras aprendes.

## Rutas y controladores

Vale: todo framework web en cualquier lenguaje tiene el mismo trabajo: ayudarnos a crear páginas, ya sean páginas HTML, respuestas JSON de la API o arte ASCII. Y casi todos los marcos lo hacen de la misma manera: mediante un sistema de rutas y controladores. La ruta define la URL de la página y apunta a un controlador. El controlador es una función PHP que construye esa página.

Así que ruta + controlador = página. Son matemáticas, gente.

## Crear el controlador

Vamos a construir estas dos cosas... un poco al revés. Así que primero, vamos a crear la función del controlador. En Symfony, la función del controlador es siempre un método dentro de una clase PHP. Te lo mostraré: en el directorio `src/Controller/`, crea una nueva clase PHP. Vamos a llamarla `VinylController`, pero el nombre puede ser cualquier cosa.

[[[ code('6709e819e9') ]]]

Y, ¡felicidades! ¡Es nuestra primera clase PHP! ¿Y adivina dónde vive? En el directorio `src/`, donde vivirán todas las clases PHP. Y en general, no importa cómo organices las cosas dentro de `src/`: normalmente puedes poner las cosas en el directorio que quieras y nombrar las clases como quieras. Así que da rienda suelta a tu creatividad.

***TIP
En realidad, los controladores deben vivir en `src/Controller/`, a menos que cambies alguna configuración. La mayoría de las clases de PHP pueden vivir en cualquier lugar de `src/`.
***

Pero hay dos reglas importantes. En primer lugar, fíjate en el espacio de nombres que PhpStorm ha añadido sobre la clase: `App\Controller`. Independientemente de cómo decidas organizar tu directorio `src/`, el espacio de nombres de una clase debe coincidir con la estructura del directorio... empezando por `App`. Puedes imaginar que el espacio de nombres `App\` apunta al directorio`src/`. Entonces, si pones un archivo en un subdirectorio `Controller/`, necesita una parte `Controller` en su espacio de nombres.

Si alguna vez metes la pata, por ejemplo, si escribes algo mal o te olvidas de esto, lo vas a pasar mal. PHP no podrá encontrar la clase: obtendrás un error de "clase no encontrada". Ah, y la otra regla es que el nombre de un archivo debe coincidir con el nombre de la clase dentro de él, más `.php`. Por lo tanto, `VinylController.php`. Seguiremos esas dos reglas para todos los archivos que creemos en `src/`.

## Crear el controlador

Volvemos a nuestra tarea de crear una función de controlador. Dentro, añade un nuevo método público llamado `homepage()`. Y no, el nombre de este método tampoco importa: prueba a ponerle el nombre de tu gato: ¡funcionará!

Por ahora, sólo voy a poner una declaración `die()` con un mensaje.

[[[ code('66d737c13c') ]]]

## Crear la ruta

¡Buen comienzo! Ahora que tenemos una función de controlador, vamos a crear una ruta, que define la URL de nuestra nueva página y apunta a este controlador. Hay varias formas de crear rutas en Symfony, pero casi todo el mundo utiliza atributos.

Así es como funciona. Justo encima de este método, decimos `#[]`. Esta es la sintaxis de atributos de PHP 8, que es una forma de añadir configuración a tu código. Empieza a escribir `Route`. Pero antes de que termines, fíjate en que PhpStorm lo está autocompletando. Pulsa el tabulador para dejar que termine.

Eso, muy bien, completó la palabra `Route` para mí. Pero lo más importante es que ha añadido una declaración `use` en la parte superior. Siempre que utilices un atributo, debes tener una declaración `use` correspondiente en la parte superior del archivo.

Dentro de `Route`, pasa `/`, que será la URL de nuestra página.

[[[ code('ffeff58236') ]]]

Y... ¡listo! Esta ruta define la URL y apunta a este controlador... simplemente porque está justo encima de este controlador.

¡Vamos a probarlo! Refresca y... ¡felicidades! ¡Symfony miró la URL, vio que coincidía con la ruta - `/` o sin barra es lo mismo para la página de inicio - ejecutó nuestro controlador y golpeó la declaración `die`!

Ah, y por cierto, sigo diciendo función del controlador. Comúnmente se llama simplemente "controlador" o "acción"... sólo para confundir.

## Devolver una respuesta

Bien, dentro del controlador -o acción- podemos escribir el código que queramos para construir la página, como hacer consultas a la base de datos, llamadas a la API, renderizar una plantilla, lo que sea. Al final vamos a hacer todo eso.

Lo único que le importa a Symfony es que tu controlador devuelva un objeto`Response`. Compruébalo: escribe `return` y luego empieza a escribir `Response`. Woh: hay bastantes clases `Response` ya en nuestro código... ¡y dos son de Symfony! Queremos la de HTTP foundation. HTTP foundation es una de esas librerías de Symfony... y nos da bonitas clases para cosas como la Petición, la Respuesta y la Sesión. Pulsa el tabulador para autocompletar y termina eso.

Oh, debería haber dicho devolver una nueva respuesta. Así está mejor. Ahora dale al tabulador. Cuando dejé que `Response` autocompletara la primera vez, muy importante, PhpStorm añadió esta declaración de uso en la parte superior. Cada vez que hagamos referencia a una clase o interfaz, tendremos que añadir una sentencia `use` al principio del archivo en el que estemos trabajando.

Al dejar que PhpStorm autocompletara eso por mí, añadió la declaración `use` automáticamente. Lo haré cada vez que haga referencia a una clase. Ah, y si todavía eres un poco nuevo en lo que respecta a los espacios de nombres de PHP y las declaraciones `use`, echa un vistazo a nuestro breve y gratuito tutorial sobre espacios de nombres de PHP.

De todos modos, dentro de `Response`, podemos poner lo que queramos devolver al usuario: HTML, JSON o, por ahora, un simple mensaje, como el título del vinilo Mixto en el que estamos trabajando: PB y jams.

[[[ code('6750c9c02f') ]]]

Bien, equipo, ¡vamos a ver qué pasa! Actualiza y... ¡PB y mermeladas! Puede que no parezca gran cosa, ¡pero acabamos de construir nuestra primera página Symfony totalmente funcional! ¡Ruta + controlador = beneficio!

Y acabas de aprender la parte más fundamental de Symfony... y sólo estamos empezando. Ah, y como nuestros controladores siempre devuelven un objeto `Response`, es opcional, pero puedes añadir un tipo de retorno a esta función si lo deseas. Pero eso no cambia nada: sólo es una forma agradable de codificar.

[[[ code('32c60928e5') ]]]

A continuación me siento bastante seguro. Así que vamos a crear otra página, pero con una ruta mucho más elegante que coincide con un patrón comodín.
