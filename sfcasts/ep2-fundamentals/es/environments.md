# Entornos

Nuestra aplicación es como una máquina: es un conjunto de servicios y clases de PHP que hacen su trabajo... y que, en última instancia, renderizan algunas páginas. Pero podemos hacer que nuestra máquina funcione de forma diferente alimentándola con una configuración distinta.

Por ejemplo, en `SongController`, estamos utilizando el servicio `$logger` para registrar cierta información:

[[[ code('ec32177521') ]]]

Si alimentamos al registrador con una configuración que diga "registrar todo", lo registrará todo, incluidos los mensajes de depuración de bajo nivel. Pero si cambiamos la configuración para que diga "sólo registrar errores", entonces sólo registrará los errores. En otras palabras, la misma máquina puede comportarse de forma diferente en función de nuestra configuración. Y a veces, como en el caso del registro, podemos necesitar que esa configuración sea diferente mientras estamos desarrollando localmente y en producción.

Para manejar esto, Symfony tiene un concepto importante llamado "entornos". No me refiero a entornos como local vs staging vs beta vs producción. Un entorno Symfony es un conjunto de configuraciones.

Por ejemplo, puedes ejecutar tu código en el entorno `dev` con un conjunto de configuración diseñado para el desarrollo. O puedes ejecutar tu aplicación en el entorno `prod`con un conjunto de configuraciones optimizadas para producción. ¡Deja que te lo enseñe!

## La variable APP_ENV

En la raíz de nuestro proyecto, tenemos un archivo `.env`:

[[[ code('40c34e875b') ]]]

Más adelante hablaremos de este archivo. Pero ¿ves este `APP_ENV=dev`? 
Esto le dice a Symfony que el entorno actual es `dev`, que es perfecto para el desarrollo local. Cuando despleguemos a producción, cambiaremos esto a `prod`. 
Más sobre esto en unos minutos.

Pero... ¿qué diferencia hay? ¿Qué ocurre en nuestra aplicación cuando cambiamos esto de `dev` a `prod`? Para responder, déjame cerrar algunas carpetas... y abrir`public/index.php`:

[[[ code('7e152d62fa') ]]]

Recuerda: este es nuestro controlador frontal. Es el primer archivo que se ejecuta en cada petición. Realmente no nos importa mucho este archivo, pero su función es importante: arranca Symfony.

Lo interesante es que lee el valor de `APP_ENV` y lo pasa como primer argumento a esta clase `Kernel`. Y... ¡esta clase `Kernel` está realmente en nuestro código! Vive en `src/Kernel.php`.

Genial. Así que lo que quiero saber ahora es ¿Qué controla el primer argumento de `Kernel`?

Si abrimos la clase, no encontramos... absolutamente nada. Está vacía. Eso es porque la mayor parte de la lógica vive en este rasgo. Mantén pulsado "cmd" o "control" y haz clic en `MicroKernelTrait` para abrirlo.

## El directorio config/packages/{ENV} Directorio

El trabajo de `Kernel` es cargar todos los servicios y rutas de nuestra aplicación. Si te desplazas hacia abajo, tiene un método llamado `configureContainer()`. ¡Ooh! ¡Ahora sabemos qué es el contenedor! ¡Y mira lo que hace! Toma este objeto `$container`e importa `$configDir.'/{packages}/*.{php,yaml}'`. Esta línea dice

> ¡Oye, contenedor! Quiero cargar todos los archivos del directorio `config/packages/`.

Carga todos esos archivos, y luego pasa la configuración de cada uno a cualquier bundle que esté definido como clave raíz. Pero lo realmente interesante para los entornos es la siguiente línea `import`
`$configDir.'/{packages}/'.$this->environment.'/*.{php,yaml}'`. ¡Si escarbas un poco, aprenderás que `$this->environment` es igual al primer argumento que se pasa a `Kernel`!

