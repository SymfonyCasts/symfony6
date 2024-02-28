# Nuevo Componente: Programador

Uno de los componentes nuevos más chulos es Scheduler, que viene de Symfony 6.3. Si necesitas activar una tarea recurrente, como generar un informe semanal, enviar algún tipo de latido cada 10 minutos, realizar un mantenimiento rutinario... o incluso algo personalizado y raro, este componente es para ti. ¡Es realmente genial! Merece su propio tutorial, pero nos preocuparemos de eso más adelante. Vamos a probarlo.

## Instalación del Programador

En tu línea de comandos, instálalo con:

```terminal
composer require symfony/scheduler symfony/messenger
```

Scheduler se basa en Messenger: ¡funcionan juntos! El proceso es el siguiente. Creas una clase de mensaje y un manejador, como harías normalmente con Messenger. Luego le dices a Symfony

> ¡Eh! Quiero que envíes este mensaje para que se gestione cada siete días, o cada
> una hora... o algo más raro.

## Crear la clase y el manejador de mensajes

Esto significa que el primer paso es generar un mensaje Messenger. Ejecuta:

```terminal
php bin/console make:message
```

Llámalo `LogHello`. ¡Genial! Aquí ha creado la clase mensaje - `LogHello` 

[[[ code('9fd176c05a') ]]]

y su manejador, cuyo método `__invoke()` será llamado cuando `LogHello` se envíe a través de Messenger.

[[[ code('cd839e5361') ]]]

En `LogHello`, dale un constructor con `public int $length`. 

[[[ code('787cd14c73') ]]]

Esto nos ayudará a saber qué mensaje se está gestionando y cuándo. En el manejador, añade también un constructor para que podamos autocablear `LoggerInterface $logger`.

[[[ code('a3c92cd450') ]]]

Abajo en el método, utiliza `$this->logger->warning()` -sólo para que estas entradas de registro sean fáciles de ver- y luego `str_repeat()` para registrar un icono de guitarra `$message->length`veces. También registraré ese número al final.

[[[ code('5cde3a301b') ]]]

¡Comprobación de mensajes y manejadores!

## Crear el programa

Lo siguiente es crear un horario que le diga a Symfony:

> Yo, otra vez. Por favor, envía un mensaje `LogHello` a través de Messenger cada
> 7 días.

O en nuestro caso, ¡cada pocos segundos porque no creo que quieras ver este screencast durante la próxima semana! 

En `src/`, no tengo que hacer esto, pero crearé un directorio `Scheduler`. Y dentro, una clase PHP llamada, qué tal, `MainSchedule`. Haz que esto implemente`ScheduleProviderInterface`.

[[[ code('0191745407') ]]]

Puedes tener varios de estos proveedores de programación en tu sistema... o puedes tener una clase que configure todos tus mensajes recurrentes. Tú decides.

Esta clase también necesita un atributo llamado `#[AsSchedule]`. Tiene un argumento opcional: el nombre de la programación, que, creativamente, es por defecto `default`. Pronto veremos por qué es importante ese nombre. Yo utilizaré `default`.

[[[ code('651134046e') ]]]

## Crear los mensajes recurrentes

Bien, ve a Código -> Generar, o comando+N en un Mac - para implementar el único método que necesitamos: `getSchedule()`. 

[[[ code('ab2732d408') ]]]

El código aquí es maravillosamente sencillo y expresivo. Devuelve un `new Schedule()`, luego añade cosas a éste llamando a `->add()`. Dentro, por cada "cosa" que necesites programar, di `RecurringMessage::`. Hay varias formas de crear estos mensajes recurrentes. La más sencilla es `every()`, como cada `7 days` o cada `5 minutes`. También puedes pasar una sintaxis `cron`, o llamar a `trigger()`. En ese caso, definirías tu propia lógica para saber exactamente cuándo quieres que se active tu mensaje raro.

Utiliza `every()` y pasa `4 seconds`. Cada 4 segundos, queremos que este nuevo mensaje `LogHello` se envíe a Messenger. Cópialo y crea otro para cada `3 seconds`.

[[[ code('e9b9ccbf5f') ]]]

¡Ya está!

## Consumir el transporte programador

El resultado de crear un proveedor de programación es que se crea un nuevo transporte de Messenger. Para que se procesen tus mensajes recurrentes, necesitas tener un trabajador que esté ejecutando el comando `messenger:consume`.

