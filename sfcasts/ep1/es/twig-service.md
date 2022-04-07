# El servicio Twig y el perfilador de peticiones de la API

Como esta página acaba de cargarse sin ningún error, pensamos que acabamos de registrar con éxito un mensaje a través del servicio de registro. Pero... ¿dónde van los mensajes de registro? ¿Cómo podemos comprobarlo?

El servicio de registro lo proporciona una biblioteca que hemos instalado antes, llamada monolog, que forma parte del paquete de depuración. Y puedes controlar su configuración dentro del archivo`config/packages/monolog.yaml`, incluyendo dónde se registran los mensajes de registro, por ejemplo, en qué archivo. Nos centraremos más en la configuración en el siguiente tutorial.

## El perfilador de peticiones de la API

Pero una forma de ver siempre los mensajes de registro de una petición es a través del perfilador Esto es muy útil. Ve a la página de inicio, haz clic en cualquier enlace de la barra de herramientas de depuración web... y luego ve a la sección Registros. Ahora veremos todos los mensajes de registro que se hicieron sólo durante esa última petición a la página de inicio.

¡Genial! Excepto que... nuestro mensaje de registro se hace en una ruta de la API... ¡y las rutas de la API no tienen una barra de herramientas de depuración web en la que podamos hacer clic! ¿Estamos atascados? No! Actualiza esta página una vez más... y luego ve manualmente a `/_profiler`. Esta es... una especie de puerta secreta al sistema de perfiles... y esta página muestra las últimas diez peticiones realizadas en nuestro sistema. La segunda en la parte superior es la petición de la API que acabamos de hacer. Haz clic en el pequeño enlace del token para ver... ¡sí! ¡Estamos viendo el perfil de esa petición de la API! En la sección de Registros... ¡ahí está!

> Respuesta de la API para la canción 5

... e incluso puedes ver la información extra que hemos pasado.

## Renderizar una plantilla Twig manualmente

Vale, los servicios son tan importantes que... Quiero hacer un ejemplo rápido más. Vuelve a `VinylController`. El método `render()` es realmente un atajo para obtener el servicio "Twig", llamar a algún método de ese objeto para renderizar la plantilla... y luego poner la cadena HTML final en un objeto `Response`. Es un gran atajo y deberías utilizarlo.

Pero! Como reto, ¿podríamos renderizar una plantilla sin usar ese método? ¡Por supuesto! Hagámoslo.

Primer paso: encontrar el servicio que hace el trabajo que necesitas hacer. Así que tenemos que encontrar el servicio Twig. Volvamos a hacer nuestro truco:

```terminal
php bin/console debug:autowiring twig
```

Y... ¡sí! Al parecer, el tipo de pista que tenemos que utilizar es `Twig\Environment`.

¡De acuerdo! Vuelve a nuestro método, añade un argumento, escribe `Environment`, y pulsa el tabulador para autocompletarlo y que PhpStorm añada la sentencia `use`. Vamos a llamarlo `$twig`.

A continuación, en lugar de usar `render`, digamos `$html =` y luego `$twig->`. Al igual que con el registrador, no necesitamos saber qué métodos tiene esta clase, porque, gracias a la sugerencia de tipo, PhpStorm puede decirnos todos los métodos. El método `render()` parece que es probablemente lo que queremos. El primer argumento es el nombre de la cadena de la plantilla a renderizar y el argumento `$context` contiene las variables. Así que... tiene los mismos argumentos que ya estábamos pasando.

Para ver si funciona, `dd($html)`.

[[[ code('9dd37843fc') ]]]

¡Hora de probar! Dirígete a la página de inicio... ¡y sí! ¡Acabamos de renderizar una plantilla manualmente! ¡Increíble! Y podemos terminar esta página envolviendo eso en una respuesta:`return new Response($html)`.

[[[ code('a862fa6787') ]]]

Y ahora... ¡la página funciona! Y entendemos que la verdadera forma de renderizar una plantilla es a través del servicio Twig. Algún día te encontrarás en una situación en la que necesites renderizar una plantilla pero no estés en un controlador... y por tanto no tengas el método abreviado `$this->render()`. Saber que hay un servicio Twig que puedes recuperar será la clave para resolver ese problema. Más sobre esto en el próximo tutorial.

Pero en una aplicación real, en un controlador, no hay razón para hacer todo este trabajo extra. Así que voy a revertir esto... y volver a usar `render()`. Y... entonces ya no necesitamos autocablear ese argumento... e incluso podemos limpiar la declaración`use`.

Aquí están los tres grandes, gigantescos e importantes puntos de partida. En primer lugar, Symfony está repleto de objetos que hacen su trabajo... a los que llamamos servicios. Los servicios son herramientas. Segundo, todo el trabajo en Symfony lo hace un servicio... incluso cosas como el enrutamiento. Y en tercer lugar, podemos utilizar los servicios para ayudarnos a realizar nuestro trabajo mediante la autoconexión de los mismos.

En el próximo tutorial de esta serie, profundizaremos en este concepto tan importante.

Pero antes de que terminemos este tutorial, quiero hablar de otra cosa increíble y asombrosa: Webpack Encore, la clave para escribir CSS y JavaScript de forma profesional. A lo largo de estos últimos capítulos, vamos a dar vida a nuestro sitio e incluso a hacerlo tan responsivo como una aplicación de una sola página.
