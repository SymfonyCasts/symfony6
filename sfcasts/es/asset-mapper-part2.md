# Encore -> AssetMapper Parte 2

Conseguir que funcionen los archivos CSS de terceros es una de las cosas más complicadas de hacer en AssetMapper. Importarlos así no va a funcionar.

## Instalar Bootstrap CSS

Centrémonos primero en Bootstrap. Se trata de un paquete de terceros, e instalamos paquetes de terceros diciendo `bin/console importmap:require` el nombre del paquete:

```terminal-silent
php bin/console importmap:require bootstrap
```

Bootstrap es especialmente interesante porque coge el paquete JavaScript, una dependencia del paquete JavaScript, y también se dio cuenta de que este paquete suele tener un archivo CSS... así que también lo cogió. Las tres cosas se añadieron a `importmap.php`.

[[[ code('b62603ecde') ]]]

No vamos a utilizar el JavaScript de bootstrap en este proyecto. Así que podríamos eliminarlo. Pero lo dejaré porque no hace daño a nada. La verdadera estrella, sin embargo, es este archivo CSS. Copia su ruta. Y en `app.css`, elimina la línea superior.

Puedes importar CSS de terceros con AssetMapper, pero no puedes hacerlo desde dentro de otro archivo CSS. Bueno, técnicamente puedes, pero la vida es más fácil si lo hacemos desde`app.js`. Di `import`, y luego pega.

[[[ code('456c25a78f') ]]]

Y ahora... ¡Bootstrap cobra vida!

## Añadir FontAwesome

Lo siguiente es FontAwesome. Fíjate en que estamos cogiendo un archivo CSS específico del paquete. Una gran diferencia entre Encore y AssetMapper es que si necesitas importar un archivo específico de un paquete, tienes que `importmap:require` ese archivo, no el paquete en general. Mira `bin/console importmap:require` y pega:

```terminal-silent
php bin/console importmap:require @fortawesome/fontawesome-free/css/all.css
```

Eso coge este único archivo CSS, lo descarga en el proyecto y lo añade a`importmap.php` justo aquí. Si tienes curiosidad, estos archivos se descargan en un directorio `assets/vendor/`.

Entra en `app.css`, elimina esa línea y añade otra importación para esa ruta.

[[[ code('5286c3ca27') ]]]

¡Y ya funciona! Aunque, sobre el tema de FontAwesome, ya no recomiendo usar FontAwesome así. En su lugar, utiliza kits de FontAwesome. O, mejor, renderiza un SVG en línea. Con suerte, pronto tendremos un paquete de iconos de Symfony UX para hacerlo más fácil.

## Añadir fuentes CSS

El último elemento en `app.css` es una fuente. Esto es más complicado. Si ejecutamos `importmap:require`seguido sólo del nombre del paquete -sin ruta- siempre descargará el archivo JavaScript principal del paquete. Sólo obtendrá un archivo CSS si `importmap:require` una ruta a un archivo CSS, como acabamos de hacer.

Vale, ya sé que antes ejecutamos `import:require bootstrap` y eso sí nos dio un archivo CSS. Permíteme ser más claro. Si ejecutas `importmap:require packageName`, obtendrás el JavaScript de ese paquete. En algunos casos, como Bootstrap, el paquete anuncia que tiene un archivo CSS. Cuando eso ocurre, AssetMapper lo ve y, efectivamente, ejecuta `importmap:require bootstrap/dist/css/bootstrap.min.css`automáticamente... sólo para ser útil.

De todos modos, sé que necesitamos un archivo CSS. Con Encore, si importabas un paquete desde dentro de un archivo CSS, Encore intentaba encontrar el archivo CSS en el paquete e importarlo. Esto no ocurre con AssetMapper: tenemos que averiguar cuál es la ruta al archivo CSS y, a continuación, solicitarlo.

Me gusta hacer esto en jsDelivr.com. Esta es la CDN que AssetMapper utiliza entre bastidores para obtener paquetes. Busca el paquete. Aparecerá, pero hay uno más abajo de `@fontsource-variable`. Las fuentes variables pueden ser un poco más eficientes, así que cambiemos a eso. Dentro, ¡eh! Anuncia el archivo CSS principal! Si quisieras un archivo diferente, podrías hacer clic en la pestaña Archivos y navegar hasta encontrar lo que necesitas.

Copia esta ruta hasta el nombre del paquete, luego gira y ejecuta`importmap:require` y pega. Pero no necesitamos la versión: sólo el paquete y luego la ruta:

```terminal-silent
php bin/console importmap:require @fontsource-variable/roboto-condensed/index.min.css
```

