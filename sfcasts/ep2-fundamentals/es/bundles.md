# ¡Bundles!

¡Hola amigos! Bienvenidos de nuevo al Episodio 2 de nuestra serie de tutoriales sobre Symfony 6. Este es el episodio en el que subimos de nivel en serio y desbloqueamos nuestro potencial para hacer todo lo que queramos. Eso es porque, en este curso, nos sumergimos en los fundamentos detrás de todo en Symfony. Hablamos de servicios, bundles, configuración, entornos, variables de entorno - lo que realmente hace que Symfony funcione. Vamos a abrir el capó de Symfony y descubrir lo que hay dentro.

## ¡Configuración del Sitio!

Para sacar el máximo provecho de este tutorial de fundamentos, te invito a que te acerques al fuego, descargues el código del curso desde esta página y codifiques conmigo. ¡Será divertido! Después de descomprimir el archivo, encontrarás un directorio `start/` con el mismo código que ves aquí. Sigue nuestro archivo`README.md` hecho a mano y de origen local para obtener todas las instrucciones de configuración. El último paso será abrir un terminal, entrar en el proyecto y ejecutar

```terminal
symfony serve -d
```

para iniciar un servidor web local en `https://127.0.0.1:8000`. Haré trampa y haré clic en ese enlace para ver nuestro sitio. Es... ¡Mixed Vinyl! Nuestra nueva startup en la que los usuarios pueden crear su propia "mixtape" personalizada -estoy pensando en MMMBop seguido de algo de las Spice Girls-, salvo que te la entregamos directamente en tu puerta en un disco de vinilo recién prensado. Incluso añadimos ese olor a vieja colección de discos de forma gratuita!

## Servicios: Servicios en todas partes

En el tutorial anterior, hablamos brevemente de que todo en Symfony se hace en realidad mediante un servicio. Y que la palabra "servicio" es un término elegante para un concepto sencillo: un servicio es un objeto que hace un trabajo.

Por ejemplo, en `src/Controller/SongController.php`, aprovechamos el servicio Logger de Symfony para registrar un mensaje:

[[[ code('0d6d809ebb') ]]]

Y, aunque ya no tenemos el código en `VinylController`, utilizamos brevemente el servicio Twig para representar directamente una plantilla Twig:

[[[ code('f65f8310e4') ]]]

Así que un servicio no es más que un objeto que hace trabajo... y todo el trabajo que se hace en Symfony lo hace un servicio. Incluso el código central que calcula qué ruta coincide con la URL actual es un servicio, llamado servicio "router".

## Hola Bundles

Así que la siguiente pregunta es: ¿de dónde vienen estos servicios? La respuesta es Mordor. Me refiero a los bundles... los servicios vienen de los bundles.

Abre `config/bundles.php`:

[[[ code('796e719451') ]]]

No es un archivo que tengas que mirar o preocuparte mucho, pero aquí es donde se activan tus bundles.

Muy sencillo: los bundles son plugins de Symfony. No son más que código PHP... pero se enganchan a Symfony. Y gracias al sistema de recetas, cuando instalamos un nuevo bundle, ese bundle se añade automáticamente a este archivo, por lo que ya tenemos 8 bundles aquí. Cuando empezamos nuestro proyecto, ¡sólo teníamos 1!

Así que un bundle es un plugin de Symfony. Y los bundles pueden darnos varias cosas... aunque en gran medida existen por una razón: darnos servicios. Por ejemplo, este TwigBundle de aquí arriba nos da el servicio Twig. Si elimináramos esta línea, el servicio Twig dejaría de existir y nuestra aplicación explotaría... ya que estamos renderizando plantillas. Esta línea `render()` dejaría de funcionar. Y MonologBundle es lo que nos da el servicio Logger que estamos utilizando en `SongController`.

Así que al añadir más bundles a nuestra aplicación, estamos obteniendo más servicios, ¡y los servicios son herramientas! ¿Necesitas más servicios? ¡Instala más bundles! Es como Neo en la mejor, digo, primera película de Matrix.

A continuación... vamos a enseñar a nuestra aplicación algo de Kung fu instalando un nuevo bundle que nos proporcione un nuevo servicio para resolver un nuevo problema.