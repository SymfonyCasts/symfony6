# Configuración de Webpack Encore

Nuestra configuración de CSS está bien. Ponemos los archivos en el directorio `public/` y luego... apuntamos a ellos desde dentro de nuestras plantillas. Podríamos añadir archivos de JavaScript de la misma manera.

Pero si queremos tomarnos realmente en serio la escritura de CSS y JavaScript, tenemos que llevar esto al siguiente nivel. E incluso si te consideras un desarrollador principalmente de backend, las herramientas de las que vamos a hablar te permitirán escribir CSS y JavaScript de forma más fácil y menos propensa a errores que a lo que probablemente estés acostumbrado.

La clave para llevar nuestra configuración al siguiente nivel es aprovechar una biblioteca de nodos llamada Webpack. Webpack es la herramienta estándar de la industria para empaquetar, minificar y analizar tu CSS, JavaScript y otros archivos del frontend. Pero no te preocupes: Node es sólo JavaScript. Y su papel en nuestra aplicación será bastante limitado.

Configurar Webpack puede ser complicado. Por eso, en el mundo Symfony, utilizamos una herramienta ligera llamada Webpack Encore. Sigue siendo Webpack... ¡sólo lo hace más fácil! Y tenemos un tutorial gratuito sobre ello si quieres profundizar.

## Instalar Encore

Pero vamos a hacer un curso intensivo ahora mismo. Primero, en tu línea de comandos, asegúrate de que tienes instalado Node:

```terminal
node -v
```

También necesitarás `npm` -que viene con Node automáticamente- o `yarn`:

```terminal-silent
yarn --version
```

Npm y yarn son gestores de paquetes de Node: son el Compositor para el mundo de Node... y puedes usar cualquiera de los dos. Si decides usar yarn - que es lo que yo usaré - asegúrate de instalar la versión 1.

Estamos a punto de instalar un nuevo paquete... así que vamos a confirmar todo:

```terminal
git add .
```

Y... se ve bien:

```terminal-silent
git status
```

Así que confirma todo:

```terminal-silent
git commit -m "Look mom! A real app"
```

Para instalar Encore, ejecuta:

```terminal
composer require encore
```

Esto instala WebpackEncoreBundle. Recuerda que un bundle es un plugin de Symfony. Y este paquete tiene una receta: una receta muy importante. Ejecuta:

```terminal
git status
```

## La receta de Encore

Por primera vez, la receta ha modificado el archivo `.gitignore`. Vamos a comprobarlo. Abre `.gitignore`. Lo de arriba es lo que teníamos originalmente... y lo de abajo es lo nuevo que ha añadido WebpackEncoreBundle. Está ignorando el directorio`node_modules/`, que es básicamente el directorio `vendor/` para Node. No necesitamos confirmarlo porque esas bibliotecas de proveedores se describen en otro archivo nuevo de la receta: `package.json`. Este es el archivo `composer.json`de Node: describe los paquetes de Node que necesita nuestra aplicación. El más importante es el propio Webpack Encore, que es una biblioteca de Node. También tiene algunos otros paquetes que nos ayudarán a realizar nuestro trabajo.

La receta también ha añadido un directorio `assets/`... y un archivo de configuración para controlar Webpack: `webpack.config.js`. El directorio `assets/` ya contiene un pequeño conjunto de archivos para que podamos empezar.

## Instalar las dependencias de Node

Bien, con Composer, si no tuviéramos este directorio `vendor/`, podríamos ejecutar`composer install` que le diría que leyera el archivo `composer.json` y volviera a descargar todos los paquetes en `vendor/`. Lo mismo ocurre con Node: tenemos un archivo `package.json`. Para descargarlo, ejecuta

```terminal
yarn install
```

O:

```terminal
npm install
```

¡Go node go! Esto tardará unos instantes mientras se descarga todo. Probablemente recibirás algunas advertencias como ésta, que puedes ignorar.

¡Genial! Esto hizo dos cosas. En primer lugar, descargó un montón de archivos en el directorio`node_modules/`: el directorio de "proveedores" de Node. También creó un archivo`yarn.lock`... o `package-lock.json` si estás usando npm. Esto sirve para el mismo propósito de `composer.lock`: almacena las versiones exactas de todos los paquetes para que obtengas las mismas versiones la próxima vez que instales tus dependencias.

En su mayor parte, no necesitas preocuparte por estos archivos de bloqueo... excepto que debes confirmarlos. Hagámoslo. Ejecuta:

```terminal
git status
```

Entonces:

```terminal
git add .
```

Hermoso:

```terminal-silent
git status
```

Y confirma:

```terminal-silent
git commit -m "Adding Webpack Encore"
```

¡Hey! ¡Ya está instalado Webpack Encore! Pero... ¡todavía no hace nada! Aprovechado. A continuación, vamos a utilizarlo para llevar nuestro JavaScript al siguiente nivel.