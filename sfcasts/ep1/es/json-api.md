# Ruta de la API JSON

En un futuro tutorial, vamos a crear una base de datos para gestionar las canciones, los géneros y los discos de vinilo mezclados que nuestros usuarios están creando. Ahora mismo, estamos trabajando completamente con datos codificados... pero nuestros controladores -y- especialmente las plantillas no serán muy diferentes una vez que hagamos todo esto dinámico.

Así que este es nuestro nuevo objetivo: quiero crear una ruta de la API que devuelva los datos de una sola canción como JSON. Vamos a usar esto en unos minutos para dar vida a este botón de reproducción. Por el momento, ninguno de estos botones hace nada, pero tienen un aspecto bonito.

 Crear el controlador JSON

Los dos pasos para crear un punto final de la API son... exactamente los mismos que para crear una página HTML: necesitamos una ruta y un controlador. Como esta ruta de la API devolverá datos de canciones, en lugar de añadir otro método dentro de `VinylController`, vamos a crear una clase de controlador totalmente nueva. La forma en que organices este material depende enteramente de ti.

Crea una nueva clase PHP llamada `SongController`... o `SongApiController` también sería un buen nombre. En su interior, ésta comenzará como cualquier otro controlador, extendiendo`AbstractController`. Recuerda: esto es opcional... pero nos proporciona métodos de acceso directo sin inconvenientes.

A continuación, crea un `public function` llamado, qué tal, `getSong()`. Añade la ruta... y pulsa el tabulador para autocompletar esto de forma que PhpStorm añada la declaración de uso en la parte superior. Establece la URL como `/api/songs/{id}`, donde `id` será finalmente el id de la base de datos de la canción.

Y como tenemos un comodín en la ruta, se nos permite tener un argumento `$id`. Por último, aunque no necesitamos hacerlo, como sabemos que nuestro controlador devolverá un objeto `Response`, podemos establecerlo como tipo de retorno. Asegúrate de autocompletar el del componente `HttpFoundation` de Symfony.

Dentro del método, para empezar, `dd($id)`... sólo para ver si todo funciona.

[[[ code('ad23fa7738') ]]]

¡Vamos a hacerlo! Dirígete a `/api/songs/5` y... ¡lo tienes! Otra página nueva.

De vuelta a ese controlador, voy a pegar algunos datos de la canción: finalmente, esto vendrá de la base de datos. Puedes copiarlo del bloque de código de esta página. Nuestro trabajo es devolverlo como JSON.

Entonces, ¿cómo devolvemos JSON en Symfony? Devolviendo un nuevo `JsonResponse` y pasándole los datos.

[[[ code('3450058bee') ]]]

Lo sé... ¡demasiado fácil! Refresca y... ¡hola JSON! Ahora puedes estar pensando:

> ¡Ryan! Nos has estado diciendo -repetidamente- que un controlador debe
> devolver siempre un objeto Symfony `Response`, que es lo que devuelve `render()`.
> ¿Ahora devuelve otro tipo de objeto `Response`?

Vale, es justo... pero esto funciona porque `JsonResponse` es una Respuesta. Me explico: a veces es útil saltar a las clases principales para ver cómo funcionan. Para ello, en PHPStorm -si estás en un Mac mantén pulsado comando, si no, mantén pulsado control- y luego haz clic en el nombre de la clase a la que quieras saltar. Y... ¡sorpresa! `JsonResponse`
extiende `Response`. Sí, seguimos devolviendo un `Response`. Pero esta subclase está bien porque codifica automáticamente JSON nuestros datos y establece la cabecera`Content-Type` en `application/json`.

## El método abreviado ->json()

Ah, y de vuelta a nuestro controlador, podemos ser aún más perezosos diciendo`return $this->json($song)`... donde `json()` es otro método abreviado que viene de `AbstractController`.

[[[ code('6499fe8656') ]]]

Hacer esto no supone ninguna diferencia, porque sólo es un atajo para devolver ... ¡un `JsonResponse`!

Si estás construyendo una API seria, Symfony tiene un componente`serializer` que es realmente bueno para convertir objetos en JSON... y luego JSON de nuevo en objetos. Hablamos mucho de él en nuestro tutorial de la Plataforma API, que es una potente biblioteca para crear APIs en Symfony.

A continuación, vamos a aprender cómo hacer que nuestras rutas sean más inteligentes, por ejemplo, haciendo que un comodín sólo coincida con un número, en lugar de coincidir con cualquier cosa.