En tu terminal, ejecuta `bin/console messenger:consume` con un `-v` para que podamos ver los mensajes de registro de nuestro manejador. A continuación, pasa el nombre del nuevo transporte añadido automáticamente: `scheduler_default`... donde `default` es el nombre que utilizamos en el atributo`#[AsSchedule]`.

```terminal-silent
php bin/console messenger:consume -v scheduler_default
```

Dale, espera unos 3 segundos... ¡ahí está! ¡Cuatro! Luego vuelve a aparecer el 3, y cuatro, y luego tres. Al cabo de 12 segundos, deberían ejecutarse, sí, casi en el mismo momento. Técnicamente, éste se despachó primero, y aquél se despachó inmediatamente después.

Pero, permíteme que deje de flipar y retroceda: ¡funciona! ¡Es precioso!

## ¿Cómo funciona el Programador?

¿Cómo funciona? Yo me preguntaba lo mismo. Cuando se inicia el comando trabajador, hace un bucle sobre cada `RecurringMessage`, calcula el próximo tiempo de ejecución de cada uno y lo utiliza para crear una lista -llamada "montón"- de próximos mensajes. A continuación, realiza un bucle sin fin. En cuanto la hora actual coincide -o es posterior- al tiempo de ejecución programado del siguiente mensaje de la pila, toma ese mensaje y lo envía a través de Messenger. A continuación, pide a este mensaje recurrente su siguiente tiempo de ejecución y lo coloca en el montón.

Y este proceso... continúa para siempre.

## Haz que tu Programación tenga Estado

Aunque hay un problema que se esconde a plena vista: si reiniciamos el comando, crea la programación desde cero. Eso significa que espera tres segundos y cuatro segundos nuevos antes de enviar los mensajes.

En una aplicación real, esto será un problema. Imagina que tienes un mensaje que se ejecuta cada 7 días. Por alguna razón, al cabo de 5 días, tu comando `messenger:consume` sale y se reinicia. Debido a esto, tu mensaje recurrente se ejecutará ahora siete días después de este reinicio: así que se ejecutará el día 12. Si se sigue reiniciando, ¡puede que tu mensaje no se ejecute nunca!

Esto no es factible. Por eso, en el mundo real, siempre hacemos que nuestra programación tenga estado. Y esto es fácil. Crea un método `__construct` y autoconecta un`private CacheInterface`: el de la caché de Symfony.

[[[ code('ac7a77fd10') ]]]

A continuación, llama a `->stateful()` y pásale `$this->cache`.

[[[ code('67af97e263') ]]]

Además, abre `services.yaml`. En un tutorial anterior, añadí alguna configuración que desactivaba efectivamente la caché en el entorno `dev`. Elimínalo para que tengamos una caché adecuada.

Bien, detén el trabajador y reinícialo. La primera vez que hagamos esto, tendrá el mismo comportamiento que antes: esperar tres segundos y cuatro segundos, ya está.

Pero ahora, detén esto, espera unos segundos y observa lo que ocurre cuando reinicie. ¡Se pone al día! ¡Esos mensajes ocurrieron inmediatamente!

El estado lleva la cuenta de la última vez que el Programador comprobó si había mensajes. Y así, si tu trabajador se apaga durante un rato, cuando se reinicia, lee esa hora y la utiliza como hora de inicio para ponerse al día con todos los mensajes que se perdió.

Esto significa que puedes tener algunos mensajes que se ejecuten varias veces inmediatamente, pero no se perderá nada.

## Múltiples Trabajadores: Bloquea tu Programación

Ah, y si planeas tener múltiples trabajadores para tu transporte programador, también necesitarás añadir un bloqueo a la programación. Esto es fácil y está cubierto en los documentos: autocablea la fábrica de bloqueos, luego llama a `->lock()` para pasar un nuevo bloqueo. Esto asegurará que dos trabajadores no cojan el mismo mensaje recurrente al mismo tiempo y ambos lo procesen.

Muy bien equipo, ¡eso es todo lo que tengo! Gracias por esperar. Si tienes alguna pregunta sobre la actualización o te has encontrado con algún problema que no hayamos mencionado, estamos a tu disposición en los comentarios. Y avísanos si consigues una victoria: nos encanta escuchar éxitos.

Muy bien, amigos. ¡Hasta la próxima!
