# MakerBundle y Autoconfiguración

¡Felicidades, equipo! ¡Hemos terminado con lo más pesado de este tutorial! Así que es hora de dar la vuelta de la victoria. Vamos a instalar uno de mis bundles favoritos de Symfony: MakerBundle. Busca tu terminal y ejecuta:

```terminal
composer require maker --dev
```

En este caso, estoy usando la bandera `--dev` porque se trata de una utilidad de generación de código que sólo necesitamos localmente, no en producción.

Este bundle, por supuesto, proporciona servicios. Pero estos servicios no están pensados para que los utilicemos directamente. En su lugar, todos los servicios de este bundle potencian un montón de nuevos comandos de `bin/console`. Ejecuta

```terminal
php bin/console
```

y busca la sección `make`. Ooh. Aquí hay un montón de cosas para configurar la seguridad, generar entidades de doctrina para la base de datos (lo que haremos en el siguiente tutorial), hacer un CRUD, y mucho más.

## Generar una nueva clase de mando

Vamos a probar una: ¿qué tal si intentamos construir nuestro propio y nuevo comando de consola personalizado que aparecerá en esta lista? Para ello, ejecuta:

```terminal
php bin/console make:command
```

Esto te pedirá interactivamente el nombre del comando. Digamos`app:talk-to-me`. No es necesario, pero es bastante habitual anteponer a tus comandos personalizados el prefijo `app:`. Y... ¡listo!

Eso ha creado exactamente un nuevo archivo: `src/Command/TalkToMeCommand.php`. Vamos a abrirlo:

[[[ code('01fe532ac9') ]]]

¡Genial! ¡Arriba, puedes ver que el nombre y la descripción del comando se hacen en un atributo PHP! Luego, abajo en este método `configure()`, del que hablaremos más en un minuto, podemos configurar los argumentos y opciones que se pueden pasar desde la línea de comandos.

Cuando ejecutemos el comando, se llamará a `execute()`... donde podemos imprimir cosas en la pantalla o leer opciones y argumentos.

Quizá lo mejor de esta clase es que... ya funciona. ¡Compruébalo! De vuelta a tu terminal, ejecuta;

```terminal
php bin/console app:talk-to-me
```

Y... ¡está vivo! No hace mucho, pero esta salida viene de aquí abajo. ¡Guau!

## Autoconfiguración: Descubriendo automáticamente los "plugins"

Pero espera... ¿cómo ha visto Symfony instantáneamente nuestra nueva clase `Command` y ha sabido que debe empezar a utilizarla? ¿Es porque vive en el directorio `src/Command/`... y Symfony escanea las clases que viven aquí? No Podríamos cambiar el nombre de este directorio a`ThereAreDefinitelyNoCommandsInHere`... y Symfony seguiría viendo el comando.

La forma en que esto funciona es mucho más genial. Abre `config/services.yaml` y mira la sección `_defaults`:

[[[ code('99a7530291') ]]]

Hemos hablado de lo que significa `autowire: true`, pero no he explicado el propósito de`autoconfigure: true`. Como está por debajo de `_defaults`, la autoconfiguración está activa en todos nuestros servicios, incluido nuestro nuevo servicio `TalkToMeCommand`. 
Cuando `autoconfiguration` está activado, básicamente le dice a Symfony:

> Oye, por favor, mira la clase base o la interfaz de cada servicio, y si
> parece que una clase debe ser un comando de consola... o un suscriptor de eventos...
> o cualquier otra clase que se enganche a una parte de Symfony, por favor, integra automáticamente
> integra el servicio en ese sistema. Bien, gracias. ¡Adiós!

¡Si! Symfony ve que nuestra clase extiende `Command` y piensa:

> Hmm, puede que no sea una IA autoconsciente... pero apuesto a que esto es un comando. Será mejor que se lo notifique al
¡> al sistema de la consola sobre ello!

Me encanta la autoconfiguración. Significa que podemos crear una clase PHP, extender cualquier clase base o implementar cualquier interfaz necesaria para la "cosa" que estamos construyendo, y... simplemente funcionará.

Internamente, si quieres conocer todos los detalles frikis, la autoconfiguración añade una etiqueta a tu servicio, como `console.command`, que es lo que, en última instancia, ayuda a que el sistema de la consola se fije en él.

Muy bien, ahora que nuestro comando funciona, vamos a divertirnos un poco y a personalizarlo a continuación.
