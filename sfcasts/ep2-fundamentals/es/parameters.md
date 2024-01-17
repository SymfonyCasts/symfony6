# Parámetros

Sabemos que existe este concepto de contenedor que contiene todos nuestros servicios... y podemos ver la lista completa de servicios ejecutando

```terminal
php bin/console debug:container
```

## Listado de Parámetros

Pues bien, resulta que el contenedor guarda otra cosa: rencores. En serio, no esperes gastar una broma al contenedor de servicios y salirte con la tuya.

Vale, lo que realmente guarda, además de los servicios, son parámetros. Son simples valores de configuración, y podemos verlos ejecutando un comando similar:

```terminal
php bin/console debug:container --parameters
```

Son básicamente variables que puedes leer y referenciar en tu código. En realidad, no tenemos que preocuparnos por la mayoría de ellos. Son establecidas por cosas internas y utilizadas por cosas internas. Pero hay algunas que empiezan por `kernel`que son bastante interesantes, como `kernel.project_dir`, que apunta al directorio de nuestro proyecto. ¡Sí! Si alguna vez necesitas una forma de referirte al directorio de tu aplicación, este parámetro puede ayudarte.

## Obtención de parámetros de un controlador

Entonces... ¿cómo utilizamos estos parámetros? Hay dos maneras. En primer lugar, no es muy común, pero puedes obtener un parámetro en tu controlador. Por ejemplo, en `VinylController`, vamos a `dd($this->getParameter())` -que es un método abreviado de `AbstractController` - y luego a `kernel.project_dir`. ¡Incluso obtenemos un bonito autocompletado gracias al plugin Symfony PhpStorm! Y cuando lo probamos... ¡sí! ¡Ahí está!

## Haciendo referencia a los parámetros con %parameter%

Ahora... borra eso. Esto funciona, pero la mayoría de las veces, la forma en que utilizarás los parámetros es haciendo referencia a ellos en tus archivos de configuración. Y esto ya lo hemos visto antes! Abre `config/packages/twig.yaml`. ¿Recuerdas ese `default_path`? Eso es hacer referencia al parámetro `kernel.project_dir`. Cuando estés en cualquiera de estos archivos de configuración de `.yaml`y quieras hacer referencia a un parámetro, puedes utilizar esta sintaxis especial: `%`, el nombre del parámetro, y luego otro `%`.

## Crear un nuevo parámetro

Abre `cache.yaml`. Vamos a configurar `cache.adapter` como `filesystem` para todos los entornos. Luego, lo anulamos para que sea el adaptador `array` sólo en el entorno dev. Veamos si podemos acortar esto creando un nuevo parámetro.

¿Cómo se crean los parámetros? En cualquiera de estos archivos, añade una clave raíz llamada`parameters`. Debajo de ella, puedes simplemente... inventar un nombre. Lo llamaré `cache_adapter`, y ponle nuestro valor: `cache.adapter.filesystem`.

Si tienes una clave raíz `framework`, Symfony pasará toda la configuración a FrameworkBundle. Lo mismo ocurre con la clave `twig` y TwigBundle.

Pero `parameters` es especial: todo lo que esté por debajo de ella creará un parámetro.

Así que sí... ahora tenemos un nuevo parámetro `cache.adapter`... que en realidad aún no estamos utilizando. ¡Pero ya podemos verlo! Ejecuta:

```terminal
php bin/console debug:container --parameters
```

Cerca de la parte superior... ahí está - ¡ `cache_adapter`! Para utilizarlo, aquí abajo para `app`, di `%cache_adapter%`.

Eso es todo. Nota rápida: Te habrás dado cuenta de que a veces utilizo comillas en YAML y a veces no. La mayoría de las veces, en YAML, no es necesario utilizar las comillas... pero siempre se puede. Y si alguna vez no estás seguro de si son necesarias o no, mejor estar seguro y utilizarlas.

Los parámetros son, de hecho, un ejemplo en el que las comillas son necesarias. Si no lo rodeáramos con comillas, parecería una sintaxis especial de YAML y arrojaría un error.

De todos modos, en el entorno `dev`, en lugar de decir `framework`, `cache`, y `app`, lo único que tenemos que hacer es anular ese parámetro. Diré `parameters`, luego`cache_adapter`... y lo pondré en `cache.adapter.array`.

Para ver si eso funciona, gira aquí y ejecuta otro comando de ayuda:

```terminal
php bin/console debug:config framework cache
```

Recuerda que `debug:config` te mostrará cuál es tu configuración actual bajo la clave`framework`, y luego la subclave `cache`. Y aquí puedes ver que `app` está configurado como `cache.adapter.array`, el valor resuelto para el parámetro.

Comprobemos el valor en el entorno prod... sólo para asegurarnos de que también es correcto. Cuando ejecutes cualquier comando de `bin/console`, ese comando se ejecutará en el mismo entorno en el que se esté ejecutando tu aplicación. Así que cuando ejecutamos `debug:config`, eso se ejecuta en el entorno dev.

Para ejecutar el comando en el entorno prod, podríamos ir aquí y cambiar`APP_ENV` por `prod` temporalmente... pero hay una forma más fácil. Puedes anular el entorno al ejecutar cualquier comando añadiendo una bandera al final. Por ejemplo:

```terminal
php bin/console debug:config framework cache --env=prod
```

Pero antes de intentarlo, siempre tenemos que borrar nuestra caché primero para ver los cambios en el entorno `prod`. Hazlo ejecutando:

```terminal
php bin/console cache:clear --env=prod
```

Ahora prueba:

```terminal
php bin/console debug:config framework cache --env=prod
```

Y... ¡qué bien! Aparece `cache.adapter.filesystem`. Así pues, el contenedor también contiene parámetros. Este no es un concepto súper importante en Symfony, así que, mientras entiendas cómo funcionan, estás bien.

Bien, volvamos a la inyección de dependencias. Sabemos que podemos autoinyectar servicios en el constructor de un servicio o en los métodos del controlador. ¿Pero qué pasa si necesitamos pasar algo que no es autoconectable? Por ejemplo, ¿qué pasa si queremos pasar uno de estos parámetros a un servicio? Averigüemos cómo funciona eso a continuación.
