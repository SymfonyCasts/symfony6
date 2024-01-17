# Personalizar un comando

¡Tenemos un nuevo comando de consola! Pero... todavía no hace mucho, aparte de imprimir un mensaje. Vamos a hacerlo más elegante.

Desplázate hasta la parte superior. Aquí es donde tenemos el nombre de nuestro comando, y también hay una descripción... que aparece al lado del comando. Permíteme cambiar la nuestra por

> Un comando autoconsciente que puede hacer... sólo una cosa.

[[[ code('0665b762f7') ]]]

## Configurar los argumentos y las opciones

Nuestro comando se llama `app:talk-to-me` porque, cuando lo ejecutemos, quiero que sea posible pasar un nombre al comando -como Ryan- y que éste responda con "¡Hey Ryan!". Así que, literalmente, escribiremos `bin/console app:talk-to-me ryan` y nos responderá.

Cuando quieres pasar un valor a un comando, eso se conoce como argumento... y se configuran abajo en... el método `configure()`. Ya hay un argumento llamado `arg1`... así que vamos a cambiarlo por `name`.

Esta clave es completamente interna: nunca verás la palabra `name` cuando utilices este comando. Pero utilizaremos esta clave para leer el valor del argumento dentro de un minuto. También podemos dar una descripción al argumento y, si quieres, puedes hacerla obligatoria. Yo lo mantendré como opcional.

Lo siguiente que tenemos son las opciones. Son como los argumentos... excepto que empiezan con un `--` cuando los utilizas. Quiero tener una bandera opcional en la que podamos decir `--yell` para que el comando grite nuestro nombre.

En este caso, el nombre de la opción, `yell`, es importante: utilizaremos este nombre cuando pasemos la opción en la línea de comandos para utilizarla. El`InputOption::VALUE_NONE` significa que nuestra bandera será simplemente `--yell` y no`--yell=` algún valor. Si tu opción acepta un valor, lo cambiarías por`VALUE_REQUIRED`. Por último, dale una descripción.

[[[ code('8001eef9d6') ]]]

¡Precioso! Todavía no vamos a utilizar este argumento y esta opción... pero ya podemos volver a ejecutar nuestro comando con la opción `--help`:

```terminal
php bin/console app:talk-to-me --help
```

Y... ¡impresionante! Vemos la descripción aquí arriba... junto con algunos detalles sobre cómo utilizar el argumento y la opción `--yell`.

## Rellenando execute()

Cuando llamemos a nuestro comando, de forma muy sencilla, Symfony llamará a `execute()`... que es donde empieza la diversión. Dentro, podemos hacer lo que queramos. Nos pasa dos argumentos: `$input` y `$output`. Si quieres leer alguna entrada -como el argumento`name` o la opción `yell` -, utiliza `$input`. Y si quieres dar salida a algo, utiliza `$output`.

Pero en Symfony, normalmente metemos estas dos cosas en otro objeto llamado `SymfonyStyle`. Esta clase de ayuda hace que la lectura y la salida sean más fáciles... y más elegantes.

Bien: empecemos por decir `$name = $input->getArgument('name')`. Si no tenemos un nombre, lo pondré por defecto en `whoever you are`. A continuación, lee la opción: `$shouldYell = $input->getOption('yell')`:

[[[ code('c25283b263') ]]]

Genial. Despejemos esto de aquí abajo y empecemos nuestro mensaje:`$message = sprintf('Hey %s!', $name)`. Luego, si queremos gritar, ya sabes qué hacer: `$message = strtoupper($message)`. Abajo, utiliza `$io->success()` y pon el mensaje ahí.

[[[ code('e396b4e104') ]]]

Este es uno de los muchos métodos de ayuda de la clase `SymfonyStyle` que ayudan a dar formato a tu salida. También está `$io->warning()`, `$io->note()`, y varios más.

Vamos a probarlo. Gira y ejecuta:

```terminal
php bin/console app:talk-to-me ryan
```

Y... ¡oh, hola! Si gritamos

```terminal-silent
php bin/console app:talk-to-me ryan --yell
```

¡TAMBIÉN FUNCIONA! Incluso podemos gritar a "quienquiera que sea":

```terminal-silent
php bin/console app:talk-to-me --yell
```

¡Impresionante! Pero vamos a volvernos más locos... autocableando un servicio y haciendo una pregunta de forma interactiva en la línea de comandos. Eso a continuación... ¡y es el último capítulo!
