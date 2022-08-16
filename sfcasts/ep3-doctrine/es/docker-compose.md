# docker-compose y puertos expuestos

Necesitamos poner en marcha una base de datos: MySQL, Postgresql, lo que sea. Si ya tienes una en marcha, ¡genial! Todo lo que tienes que hacer es copiar tu variable de entorno `DATABASE_URL`, abrir o crear un archivo `.env.local`, pegarlo, y luego cambiarlo para que coincida con lo que esté usando tu configuración local. Si decides hacer esto, no dudes en saltar al final del capítulo 4, donde configuramos el `server_version`.

## Docker sólo para la base de datos

En mi caso, no tengo una base de datos funcionando localmente en mi sistema... y no voy a instalar una. En su lugar, quiero utilizar Docker. Y vamos a utilizar Docker de una forma interesante. Tengo PHP instalado localmente:

```terminal-silent
php -v
```

Así que no voy a usar Docker para crear un contenedor específicamente para PHP. En su lugar, voy a utilizar Docker simplemente para ayudar a arrancar cualquier servicio que mi aplicación necesite localmente. Y en este momento, necesito un servicio de base de datos. Gracias a cierta magia entre Docker y el binario de Symfony, esto va a ser súper fácil.

Para empezar, ¿recuerdas cuando la receta de Doctrine nos preguntó si queríamos la configuración de Docker? Como dijimos que sí, la receta nos dio los archivos `docker-compose.yml` y`docker-compose.override.yml`. Cuando Docker arranque, leerá ambos... y están divididos en dos partes por si quieres usar también Docker para desplegar en producción. Pero no vamos a preocuparnos por eso: sólo queremos usar Docker para facilitar la vida en el desarrollo local.

Estos archivos dicen que arrancarán un único contenedor de base de datos Postgres con un usuario llamado `symfony` y una contraseña `ChangeMe`. También expondrá el puerto 5432 del contenedor -que es el puerto normal de Postgres- a nuestra máquina anfitriona en un puerto aleatorio. Esto significa que vamos a poder hablar con el contenedor Docker de Postgresql como si se estuviera ejecutando en nuestra máquina local... siempre que conozcamos el puerto aleatorio que Docker ha elegido. Veremos cómo funciona en un minuto.

Por cierto, si quieres utilizar MySQL en lugar de Postgres, puedes hacerlo. Puedes actualizar estos archivos... o eliminar ambos y ejecutar

```terminal
php bin/console make:docker:database
```

para generar un nuevo archivo de composición para MySQL o MariaDB. Yo me voy a quedar con Postgres porque es increíble.

Llegados a este punto, vamos a poner en marcha Docker y aprender un poco sobre cómo comunicarse con la base de datos que vive dentro. Si te sientes bastante cómodo con Docker, no dudes en pasar al siguiente capítulo.

## Iniciar el contenedor

De todas formas, vamos a poner en marcha nuestro contenedor. Primero, asegúrate de que tienes Docker realmente instalado en tu máquina: No lo mostraré porque varía según el sistema operativo. Luego, busca tu terminal y ejecuta:

```terminal
docker-compose up -d
```

El `-d` significa "ejecutar en segundo plano como un demonio". La primera vez que lo ejecute, probablemente descargará un montón de cosas. Pero al final, ¡tu contenedor debería arrancar!

## Comunicarse con el contenedor

¡Genial! ¿Pero ahora qué? ¿Cómo podemos hablar con el contenedor? Ejecuta un comando llamado:

```terminal
docker-compose ps
```

Esto muestra información sobre todos los contenedores que se están ejecutando actualmente... sólo uno para nosotros. Lo realmente importante es que el puerto 5432 del contenedor está conectado al puerto 50700 de mi máquina anfitriona. Esto significa que si hablamos con este puerto, estaremos hablando realmente con esa base de datos Postgres. Ah, y este puerto es aleatorio: será diferente en tu máquina... e incluso cambiará cada vez que paremos y arranquemos nuestro contenedor. Pronto hablaremos de ello.

Pero ahora que conocemos el puerto 50700, podemos utilizarlo para conectarnos a la base de datos. Por ejemplo, como estoy utilizando Postgres, podría ejecutar:

```terminal-silent
psql --user=symfony --port=50700 --host=127.0.0.1 --password app
```

Esto significa: conectar con Postgres en el puerto 127.0.0.1 50700 utilizando el usuario `symfony` y hablando con la base de datos `app`. Todo esto está configurado en el archivo `docker-compose.yml`. Copia la contraseña de `ChangeMe` porque esa última bandera le dice a Postgres que te pida esa contraseña. Pégala y... ¡estamos dentro!

Si utilizas MySQL, podemos hacer esto mismo con un comando `mysql`.

Pero esto sólo funciona si tenemos ese comando `psql` instalado en nuestra máquina local. Así que vamos a probar con otro comando. Ejecuta

```terminal
docker-compose ps
```

de nuevo. El contenedor se llama `database`, que proviene de nuestro archivo `docker-compose.yml`. Así podremos cambiar el comando anterior por:

```terminal
docker-compose exec database psql --username symfony --password app
```

Esta vez, estamos ejecutando el comando `psql` dentro del contenedor, por lo que no necesitamos instalarlo localmente. Escribe `ChangeMe` como contraseña y... ¡vamos a estar dentro!

La cuestión es: ¡sólo con ejecutar `docker-compose up`, tenemos un contenedor de base de datos Postgres con el que podemos hablar!

## Detener el contenedor

Por cierto, cuando estés preparado para detener el contenedor más adelante, puedes ejecutarlo:

```terminal
docker-compose stop
```

Que básicamente apaga el contenedor. O puedes ejecutar el más común

```terminal
docker-compose down
```

que apaga los contenedores y los elimina. Para volver a arrancar, es lo mismo:

```terminal
docker-compose up -d
```

Pero fíjate en que cuando volvemos a ejecutar `docker-compose ps`, ¡el puerto de mi máquina anfitriona es un puerto aleatorio diferente! Así que, en teoría, podríamos configurar la variable `DATABASE_URL`para que apunte a nuestra base de datos Postgres, incluyendo el uso del puerto correcto. ¡Pero ese puerto aleatorio que sigue cambiando va a ser molesto!

Afortunadamente, ¡hay un truco para esto! Resulta que nuestra aplicación ya está configurada, ¡sin que nosotros hagamos nada! Eso a continuación.
