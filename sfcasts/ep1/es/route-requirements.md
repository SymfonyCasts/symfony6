# Rutas inteligentes: Sólo GET y Validar {Comodines}

Ahora que tenemos una nueva página, en tu terminal, ejecuta de nuevo `debug:router`.

```terminal-silent
php bin/console debug:router
```

Sí, ¡ahí está nuestra nueva ruta! Observa que la tabla tiene una columna llamada "Método" que dice "cualquiera". Esto significa que puedes hacer una petición a esta URL utilizando cualquier método HTTP -como GET o POST- y coincidirá con esa ruta.

## Restringir las rutas sólo a GET o POST

Pero el objetivo de nuestra nueva ruta API es permitir a los usuarios hacer una petición GET para obtener datos de la canción. Técnicamente, ahora mismo, también podrías hacer una petición POST a esto... y funcionaría perfectamente. Puede que no nos importe, pero a menudo con las APIs, querrás restringir una ruta para que sólo funcione con un método específico como GET, POST o PUT. ¿Podemos hacer que esta ruta, de alguna manera, sólo funcione con peticiones GET?

Sí! Añadiendo otra opción a la `Route`. En este caso, se llama `methods`, ¡incluso se autocompleta! Establece esto como un array y, pon `GET`.

[[[ code('cbd8c30440') ]]]

Voy a mantener pulsado Comando y a hacer clic en la clase `Route` de nuevo... para que podamos ver que... ¡sí! `methods` es uno de los argumentos.

Volvemos a `debug:router`:

```terminal-silent
php bin/console debug:router
```

Bien. La ruta ahora sólo coincidirá con las peticiones GET. Es... un poco difícil probar esto, ya que un navegador siempre hace peticiones GET si vas directamente a una URL... pero aquí es donde otro comando de `bin/console` resulta útil: `router:match`.

Si lo ejecutamos sin argumentos

```terminal-silent
php bin/console router:match
```

Nos da un error, ¡pero muestra cómo se utiliza! Inténtalo:

```terminal
php bin/console router:match /api/songs/11
```

Y... ¡eso coincide con nuestra nueva ruta! Pero ahora pregúntate qué pasaría si hiciéramos una petición POST a esa URL con `--method=POST`:

```terminal-silent
php bin/console router:match /api/songs/11 --method=POST
```

¡Ninguna ruta coincide con esta ruta con ese método! Pero dice que casi coincide con nuestra ruta.

## Restringir los comodines de ruta mediante Regex

Vamos a hacer una cosa más para restringir nuestra nueva ruta. Voy a añadir una pista de tipo `int` al argumento `$id`.

[[[ code('eb386d1bd6') ]]]

Eso... no cambia nada, excepto que ahora PHP tomará la cadena `id` de la URL que Symfony pasa a este método y la convertirá en un `int`, lo cual es... agradable porque entonces estamos tratando con un verdadero número entero en nuestro código.

Puedes ver la sutil diferencia en la respuesta. Ahora mismo, el campo `id` es una cadena. Cuando actualizamos, `id` es ahora un número verdadero en JSON.

Pero... si alguien se hiciera el remolón... y pasara a `/api/songs/apple`... ¡vaya! ¡Un error PHP, que, en producción, sería una página de error 500! Eso no me gusta.

Pero... ¿qué podemos hacer? El error se produce cuando Symfony intenta llamar a nuestro controlador y le pasa ese argumento. Así que no podemos poner código en el controlador para comprobar si `$id` es un número: ¡es demasiado tarde!

¿Y si, en cambio, pudiéramos decirle a Symfony que esta ruta sólo debe coincidir si el comodín `id` es un número? ¿Es posible? Totalmente

Por defecto, cuando tienes un comodín, coincide con cualquier cosa. Pero puedes cambiarlo para que coincida con una expresión regular personalizada. Dentro de las llaves, justo después del nombre, añade un `<`, luego `>` y, entre medias, `\d+`. Es una expresión regular que significa "un dígito de cualquier longitud".

[[[ code('b28748cdaa') ]]]

¡Pruébalo! Actualiza y... ¡sí! A 404. No se ha encontrado ninguna ruta: simplemente no ha coincidido con esta ruta. Un 404 está muy bien... pero un error 500... eso es algo que queremos evitar. Y si volvemos a `/api/songs/5`... eso sigue funcionando.

A continuación: si me preguntaras cuál es la parte más central e importante de Symfony, no lo dudaría: son los servicios. Descubramos qué es un servicio y cómo es la clave para liberar el potencial de Symfony.
