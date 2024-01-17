# Ejemplo de Stimulus en el mundo real

Pongamos a prueba a Stimulus. Éste es nuestro objetivo: cuando hagamos clic en el icono de reproducción, haremos una petición Ajax a nuestra ruta de la API... la que está en `SongController`. Esto devuelve la URL donde se puede reproducir esta canción. Entonces usaremos eso en JavaScript para... ¡reproducir la canción!

Toma `hello_controller.js` y cámbiale el nombre a, qué tal `song-controls_controller.js`. Dentro, sólo para ver si esto funciona, en `connect()`, registra un mensaje. El método`connect()` se llama cada vez que Stimulus ve un nuevo elemento coincidente en la página.

[[[ code('e29d1ac07a') ]]]

Ahora, en la plantilla, hola ya no va a funcionar, así que quita eso. Lo que quiero hacer es rodear cada fila de canciones con este controlador.... así que es este elemento`song-list`. Después de la clase, añade `{{ stimulus_controller('song-controls') }}`.

[[[ code('bd43e92632') ]]]

Vamos a probarlo Actualiza, comprueba la consola y... ¡sí! Golpeó nuestro código seis veces! Una vez por cada uno de estos elementos. Y cada elemento recibe su propia instancia de controlador, por separado.

## Añadir acciones de Stimulus

Bien, a continuación, cuando hagamos clic en reproducir, queremos ejecutar algún código. Para ello, podemos añadir una acción. Tiene este aspecto: en la etiqueta `a`, añade `{{ stimulus_action() }}` -otra función de acceso directo- y pásale el nombre del controlador al que estás adjuntando la acción - `song-controls` - y luego un método dentro de ese controlador que debe ser llamado cuando alguien haga clic en este elemento. ¿Qué te parece `play`.

[[[ code('69caff7d11') ]]]

Genial, ¿no? De vuelta en el controlador de la canción, ya no necesitamos el método `connect()`: no tenemos que hacer nada cada vez que veamos otra fila `song-list`. Pero sí necesitamos un método `play()`.

Y al igual que con los escuchadores de eventos normales, éste recibirá un objeto `event`... y entonces podremos decir `event.preventDefault()` para que nuestro navegador no intente seguir el clic del enlace. Para probar, `console.log('Playing!')`.

[[[ code('80418b94bb') ]]]

¡Vamos a ver qué pasa! Actualiza y... haz clic. Ya funciona. Así de fácil es enganchar un oyente de eventos en Stimulus. Ah, y si inspeccionas este elemento... esa función`stimulus_action()` es sólo un atajo para añadir un atributo especial `data-action`que Stimulus entiende.

## Instalar e importar Axios

Bien, ¿cómo podemos hacer una llamada Ajax desde dentro del método `play()`? Bueno, podríamos utilizar la función integrada `fetch()` de JavaScript. Pero en su lugar, voy a instalar una biblioteca de terceros llamada Axios. En tu terminal, instálala diciendo:

```terminal
yarn add axios --dev
```

Ahora sabemos lo que hace: descarga este paquete en nuestro directorio `node_modules`, y añade esta línea a nuestro archivo `package.json`.

Ah, y nota al margen: puedes utilizar absolutamente jQuery dentro de Stimulus. No lo haré, pero funciona muy bien - y puedes instalar - e importar - jQuery como cualquier otro paquete. Hablamos de ello en nuestro tutorial de Stimulus.

Bien, ¿cómo utilizamos la biblioteca `axios`? Importándola

Al principio de este archivo, ya hemos importado la clase base `Controller` de`stimulus`. Ahora `import axios from 'axios'`. En cuanto lo hagamos, Webpack Encore cogerá el código fuente de `axios` y lo incluirá en nuestros archivos JavaScript construidos.

[[[ code('f334d5443b') ]]]

