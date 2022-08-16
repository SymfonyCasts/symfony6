# Docker y variables de entorno

Ahora tenemos una base de datos Postgres ejecutándose dentro de un contenedor Docker. Podemos verlo ejecutando:

```terminal
docker-compose ps
```

Esto también nos dice que si queremos hablar con esta base de datos, podemos conectarnos al puerto`50739` en nuestra máquina local. Ese será un puerto diferente para ti, porque se elige al azar cuando iniciamos Docker.

También hemos aprendido que podemos hablar con la base de datos directamente a través de:

```terminal
docker-compose exec database psql --user symfony --password app
```

Para conseguir que nuestra aplicación real apunte a la base de datos que se ejecuta en este puerto, podríamos entrar en `.env` o `.env.local` y personalizar `DATABASE_URL`en consecuencia: con el usuario `symfony` la contraseña `ChangeMe`... y con el puerto que tengas actualmente. Aunque... tendríamos que actualizar ese puerto cada vez que iniciemos y detengamos Docker.

## El Binario de Symfony y variables de entorno de Docker

Afortunadamente, no tenemos que hacer nada de eso porque, sorpresa, ¡la variable de entorno `DATABASE_URL`ya está correctamente configurada! Cuando configuramos nuestro proyecto, iniciamos un servidor local de desarrollo utilizando el binario de Symfony.

Como recordatorio, voy a ejecutar

```terminal
symfony server:stop
```

para detener ese servidor. Y luego reiniciarlo con:

```terminal
symfony serve -d
```

Menciono esto porque el binario `symfony` tiene un superpoder de Docker bastante impresionante.

Observa: cuando actualices ahora... y pases el ratón por la esquina inferior derecha de la barra de herramientas de depuración de la web, dirá "Env Vars: De Docker".

En resumen, ¡el binario de Symfony se dio cuenta de que Docker se estaba ejecutando y expuso algunas nuevas variables de entorno que apuntaban a la base de datos! Te lo mostraré. Abre`public/index.php`. Normalmente no nos preocupamos por este archivo... pero es un buen lugar para volcar algo de información justo cuando nuestra aplicación empieza a arrancar. Dentro de la llamada de retorno,`dd()` la superglobal `$_SERVER`. Esa variable contiene mucha información, incluyendo cualquier variable de entorno.

Bien, gira y actualiza. ¡Una gran lista! Busca `DATABASE_URL` y... ¡ahí está! Pero ese no es el valor que tenemos en nuestro archivo `.env`: el puerto no es el que tenemos ahí. No, ¡es el puerto correcto necesario para hablar con el contenedor Docker!

Sí, el binario de Symfony detecta que Docker se está ejecutando y establece una variable de entorno real`DATABASE_URL` que apunta a ese contenedor. Y recuerda que, al tratarse de una variable de entorno real, ganará a cualquier valor colocado en los archivos `.env` o `.env.local`.

La cuestión es que, con sólo iniciar Docker, ya está todo configurado: no hemos tenido que tocar ningún archivo de configuración. Eso está muy bien.

Por cierto, si quieres ver todas las variables de entorno que configura el binario de Symfony, puedes ejecutarlo:

```terminal
symfony var:export --multiline
```

Pero por mucho la más importante es `DATABASE_URL`.

Bien: ¡Doctrine está configurado! A continuación, vamos a crear la base de datos propiamente dicha mediante un comando `bin/console`. Cuando lo hagamos, aprenderemos un truco para hacerlo con las variables de entorno del binario de Symfony.
