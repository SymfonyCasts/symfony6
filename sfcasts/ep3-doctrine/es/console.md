# El comando "symfony console" y server_version

Doctrine está ahora configurado para hablar con nuestra base de datos, que vive dentro de un contenedor Docker. Esto es gracias a que el servidor Symfony dev expone esta variable de entorno `DATABASE_URL`, que apunta al contenedor. En mi caso, el contenedor es accesible en el puerto 50739.

Ahora vamos a asegurarnos de que la base de datos real se ha creado. Pero primero, en `index.php`, elimina el `dd()`... y luego cierra ese archivo.

Ve a tu terminal y ejecuta:

```terminal
php bin/console
```

Esto imprime todos los comandos de `bin/console` que están disponibles, incluyendo un montón de nuevos comandos que empiezan con la palabra `doctrine`. Ooh. La mayoría de ellos no son muy importantes y ya iremos viendo los que sí lo son.

## bin/console doctrine:database:create

Por ejemplo, uno se llama `doctrine:database:create`. Genial, vamos a probarlo:

```terminal
php bin/console doctrine:database:create
```

Y... ¡error! Fíjate bien: está intentando conectarse al puerto 5432. ¡Pero nuestra variable de entorno apunta al puerto 50739! Es como si utilizara el valor `DATABASE_URL`de nuestro archivo `.env` en lugar del real que establece el binario de Symfony.

Y, de hecho, eso es exactamente lo que está ocurriendo. Y, ¡tiene sentido! Cuando actualizamos la página en nuestro navegador, eso se procesa a través del binario `symfony`, que le da la oportunidad de añadir la variable de entorno.

Pero cuando ejecutamos un comando `bin/console` -donde `console` es sólo un archivo PHP que vive en un directorio `bin/`, el binario `symfony` nunca se utiliza como parte de ese proceso. Esto significa que nunca tiene la oportunidad de añadir la variable de entorno, por lo que Symfony vuelve a utilizar el valor de `.env`.

Para solucionar esto, siempre que ejecutemos un comando `bin/console` que necesite las variables de entorno de Docker, en lugar de ejecutar `bin/console`, ejecuta `symfony console`:

```terminal-silent
symfony console doctrine:database:create
```

Eso es literalmente un atajo para ejecutar `bin/console`: no es diferente. Pero el hecho de que lo estemos ejecutando a través del binario `symfony` le da la oportunidad de añadir las variables de entorno.

Cuando probamos esto... ¡sí! Obtenemos un error porque aparentemente la base de datos ya existe, pero se conectó con éxito y habló con la base de datos.

## Configurar la versión del servidor

Bien, hay una última parte de la configuración que debemos establecer. Abre`config/packages/doctrine.yaml`. Este archivo viene de la receta. Busca`server_version` y desactívalo.

Este valor "13" se refiere a la versión de mi motor de base de datos. Como estoy usando la versión 13 de Postgres, necesito el 13 aquí. Si utilizas MySQL, puede que necesites la 8 o la 5.7.

Esto ayuda a Doctrine a determinar qué características soporta tu base de datos o no... ya que una versión más reciente de una base de datos podría soportar características que una versión más antigua no soporta. No es una pieza de configuración especialmente interesante, sólo tenemos que asegurarnos de que está configurada.

Ok equipo: toda la configuración aburrida está hecha. Lo siguiente: ¡creamos nuestra primera clase de entidad! Las entidades son el concepto más fundamental de Doctrine y la clave para hablar con nuestra primera tabla de la base de datos.