Ahora, aquí abajo, podemos decir `axios.get()` para hacer una petición GET. Pero... ¿qué debemos pasar para la URL? Tiene que ser algo como `/api/songs/5`... pero ¿cómo sabemos cuál es el "id" de esta fila?

## Valores de Stimulus

Una de las cosas más interesantes de Stimulus es que te permite pasar valores de Twig a tu controlador Stimulus. Para ello, declara qué valores quieres permitir que se pasen a través de una propiedad estática especial: `static values = {}`. Dentro, vamos a permitir que se pase un valor de `infoUrl`. Me acabo de inventar ese nombre: creo que pasaremos la URL completa a la ruta de la API. Establece esto como el tipo que será. Es decir, un `String`.

Aprenderemos cómo pasamos este valor desde Twig a nuestro controlador en un minuto. Pero como tenemos esto, abajo, podemos referenciar el valor diciendo `this.infoUrlValue`.

[[[ code('0684edc5d4') ]]]

Entonces, ¿cómo lo pasamos? De vuelta en `homepage.html.twig`, añade un segundo argumento a `stimulus_controller()`. Este es un array de los valores que quieres pasar al controlador. Pasa a `infoUrl` el conjunto de la URL.

Hmm, pero tenemos que generar esa URL. ¿Esa ruta tiene ya un nombre? No, añade `name: 'api_songs_get_one'`.

[[[ code('e20f187c7b') ]]]

Perfecto. Copia eso... y de nuevo en la plantilla, establece `infoURl` a `path()`, el nombre de la ruta... y luego una matriz con cualquier comodín. Nuestra ruta tiene un comodín`id`.

En una aplicación real, estas rutas probablemente tendrían cada una un id de base de datos que podríamos pasar. Todavía no lo tenemos... así que para, en cierto modo, falsear esto, voy a utilizar`loop.index`. Esta es una variable mágica de Twig: si estás dentro de un bucle de Twig `for`, puedes acceder al índice -como 1, 2, 3, 4- utilizando `loop.index`. Así que vamos a usar esto como una identificación falsa. Ah, y no olvides decir `id:` y luego`loop.index`.

[[[ code('f3a0755cb5') ]]]

¡Hora de probar! Refresca. Lo primero que quiero que veas es que, cuando pasamos`infoUrl` como segundo argumento a `stimulus_controller`, lo único que hace es dar salida a un atributo muy especial `data` que Stimulus sabe leer. Así es como se pasa un valor a un controlador.

Haz clic en uno de los enlaces de reproducción y... lo tienes. ¡A cada objeto controlador se le pasa su URL correcta!

## Hacer la llamada Ajax

¡Vamos a celebrarlo haciendo la llamada Ajax! Hazlo con `axios.get(this.infoUrlValue)` -sí, acabo de escribirlo-, `.then()` y una devolución de llamada utilizando una función de flecha que recibirá un argumento `response`. Esto se llamará cuando termine la llamada Ajax. Registra la respuesta para empezar. Ah, y corrige para usar `this.infoUrlValue`.

[[[ code('de15183eff') ]]]

Muy bien, actualiza... ¡y haz clic en el enlace de reproducción! ¡Sí! Ha volcado la respuesta... y una de sus claves es `data`... ¡que contiene el `url`!

¡Es hora de dar la vuelta de la victoria! De vuelta a la función, podemos reproducir ese audio creando un nuevo objeto `Audio` -es un objeto JavaScript normal-, pasándole`response.data.url`... y llamando a continuación a `play()`.

[[[ code('a92c517158') ]]]

Y ahora... cuando le demos al play... ¡por fin! Música para mis oídos.

Si quieres aprender más sobre Stimulus - esto ha sido un poco rápido - tenemos un tutorial entero sobre ello... y es genial.

Para terminar este tutorial, vamos a instalar otra biblioteca de JavaScript. Ésta hará que nuestra aplicación se sienta instantáneamente como una aplicación de una sola página. Eso a continuación.