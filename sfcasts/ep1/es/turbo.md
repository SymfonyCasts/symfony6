# Turbo: Supercarga tu aplicación

Bienvenido al último capítulo de nuestro tutorial de introducción a Symfony 6. Si estás viendo esto, ¡lo estás petando! Y es hora de celebrarlo instalando un paquete más de Symfony. Pero antes de hacerlo, como sabes, me gusta confirmar todo primero... por si el nuevo paquete instala una receta interesante:

```terminal-silent
git add .
git commit -m "Never gonna let you go..."
```

## Instalando symfony/ux-turbo

Bien, vamos a instalar el nuevo paquete:

```terminal
composer require symfony/ux-turbo
```

¿Ves ese "ux" en el nombre del paquete? Symfony UX es un conjunto de bibliotecas que añaden funcionalidad JavaScript a tu aplicación... a menudo con algo de código PHP para ayudar. Por ejemplo, hay una biblioteca para renderizar gráficos... y otra para usar un Cropper de imágenes con el sistema de formularios.

## Recetas UX de Symfony

Así que, como puedes ver, esto instaló una receta. OoOOo. Ejecuta

```terminal
git status
```

para que podamos ver lo que ha hecho. La mayor parte es normal, como `config/bundles.php`que significa que habilitó el nuevo bundle. Los dos cambios interesantes son`assets/controllers.json` y `package.json`. Comprobemos primero `package.json`.

Cuando instalas un paquete UX, lo que suele significar es que te estás integrando con una biblioteca JavaScript de terceros. Y así, la receta de ese paquete añade esa biblioteca a tu código. En este caso, la biblioteca JavaScript con la que nos estamos integrando se llama `@hotwired/turbo`. Además, el propio paquete PHP `symfony/ux-turbo` viene con algo de JavaScript adicional. Esta línea especial dice

> ¡Hey Node! Quiero incluir un paquete llamado `@symfony/ux-turbo`... pero en lugar de
> de descargarlo, puedes encontrar su código en el directorio
> directorio `vendor/symfony/ux-turbo/Resources/assets`.

Puedes buscar literalmente en esa ruta `vendor/symfony/ux-turbo/Resources/assets`
para encontrar un mini paquete JavaScript. Ahora, debido a que esto actualizó nuestro archivo `package.json`, tenemos que volver a instalar nuestras dependencias para descargarlo y tenerlo todo listo.

De hecho, busca tu terminal que está ejecutando `yarn watch`. Tenemos un error! Dice que no se puede encontrar el archivo `@symfony/ux-turbo/package.json`, intenta ejecutar`yarn install --force`.

¡Vamos a hacerlo! Pulsa control+C para detener esto... y luego ejecuta

```terminal
yarn install --force
```

o `npm install --force`. Luego, reinicia Encore con:

```terminal
yarn watch
```

El otro archivo que la receta modificó fue `assets/controllers.json`. Vamos a echarle un vistazo: `assets/controllers.json`. Esta es otra cosa que es exclusiva de Symfony UX. Normalmente, si queremos añadir un controlador Stimulus, lo ponemos en el directorio`controllers/`. Pero a veces, puede que instalemos un paquete PHP y que queramos añadir su propio controlador Stimulus en nuestra aplicación. Esta sintaxis dice básicamente

> ¡Hey Stimulus! Ve a cargar este controlador Stimulus desde ese nuevo
> `@symfony/ux-turbo` paquete.

Ahora bien, este controlador Stimulus en particular es un poco raro. No es uno que vayamos a utilizar directamente dentro de la función `stimulus_controller()` Twig. Es una especie de controlador falso. ¿Qué hace? Sólo con que se cargue, va a activar la biblioteca Turbo.

## ¡Hola Turbo! Por la actualización de la página completa

Sigo hablando de Turbo. ¿Qué es Turbo? Bueno, al ejecutar ese comando composer require... y luego reinstalar yarn, el JavaScript de Turbo está ahora activo y funcionando en nuestro sitio. ¿Qué hace? Es sencillo: convierte cada clic en un enlace y cada envío de un formulario de nuestro sitio en una llamada Ajax. Y eso hace que nuestro sitio sea rápido como un rayo.

Compruébalo. Haz una última actualización completa. Y luego observa... si hago clic en Examinar, ¡no hay actualización completa de la página! Si hago clic en estos iconos, ¡no hay actualización! Turbo intercepta esos clics, hace una llamada Ajax a la URL, y luego pone ese HTML en nuestro sitio. Esto es enorme porque, de repente, nuestra aplicación se ve y se siente como una aplicación de una sola página... ¡sin que nosotros hagamos nada!

## La barra de herramientas de depuración web y el perfilador de peticiones Ajax

Ahora, otra cosa interesante que notarás es que, aunque las recargas de páginas completas han desaparecido, estas llamadas Ajax aparecen en la barra de herramientas de depuración web. Y puedes hacer clic para ir a ver el perfil de esa llamada Ajax muy fácilmente. Esta parte de la barra de herramientas de depuración web es aún más útil con las llamadas Ajax para una ruta de la API. Si pulsamos el icono de reproducción... ese 7 acaba de subir a 8... ¡y aquí está el perfilador de esa petición de la API! Abriré ese enlace en una nueva ventana. Esa es una forma súper fácil de llegar al perfilador de cualquier petición Ajax.

Así que Turbo es increíble... y puede hacer más. Hay algunas cosas que debes saber sobre él antes de enviarlo a producción, y si te interesa, ¡sí! tenemos un tutorial completo sobre Turbo. Quería mencionarlo en este tutorial porque Turbo es más fácil si lo añades a tu aplicación desde el principio.

Muy bien, ¡felicidades! ¡El primer tutorial de Symfony 6 está en los libros! Date una palmadita en la espalda... o mejor, busca a un amigo y choca los cinco.

¡Y sigue adelante! Acompáñanos en el siguiente tutorial de esta serie, que te hará pasar de ser un desarrollador de Symfony en ciernes a alguien que realmente entiende lo que está pasando. Cómo funcionan los servicios, el sentido de todos estos archivos de configuración, los entornos Symfony, las variables de entorno y mucho más. Básicamente todo lo que necesitarás para hacer lo que quieras con Symfony.

Y si tienes alguna pregunta o idea, estamos aquí para ti en la sección de comentarios debajo del vídeo.

Muy bien amigos, ¡hasta la próxima!