En otras palabras, en el entorno `dev`, éste será `dev`. Así que, además de los archivos de configuración principales, esto también cargará todo lo que haya en el directorio`config/packages/dev/`. Sí, podemos añadir allí una configuración extra que anule la configuración principal en el entorno `dev`. Por ejemplo, podemos añadir una configuración de registro que le diga al registrador que lo registre todo

Debajo de esto, también cargamos un archivo llamado `services.yaml` y, si lo tenemos,`services_dev.yaml`. Pronto hablaremos más sobre `services.yaml`.

## El when@{ENV} Config

Así que, si quieres añadir una configuración específica del entorno, puedes ponerla en el directorio del entorno correcto. Pero hay otra forma. Es una característica bastante nueva y la vimos en la parte inferior de `twig.yaml`. Es la sintaxis `when@`:

[[[ code('c2bc8660e5') ]]]

En Symfony, por defecto, hay tres entornos: `dev` `prod` , y luego, si ejecutas pruebas automatizadas, hay un entorno llamado `test`. Dentro de `twig.yaml`, al decir, `when@test`, significa que esta configuración sólo se cargará si el entorno es igual a `test`.

El mejor ejemplo de esto podría estar en `monolog.yaml`. `monolog` es el bundle que controla el servicio de registro. Tiene una configuración que se utiliza en todos los entornos. Pero, por debajo de éste, tiene `when@dev`. No hablaremos demasiado de la configuración específica de `monolog`, pero esto controla cómo se manejan los mensajes de registro. En el entorno `dev`, esto dice que se debe registrar todo y que se debe registrar en un archivo, utilizando esta sintaxis extravagante `%kernel.logs_dir%` de la que aprenderemos pronto.

En cualquier caso, esto apunta a un archivo `var/logs/dev.log` y la parte `level: debug` significa que registrará todos los mensajes en `dev.log`... independientemente de lo importante o no que sea ese mensaje.

Por debajo de esto, para el entorno `prod`, es bastante diferente. La línea más importante es `action_level: error`. Que dice:

> ¡Hola Sra. Logger! Esta aplicación probablemente registra una tonelada de mensajes, pero sólo quiero que
> guarde realmente los mensajes que tengan un nivel de importancia `error` o superior.

¡Eso tiene sentido! En producción, no queremos que nuestros archivos de registro se llenen de toneladas y toneladas de mensajes de depuración. Con esto, sólo registramos los mensajes de error.

El gran punto es éste: utilizando estos trucos, podemos configurar nuestros servicios de forma diferente en función del entorno.

## Enrutamiento específico del entorno

Incluso podemos hacer lo mismo con las rutas A veces tienes rutas enteras que sólo quieres cargar en un entorno determinado. Volviendo a `MicroKernelTrait`, si bajas, hay un método llamado `configureRoutes()`. Este es el responsable de cargar todas nuestras rutas... y es muy similar al otro código. Carga `$configDir.'/{routes}/*.{php,yaml}'` así como este directorio de entorno `dev`, si tienes uno. Nosotros no lo tenemos.

También puedes utilizar el truco de `when@dev`. Este archivo se encarga de registrar las rutas que utiliza la barra de herramientas de depuración web. No queremos que la barra de herramientas de depuración web esté en producción... así que estas rutas sólo se importan en el entorno `dev`.

[[[ code('c32be9baf6') ]]]

Diablos, ¡algunos bundles sólo están habilitados en algunos entornos! Si abres`config/bundles.php`, tenemos el nombre del bundle... y luego, a la derecha, los entornos en los que ese bundle debe estar habilitado. Este `all` significa todos los entornos.... y la mayoría están habilitados en todos los entornos.

Sin embargo, el `WebProfilerBundle` -el bundle que nos proporciona la barra de herramientas de depuración web y el perfilador- sólo se carga en los entornos `dev` y `test`. Sí, todo el bundle -y los servicios que proporciona- nunca se cargan en el entorno `prod`.

Así que, ahora que entendemos los fundamentos de los entornos, vamos a ver si podemos cambiar nuestra aplicación al entorno `prod`. Y luego, como reto, configuraremos nuestro servicio de caché de forma diferente en `dev`. Eso a continuación.
