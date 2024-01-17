# Generar Urls y bin/console

Hay dos formas diferentes de interactuar con nuestra aplicación. La primera es a través del servidor web... ¡y eso es lo que hemos hecho! Llegamos a una URL y... entre bastidores, se ejecuta `public/index.php`, que arranca Symfony, llama al enrutamiento y ejecuta nuestro controlador.

## Hola bin/console

¿Cuál es la segunda forma de interactuar con nuestra aplicación? Todavía no la hemos visto: es a través de una herramienta de línea de comandos llamada `bin/console`. En tu terminal ejecuta:

```terminal
php bin/console
```

... para ver un montón de comandos dentro de este script. Me encanta esta cosa. Está lleno de cosas que nos ayudan a depurar, con el tiempo tendrá comandos de generación de código, comandos para establecer secretos: todo tipo de cosas buenas que iremos descubriendo poco a poco.

Pero quiero señalar que... ¡no hay nada especial en este script de `bin/console`! Es sólo un archivo: hay literalmente un directorio `bin/` con un archivo `console`dentro. Probablemente nunca necesitarás abrir este archivo ni pensar en él, pero es útil. Ah, y en la mayoría de los sistemas, puedes simplemente ejecutar:

```terminal
./bin/console
```

... que se ve mejor. O a veces puedes ver que ejecute:

```terminal
symfony console
```

... que no es más que otra forma de ejecutar este archivo. Hablaremos más de esto en un futuro tutorial.

## bin/consola debug:router

El primer comando que quiero comprobar dentro de `bin/console` es `debug:router`:

```terminal-silent
php bin/console debug:router
```

Esto es impresionante. Nos muestra todas las rutas de nuestra aplicación, como nuestras dos rutas para `/` y `/browse/{slug}`. ¿Qué son estas otras rutas? Vienen de la barra de herramientas de depuración web y del sistema de perfilado... y sólo están aquí mientras desarrollamos localmente.

Bien, de vuelta a nuestro sitio.... en la parte superior de la página, tenemos dos enlaces no funcionales a la página de inicio y a la página de navegación. Vamos a conectarlos. Abre `templates/
base.html.twig`... y busca las etiquetas `a`. Ya está.

Así que sería muy fácil hacer que esto funcionara con sólo `href="/"`. Pero en lugar de eso, cada vez que enlacemos una página en Symfony, vamos a pedir al sistema de enrutamiento que nos genere una URL. Diremos

> Por favor, genera la URL de la ruta de la página de inicio, o de la ruta de la página de navegación.

Así, si alguna vez cambiamos la URL de una ruta, todos nuestros enlaces se actualizarán instantáneamente. Magia.

## Cómo nombrar tu ruta

Empecemos por la página de inicio. ¿Cómo le pedimos a Symfony que genere una URL para esta ruta? Bueno, primero tenemos que dar un nombre a la ruta. ¡Sorpresa! Cada ruta tiene un nombre interno. Puedes verlo en `debug:router`. Nuestras rutas se llaman `app_vinyl_homepage` y `app_vinyl_browse`. Huh, esos son los nombres exactos de mis tortugas mascota cuando era niño.

¿De dónde vienen estos nombres? Por defecto, Symfony nos genera automáticamente un nombre, lo cual está bien. El nombre no se utiliza en absoluto hasta que generamos una URL a la misma. Y en cuanto necesitemos generar una URL a una ruta, recomiendo encarecidamente tomar el control de este nombre... sólo para asegurarnos de que nunca cambia accidentalmente.

Para ello, busca la ruta y añade un argumento: `name` ajustado a, qué tal,`app_homepage`. Me gusta utilizar el prefijo `app_`: facilita la búsqueda del nombre de la ruta más adelante.

[[[ code('25d040ab5a') ]]]

Por cierto, los atributos de PHP 8 -como este atributo `Route` - están representados por clases PHP reales y físicas. Si mantienes pulsado command o ctrl, puedes abrirlo y mirar dentro. Esto es genial: el método `__construct()` muestra todas las diferentes opciones que puedes pasar al atributo.

Por ejemplo, hay un argumento `name`... y entonces estamos utilizando la sintaxis de argumentos con nombre de PHP para pasar esto al atributo. Abrir un atributo es una buena manera de conocer sus opciones.

## Generar una URL desde Twig

De todos modos, ahora que le hemos dado un nombre, vuelve a nuestro terminal y ejecuta de nuevo`debug:router`:

```terminal-silent
php bin/console debug:router
```

Esta vez... ¡sí! ¡La ruta se llama `app_homepage`! Cópialo y vuelve a `base.html.twig`. Para generar una URL dentro de twig, di `{{` -porque vamos a imprimir algo- y luego utiliza una función Twig llamada `path()`. Pásale el nombre de la ruta.

[[[ code('8a471c3074') ]]]

Ya está Actualiza... ¡y el enlace de aquí arriba funciona!

Falta un enlace más. Ya conocemos el primer paso: dar un nombre a la ruta. Así que `name:` y, qué tal, `app_browse`.

[[[ code('c8ff1fc6dd') ]]]

Copia eso, y... desplázate un poco hacia abajo. Aquí está: "Examinar mezclas". Cámbialo por `{{ path('app_browse') }}`.

[[[ code('802a4800ee') ]]]

Y ahora... ¡ese enlace también funciona!

## Generar URLs con comodines

Pero en esta página, tenemos algunos enlaces rápidos para ir a la página de exploración de un género específico. Y éstos aún no funcionan.

Esto es interesante. Queremos generar una URL como antes... pero esta vez necesitamos pasar algo al comodín `{slug}`. Abre `browse.html.twig`. Así es como lo hacemos. La primera parte es la misma: `{{ path() }}` y luego el nombre de la ruta: `app_browse`.

Si nos detuviéramos aquí, se generaría `/browse`. Para pasar valores a cualquier comodín de una ruta, `path()` tiene un segundo argumento: una matriz asociativa de esos valores. Y, de nuevo, al igual que en JavaScript, para crear una "matriz asociativa", utilizas`{` y `}`. Voy a pulsar intro para dividir esto en varias líneas... sólo para que sea legible. Dentro añade una clave `slug` a la matriz... y como este es el género "Pop", ponla en `pop`.

¡Genial! Repitamos esto dos veces más: `{{ path('app_browse') }}` pasar las llaves para un array asociativo, con `slug` fijado en `rock`. Y luego una vez más aquí abajo... que haré muy rápidamente.

[[[ code('4fa3d35edf') ]]]

¡Vamos a ver si funciona! Actualiza. ¡Ah! La variable `rock` no existe. Seguro que alguno de vosotros me ha visto hacer eso. Me olvidé de las comillas, así que esto parece una variable.

Inténtalo de nuevo. Ya está. Y prueba los enlaces... ¡sí! ¡Funcionan!

Siguiente: hemos creado dos páginas HTML. Ahora vamos a ver cómo queda la creación de una ruta de la API JSON.