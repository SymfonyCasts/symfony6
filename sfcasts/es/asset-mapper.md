# Migrando Encore -> AssetMapper

Symfony 6.3 vino con un nuevo componente llamado AssetMapper... ¡y me encanta! Vale, trabajo en él... así que no soy en absoluto objetiva... ¡pero créeme, es increíble! Nos permite escribir JavaScript y css modernos sin sistema de compilación. Tenemos un [tutorial de Asset Mapper](https://symfonycasts.com/screencast/asset-mapper) y otro más reciente [tutorial de LAST Stack](https://symfonycasts.com/screencast/last-stack) donde construimos cosas chulas con él.

## ¿AssetMapper vs Webpack Encore?

AssetMapper es un sustituto de Webpack Encore. Encore no va a morir súper pronto, ¡pero definitivamente lo he pillado hojeando algunos folletos de jubilación!

Así que sé lo que te estás preguntando

> ¿Debería convertir mi aplicación de Webpack Encore a AssetMapper?

La respuesta corta, pero no satisfactoria, es... depende de ti. AssetMapper es más moderno, más fácil de usar y, si estás frustrado por la lentitud de Encore, es una buena razón para cambiar. Pero si Encore funciona bien, no hay ninguna razón de peso para hacer todo el trabajo de conversión a AssetMapper. Además, si utilizas React o Vue, querrás quedarte con Encore, ya que siguen necesitando un paso de compilación.

## Eliminar Webpack Encore

Pero, ¡vamos a convertirlo! Ve a tu terminal y busca la pestaña en la que `yarn watch`está haciendo de las suyas. Detenlo con Ctrl+C y cierra esa pestaña. No necesitamos un sistema de compilación, así que esa segunda pestaña no volverá.

A continuación, Ejecuta:

```terminal
composer remove symfony/webpack-encore-bundle
```

Esto eliminará ese paquete... pero lo que es más importante: ¡su receta se desinstalará sola! La sensación es genial: `package.json` se ha ido, `webpack.config.js` se ha ido, las funciones de`encore_entry_` en `base.html.twig` se han ido.

Pero... también ha eliminado `app.js` y `app.css`. Queremos esos archivos, así que ejecuta

```terminal
git checkout assets/
```

para recuperarlos. ¡Pero todo lo demás tiene buena pinta! Ejecuta:

```terminal
git diff
```

En el antiguo `package.json`, las dependencias de aquí estaban relacionadas con Webpack Encore y no las necesitaremos. Pero algunas de ellas son para nuestro frontend, y las volveremos a añadir a través de AssetMapper.

Bien, bloquea esos cambios con un commit... y luego monta una fiesta eliminando`node_modules`, `public/build/` y el archivo de error de yarn. Oh, también podemos eliminar`yarn.lock`. ¡Estupendo!

## Instalar AssetMapper

Ahora vamos a instalar AssetMapper:

```terminal
composer require symfony/asset-mapper
```

Su receta hace un montón de cosas interesantes. No profundizaremos demasiado en cómo funciona AssetMapper -para eso tenemos otros tutoriales-, pero exploremos. En `.gitignore`:

[[[ code('93c93b545b') ]]]

ignora la ubicación final de los activos construidos y dónde viven los archivos del proveedor. Y en `templates/base.html.twig`, añade una función `importmap()`que dará salida a CSS y JavaScript.

[[[ code('b0b941ddae') ]]]

También nos dio un archivo `importmap.php`. 

[[[ code('668b1573ff') ]]]

Éste es, efectivamente, el nuevo `package.json`: el hogar de los paquetes de terceros. ¡Y oye! ¡Ya ha añadido Stimulus y Turbo! Son dos de los paquetes de`package.json` que necesitamos.

¿Funcionará? ¿Refrescar y... más o menos? No tenemos Bootstrap CSS... y por eso tiene un aspecto horrible. Pero puedo ver que se está cargando `assets/styles/app.css`: eso nos está dando algunos estilos básicos. Pero tenemos que arreglar estas importaciones.

[[[ code('0d828a4a27') ]]]

¡Vamos allá! Vamos a arremangarnos y a concretar los últimos pasos para poner en marcha AssetMapper a continuación.