Cópiala y pulsa intro. Descarga el archivo CSS y añade una entrada a`importmap.php`. 

[[[ code('efd28cccfb') ]]]

Por último, elimina la importación de `app.css` e impórtala de `app.js`.

[[[ code('977b4890a7') ]]]

Ah, y como hemos cambiado a la fuente variable, en `app.css`, actualiza la familia de fuentes a `Roboto Condensed Variable`.

En el sitio, observa la fuente cuando actualice. Ya está Coger esos archivos CSS de terceros puede ser lo más complicado que hagas en AssetMapper.

Ah, y si utilizas Sass o Tailwind, hay bundles de Symfonycasts compatibles con ambos en AssetMapper.

## Añadir la extensión .js

Ahora que el estilo está funcionando, vamos a echar un vistazo a nuestro JavaScript. En la consola, tenemos un error: un 404 para algo llamado `bootstrap`. Eso viene de`app.js`: de esta línea de importación. Para solucionarlo, abre `app.js` y añade `.js` al final.

[[[ code('757ccea5ee') ]]]

Con Webpack Encore, estamos ejecutando dentro de un entorno Node. Y Node te permite hacer trampas: si el archivo que estás importando termina en `.js`, no necesitas incluir el `.js`. Pero en un entorno JavaScript real, como tu navegador, no puedes hacer eso: el `.js` es necesario.

Éste es probablemente el mayor cambio que tendrás que hacer al convertir.

## stimulus-bridge -> stimulus-bundle

Prueba la página ahora. ¡Siguiente error! Y es importante:

> Error al resolver el especificador de módulo `@symfony/stimulus-bridge`.

Esto significa que, en algún sitio, estamos importando este paquete... pero el paquete no existe en `importmap.php`.

Hay dos tipos de importaciones. En primer lugar, si una importación empieza por `./` o `../`, es una importación relativa. Ésas son sencillas: estás importando un archivo junto a este archivo. El segundo tipo se llama importación desnuda. Es cuando importas un paquete o un archivo dentro de un paquete. En estos casos, la cadena dentro de la importación debe existir exactamente en `importmap.php`. Si no es así, verás este error.

La fuente de nuestro error es `bootstrap.js`. ¿Ves este `@symfony/stimulus-bridge`? que no existe en `importmap.php`. La solución, normalmente, es instalarlo.

Pero en este caso, el paquete es específico de Webpack Encore y la solución está relacionada con nuestra migración. Cámbialo por `@symfony/stimulus-bundle`.

[[[ code('87879d7060') ]]]

Y he aquí: ¡esa cadena sí vive dentro de `importmap.php`! A continuación, la siguiente línea simplifica.

[[[ code('3666c1ab9c') ]]]

Pero hace lo mismo que antes: inicia la app Stimulus y carga nuestros controladores. Si inicias una nueva app Symfony, obtendrás todo esto con la receta. Pero como estamos convirtiendo, necesitamos hacer un poco más de trabajo.

## Instalar los paquetes que faltan

Actualiza ahora. Obtenemos exactamente el mismo error pero con un paquete diferente:`axios`. Ya sabes lo que pasa: en algún sitio, estamos importando esto... pero no vive en `importmap.php`. En este caso, procede de`song-controls_controller.js`.

Y esta vez, ¡la solución es instalar este paquete! Gira y ejecuta

```terminal
php bin/console importmap:require axios
```

Eso añade `axios` a `importmap.php` y ahora... ¡nuestra aplicación está viva! ¡Esto funciona con AssetMapper! Tenemos un frontend moderno y eficaz, todo ello sin sistema de compilación.

## Reducción de una dependencia

Ah, pero mira el pie de página: el texto es más oscuro que antes. Antes utilizaba bootstrap 5.1. Pero cuando instalamos `bootstrap` con AssetMapper, cogió la última 5.3. Y, al parecer, ¡algo cambió!

Podría averiguar qué ha cambiado y solucionarlo... Pero también podemos hacer un downgrade. Actualiza la versión en `importmap.php` a 5.1.3.

[[[ code('a0e54e5a0c') ]]]

Si hiciéramos eso y actualizáramos, nada cambiaría: la versión más reciente se sigue descargando en `assets/vendor/`. Para sincronizar ese directorio con `importmap.php`, ejecuta:

```terminal
php bin/console importmap:install
```

Piensa que esto es como el `composer install` del mundo de AssetMapper. Se ha dado cuenta de que hemos cambiado dos paquetes y los ha descargado. Y así de fácil, ¡hemos cruzado la línea de meta! ¡Estamos ejecutando AssetMapper!

A continuación, vamos a dedicar tres minutos a modernizar y simplificar nuestro JavaScript.
