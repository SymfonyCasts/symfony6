# Variables de entorno

Abrir `config/packages/framework.yaml`. No necesitamos estar autentificados para utilizar esta parte de la API de GitHub dedicada al contenido en bruto del usuario:

[[[ code('d0e2649747') ]]]

Pero si accedemos mucho a esta ruta, podríamos llegar a su límite de velocidad, que es bastante bajo para los usuarios anónimos. Así que vamos a autenticar nuestra petición.

## Añadir una cabecera de autorización a la petición HTTP

En primer lugar, si estás codificando conmigo, dirígete a "github.com" y crea tu propio token de acceso personal. Una vez hecho esto, abre `MixRepository` y baja hasta donde hacemos la petición HTTP. Para adjuntar el token de acceso a la petición pasa un tercer argumento, que es un array. Dentro, añade una clave `headers` fijada en otro array, con una cabecera `Authorization` asignada a la palabra `Token` y luego el token de acceso. Empieza utilizando un token falso:

[[[ code('9cb7084ddc') ]]]

Puedes saber que esto funciona porque, cuando volvemos a la página y la refrescamos... ¡estalla! Nuestra llamada a la API ahora falla con un 404 porque reconoce que estamos intentando autenticarnos con un token... pero el que hemos pasado es falso.

Ahora añade tu token real. Inténtalo de nuevo y... ¡funciona!

## Moviendo el encabezado de autorización a framework.yaml

¡Así que esto es genial! Pero sería mejor si el servicio viniera preconfigurado para establecer automáticamente esta cabecera de autorización... especialmente si queremos utilizar este servicio de Cliente HTTP en varios sitios. ¿Podemos hacerlo? Por supuesto

Copia la línea `Token`, entra en `framework.yaml`, y después de `base_uri`, pasa una clave `headers` con `Authorization` ajustada a nuestra cadena larga. En realidad, déjame poner un token falso ahí temporalmente:

[[[ code('63d8ced9e1') ]]]

En `MixRepository`, elimina ese tercer argumento:

[[[ code('614d5f78d4') ]]]

Y ahora, cuando probemos esto... ¡genial! Las cosas se rompen, lo que demuestra que estamos enviando esa cabecera... sólo que con el valor equivocado. Si cambiamos a nuestro token real... una vez más... ¡funciona! ¡Genial!

## Hola Variables de Entorno

Hasta ahora, esto es sólo una bonita característica del HttpClient. Pero esto también ayuda a poner de manifiesto un problema común. No es... genial tener nuestro sensible token de la API de GitHub codificado en este archivo. Es decir, este archivo va a ser enviado a nuestro repositorio. Quiero a mis compañeros de equipo... pero no los quiero tanto como para compartir mi token de acceso con ellos... o el token de acceso de nuestra empresa.

Aquí es donde las variables de entorno resultan útiles. Si no estás familiarizado con las variables de entorno, son variables que puedes establecer en cualquier sistema (Windows, Linux, lo que sea)... y luego puedes leerlas desde dentro de PHP. Muchas plataformas de alojamiento hacen que sea súper fácil establecerlas. ¿Cómo nos ayuda eso? Porque, en teoría, podríamos establecer nuestro token de acceso como una variable de entorno y luego simplemente leerlo en PHP. Eso nos permitiría evitar poner ese valor sensible dentro de nuestro código.

## Lectura de variables de entorno

Pero, antes de hablar de establecer variables de entorno, ¿cómo leemos las variables de entorno en Symfony? Copia tu token de acceso para no perderlo, pon comillas simples alrededor de `Token`, y luego vamos a utilizar una sintaxis muy especial para leer una variable de entorno. En realidad va a parecer un parámetro. Empieza y termina con `%`, y dentro, di `env()` con el nombre de la variable de entorno. ¿Qué te parece `GITHUB_TOKEN`. Me acabo de inventar ese nombre:

[[[ code('812eee7285') ]]]

Si volvemos atrás y refrescamos... ahora estamos leyendo esa variable de entorno `GITHUB_TOKEN`... pero aún no la hemos configurado, por lo que obtenemos este error "Variable de entorno no encontrada".

## Configurar las variables de entorno y el .env

En el mundo real, configurar las variables de entorno es... en realidad algo complicado. Es diferente en Windows que en Linux. Y aunque muchas plataformas de alojamiento hacen que sea súper fácil configurar las variables de entorno, no es muy sencillo hacerlo localmente en tu ordenador.

Por eso existe este archivo `.env`. Muy sencillo, cuando Symfony arranca, lee el archivo `.env` y convierte todo esto en variables de entorno. Esto significa que podemos decir `GITHUB_TOKEN=` y pegar nuestro token... y ahora... ¡funciona!

[[[ code('6de74e6cd7') ]]]

Por cierto, si hubiera una variable de entorno real `GITHUB_TOKEN` en mi sistema, esa variable de entorno real ganaría a lo que tenemos en este archivo.

## El archivo .env.local

Vale... esto es genial... ¡pero seguimos teniendo el mismo problema! Tenemos un valor sensible que está dentro de un archivo... que está comprometido en nuestro repositorio.

Bien, entonces, vamos a intentar otra cosa. Copia el token de GitHub, elimina el valor de este archivo y crea un nuevo archivo llamado `.env.local`. Establece la variable de entorno aquí.

Y ahora... ¡las cosas siguen funcionando!

Esto es lo que pasa. Cuando Symfony arranca, primero lee el archivo `.env` y convierte todo esto en variables de entorno. Luego lee `.env.local` y convierte todo lo que hay aquí en variables de entorno... que anulan cualquier valor establecido en `.env`.

El resultado es que tu archivo `.env` está destinado a contener valores seguros, por defecto, que están bien para ser consignados en tu repositorio. Entonces, localmente (y quizás también en producción, dependiendo de cómo se despliegue), creas un archivo `.env.local` y pones allí los valores sensibles. La clave es que `.env.local` es ignorado por Git. Puedes ver que ya está en nuestro archivo `.gitignore`. Así que, aunque este archivo contenga valores sensibles, no se confirmará en el repositorio.

Hay algunos otros archivos `.env` que puedes crear... y puedes verlos mencionados aquí. No son tan importantes, pero si quieres leer sobre ellos, puedes consultar la documentación.

## Visualización de las variables de entorno con debug:dotenv

Otra cosa genial sobre las variables de entorno es que puedes visualizarlas ejecutando:

```terminal
php bin/console debug:dotenv
```

¡Genial! Puedes ver el valor actual de `GITHUB_TOKEN`... y que este valor también está establecido en `.env.local`. En cambio, `APP_ENV` y `APP_SECRET` tienen `n/a`aquí, lo que significa que sus valores no están siendo anulados en `.env.local`. También nos dice qué archivos `.env` ha detectado.

## Procesadores de variables de entorno

Hay algunos trucos que puedes utilizar con las variables de entorno. Por ejemplo, hay algo llamado "sistema de procesadores" en el que podrías utilizar `trim` para "recortar" el espacio en blanco en `GITHUB_TOKEN`. O podrías utilizar `file` donde la variable `GITHUB_TOKEN`es en realidad una ruta a un archivo que contiene el valor verdadero. En cualquier caso, esto se llama "procesadores de variables de entorno" si quieres leer más sobre ellos.

A continuación, vamos a hablar rápidamente sobre el despliegue... pero aún más sobre cómo podemos almacenar de forma segura estos valores sensibles cuando se despliega a producción. Una opción es la bóveda de secretos de Symfony.
