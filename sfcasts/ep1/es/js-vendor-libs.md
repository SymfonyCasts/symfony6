# Instalación de código de terceros en nuestro JS/CSS

Ahora tenemos un nuevo y bonito sistema de JavaScript y CSS que vive completamente dentro del directorio`assets/`. Vamos a trasladar nuestros estilos públicos a éste. Abre`public/styles/app.css`, copia todo esto, borra todo el directorio... y pégalo en el nuevo `app.css`. Gracias a `encore_entry_link_tags()` en`base.html.twig`, el nuevo CSS se está incluyendo... y ya no necesitamos la antigua etiqueta`link`.

Ve a comprobarlo. Refresca y... ¡todavía se ve muy bien!

## Instalación de bibliotecas JavaScript/CSS de terceros

Vuelve a `base.html.twig`. ¿Qué pasa con estas etiquetas de enlace externo para bootstrap y FontAwesome? Bueno, puedes mantener totalmente estos enlaces CDN. Pero también podemos procesar estas cosas a través de Encore. ¿Cómo? Instalando Bootstrap y FontAwesome como bibliotecas de proveedor e importándolas.

Elimina todas estas etiquetas de enlace... y luego actualiza. ¡Vaya! Vuelve a parecer que he diseñado este sitio. Vamos... primero a volver a añadir bootstrap. Busca tu terminal. Ya que el comando watch se está ejecutando, abre una nueva pestaña de terminal y ejecútalo:

```terminal
yarn add bootstrap --dev
```

Esto hace tres cosas. Primero, añade `bootstrap` a nuestro archivo `package.json`. Segundo, descarga bootstrap en nuestro directorio `node_modules/`... lo encontrarías aquí abajo. Y tercero, actualiza el archivo `yarn.lock` con la versión exacta de bootstrap que acaba de descargar.

Si nos detuviéramos ahora... ¡esto no supondría ninguna diferencia! Hemos descargado bootstrap -yay- pero no lo estamos utilizando.

Para usarlo, tenemos que importarlo. Entra en `app.css`. Al igual que en los archivos JavaScript, podemos importar desde dentro de los archivos CSS diciendo `@import` y luego el archivo. Podemos hacer referencia a un archivo en el mismo directorio con `./other-file.css`. O, si quieres importar algo del directorio `node_modules/` en CSS, hay un truco: un `~` y luego el nombre del paquete: `bootstrap`.

[[[ code('43abc8b3a6') ]]]

Eso es todo En cuanto hicimos eso, la función de vigilancia de Encore reconstruyó nuestro archivo `app.css`... ¡que ahora incluye Bootstrap! Observa: actualiza la página y... ¡volvemos a estar de vuelta! ¡Qué bien!

Las otras dos cosas que nos faltan son `FontAwesome` y una fuente específica. Para añadirlas, vuelve al terminal y ejecútalas:

```terminal
yarn add @fontsource/roboto-condensed --dev
```

Revelación completa: hice algunas búsquedas antes de grabar para saber los nombres de todos los paquetes que necesitamos. Puedes buscar los paquetes en https://npmjs.com.

Añadamos también el último que necesitamos:

```terminal
yarn add @fortawesome/fontawesome-free --dev
```

De nuevo, esto descargó las dos bibliotecas en nuestro proyecto... pero no las utiliza automáticamente todavía. Como esas bibliotecas contienen archivos CSS, vuelve a nuestro archivo`app.css` e impórtalos: `@import '~'` y luego `@fortawesome/fontawesome-free`. Y `@import '~@fontsource/roboto-condensed'`.

[[[ code('c72cdebd5f') ]]]

El primer paquete debería arreglar este icono... y el segundo debería hacer que la fuente cambie en toda la página. Observa el tipo de letra cuando refrescamos... ¡ha cambiado! Pero... los iconos siguen estando algo rotos.

## Importar archivos específicos de node_modules/

Para ser totalmente honesto, no estoy seguro de por qué esto no funciona fuera de la caja. Pero la solución es bastante interesante. Mantén pulsado command en un Mac -o ctrl en caso contrario- y haz clic en esta cadena `fontawesome-free`.

Cuando usas esta sintaxis, va a tu directorio `node_modules/`, a`@fortawesome/fontawesome-free`... y entonces, si no pones ningún nombre de archivo después de esto, hay un mecanismo en el que esta biblioteca le dice a Webpack qué archivo CSS debe importar. Por defecto, importa este archivo `fontawesome.css`. Por alguna razón... eso no funciona. Lo que queremos es este `all.css`.

Y podemos importarlo añadiendo la ruta: `/css/all.css`. No necesitamos el archivo minificado porque Encore se encarga de minificar por nosotros.

[[[ code('0e00a5819a') ]]]

Y ahora... ¡estamos de vuelta!

La principal razón por la que me encanta Webpack Encore y este sistema es que nos permite utilizar importaciones adecuadas. Incluso podemos organizar nuestro JavaScript en pequeños archivos -poniendo clases o funciones en cada uno- y luego importarlos cuando los necesitemos. Ya no son necesarias las variables globales.

Webpack también nos permite utilizar cosas más serias como React o Vue: incluso puedes ver, en `webpack.config.js`, los métodos para activarlos.

Pero, por lo general, me gusta utilizar una encantadora biblioteca de JavaScript llamada Stimulus. Y quiero hablarte de ella a continuación.