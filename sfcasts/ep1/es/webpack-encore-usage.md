# Empaquetar JS y CSS con Encore

Cuando instalamos Webpack Encore, su receta nos dio este nuevo directorio `assets/`. Mira el archivo `app.js`. Es interesante. Observa cómo importa este archivo `bootstrap`. En realidad es `bootstrap.js`: este archivo de aquí. La extensión `.js` es opcional.

## Importaciones de JavaScript

Esta es una de las cosas más importantes que nos da Webpack: la capacidad de importar un archivo JavaScript de otro. Podemos importar funciones, objetos... realmente cualquier cosa desde otro archivo. Vamos a hablar más sobre este archivo`bootstrap.js` dentro de un rato.

Esto también importa un archivo CSS? Si no has visto esto antes, puede parecer... raro: ¿JavaScript importando CSS?

Para ver cómo funciona todo esto, en `app.js`, añade un `console.log()`.

[[[ code('37b4bd7997') ]]]

Y `app.css` ya tiene un fondo de cuerpo... pero añade un `!important` para que podamos ver definitivamente si se está cargando.

[[[ code('28ba4bdd4d') ]]]

Vale... ¿entonces quién lee estos archivos? Porque... no viven en el directorio `public/`... así que no podemos crear etiquetas `script` o `link` que apunten directamente a ellos.

## webpack.config.js

Para responder a esto, abre `webpack.config.js`. Webpack Encore es un binario ejecutable: vamos a ejecutarlo en un minuto. Cuando lo hagamos, cargará este archivo para obtener su configuración.

Y aunque hay un montón de funciones dentro de Webpack, lo único en lo que tenemos que centrarnos ahora es en esta: `addEntry()`. Este `app` puede ser cualquier cosa... como `dinosaur`, no importa. Te mostraré cómo se utiliza en un minuto. Lo importante es que apunta al archivo `assets/app.js`. Por ello,`app.js` será el primer y único archivo que Webpack analizará.

Esto es bastante bueno: Webpack leerá el archivo `app.js` y luego seguirá todas las declaraciones de`import` recursivamente hasta que finalmente tenga una colección gigante de todo el JavaScript y el CSS que nuestra aplicación necesita. Entonces, lo escribirá en el directorio `public/`.

## Ejecutando Webpack Encore

Vamos a verlo en acción. Busca tu terminal y ejecuta:

```terminal
yarn watch
```

Esto es, como dice, un atajo para ejecutar `encore dev --watch`. Si miras tu archivo `package.json`, viene con una sección `script` con algunos atajos.

En cualquier caso, `yarn watch` hace dos cosas. En primer lugar, crea un nuevo directorio `public/build/`y, dentro, los archivos `app.css` y `app.js` Pero no dejes que los nombres te engañen: `app.js` contiene mucho más que lo que hay dentro de `assets/app.js`: contiene todo el JavaScript de todas las importaciones que encuentra. `app.css`
contiene todo el CSS de todas las importaciones.

La razón por la que estos archivos se llaman `app.css` y `app.js` es por el nombre de la entrada.

Así que la conclusión es que, gracias a Encore, de repente tenemos nuevos archivos en el directorio`public/build/` que contienen todo el JavaScript y el CSS que necesita nuestra aplicación

## Las funciones Twig de Encore

Y si te diriges a tu página de inicio y la actualizas... ¡woh! Ha funcionado al instante!? El fondo ha cambiado... y en mi inspector... ¡está el registro de la consola! ¿Cómo diablos ha ocurrido eso?

Abre tu diseño base: `templates/base.html.twig`. El secreto está en las funciones`encore_entry_link_tags()` y `encore_entry_script_tags()`. Apuesto a que puedes adivinar lo que hacen: añadir la etiqueta `link` a `build/app.css` y la etiqueta `script`a `build/app.js`.

Puedes ver esto en tu navegador. Mira la fuente de la página y... ¡sí! La etiqueta link para `/build/app.css`... y la etiqueta `script` para `/build/app.js`. Ah, pero también ha renderizado otras dos etiquetas `script`. Eso es porque Webpack es muy inteligente. Por motivos de rendimiento, en lugar de volcar un gigantesco archivo `app.js`, a veces Webpack lo divide en varios archivos más pequeños. Afortunadamente, estas funciones Twig de Encore son lo suficientemente inteligentes como para manejar eso: incluirá todas las etiquetas de enlace o de script necesarias.

Lo más importante es que el código que tenemos en nuestro archivo `assets/app.js`-incluyendo todo lo que importa- ¡ahora funciona y aparece en nuestra página!

## Vigilancia de los cambios

Ah, y como hemos ejecutado `yarn watch`, Encore sigue funcionando en segundo plano en busca de cambios. Compruébalo: entra en `app.css`... y cambia el color de fondo. Guarda, pasa y actualiza 

[[[ code('9947a18198') ]]]

¡Se actualiza instantáneamente! Eso es porque Encore se ha dado cuenta del cambio y ha recompilado el archivo construido muy rápidamente.

A continuación: vamos a trasladar nuestro CSS existente al nuevo sistema y a aprender cómo podemos instalar e importar bibliotecas de terceros -mira Bootstrap o FontAwesome- directamente en nuestra configuración de Encore.
