# Servicios no autoconvocables

Ejecuta:

```terminal
php bin/console debug:container
```

Y... Haré esto un poco más pequeño para que todo aparezca en una sola línea. Como sabemos, este comando muestra todos los servicios de nuestro contenedor... pero sólo un pequeño número de ellos son autoconectables. Lo sabemos porque un servicio es autoconectable sólo si su ID, que es esto de aquí, es un nombre de clase o de interfaz.

Así que, al principio, podría parecer que el servicio Twig no es autoconectable. Después de todo, su ID - `twig` - no es en absoluto una clase o interfaz. Pero si te desplazas hasta la parte superior... veamos... ¡sí! Hay otro servicio en el contenedor cuyo ID es `Twig\Environment`, que es un alias del servicio `twig`. Este es un pequeño truco que hace Symfony para hacer que los servicios sean autoconducibles. Si escribimos un argumento con `Twig\Environment`, obtendremos el servicio `twig`.

Sin embargo, la mayoría de los servicios de esta lista no tienen un alias como ese. Por tanto, no son autoconectables. Y, por lo general, eso está bien. Si un servicio no es autoconectable, probablemente sea porque nunca necesitarás utilizarlo. Pero supongamos que sí queremos utilizar uno de ellos.

Fíjate en éste Se llama `twig.command.debug`. Abre otra pestaña. Antes hemos ejecutado:

```terminal
php bin/console debug:twig
```

Esto nos muestra todas las funciones y filtros de Twig... ¡lo que está muy bien! Bueno, ¡sorpresa! ¡Este comando viene del servicio `twig.command.debug`! Porque "todo en Symfony lo hace un servicio", incluso los comandos de la consola.

Como reto, veamos si podemos inyectar este servicio en `MixRepository`, ejecutarlo y volcar su salida.

## Inyección de dependencia: Añadiendo el nuevo argumento

Lo primero es lo primero. En `MixRepository`, acabamos de descubrir que, para hacer nuestro trabajo, necesitamos acceder a otro servicio. ¿Qué hacemos? La respuesta: Inyección de dependencia, que es esa elegante palabra para añadir otro argumento de construcción y fijarlo en una propiedad, lo que podemos hacer de una vez con`private $twigDebugCommand`:

[[[ code('db9765d0f6') ]]]

Si nos detenemos ahora mismo y refrescamos... ¡no hay sorpresa! Obtendríamos un error. Symfony no tiene ni idea de qué pasar para ese argumento.

¿Y si añadimos el tipo de esta clase? De vuelta a nuestro terminal, podemos ver que este servicio es una instancia de `DebugCommand`. Por aquí, vamos a añadir ese tipo -consejo: `DebugCommand`... queremos el de `Symfony\Bridge\Twig\Command`. Pulsa "tab" para autocompletarlo:

[[[ code('1d003f5f47') ]]]

Y luego... actualiza. ¡Sigue habiendo un error! Vale, deberíamos añadir la sugerencia de tipo porque somos buenos programadores. Pero... por mucho que lo intentemos, esto no es un servicio autocompletable. Entonces, ¿cómo lo arreglamos?

## Vinculando el argumento en YAML

Hay dos formas principales. Primero te mostraré la forma antigua, que es la que más hago porque la verás en la documentación y en las entradas del blog por todas partes. En`config/services.yaml`, al igual que hicimos antes para el argumento `$isDebug`, anula nuestro servicio por completo. Digamos `App\Service\MixRepository`, y añadimos una clave`bind`. Entonces, vamos a insinuar lo que hay que pasar al argumento `$twigDebugCommand`.

Lo único complicado es averiguar qué valor hay que poner. Por ejemplo, si voy y copio el ID del servicio - `twig.command.debug` - y lo pego aquí... ¡eso no va a funcionar! Eso va a pasar literalmente por esa cadena. Si refrescas, ¡sí!

> El argumento 4 debe ser del tipo `DebugCommand`, cadena dada.

Tenemos que decirle a Symfony que pase el servicio que tiene este ID. En estos archivos YAML, hay una sintaxis especial para hacer precisamente eso: anteponer al ID del servicio el símbolo`@`:

[[[ code('447697206a') ]]]

En cuanto lo hagamos... ¡el hecho de que esto no explote significa que está funcionando!

## El atributo Autowire

Pero... vamos a eliminar esto. Porque quiero mostrarte la nueva forma de hacerlo... que aprovecha ese mismo atributo `Autowire`.

Aquí arriba, digamos `#[Autowire()]`, pero en lugar de pasar sólo una cadena, digamos`service: 'twig.command.debug'`:

[[[ code('b2b20613e3') ]]]

## Usando el nuevo Argumento

¡Me encanta! Antes de probar esto, vamos a utilizar realmente el servicio. Dirígete a`findAll()`. Ejecutar un comando de consola manualmente en tu código PHP es totalmente posible. Es un poco raro, ¡pero genial! Tenemos que crear un objeto`$output = new BufferedOutput()`... luego podemos ejecutar el comando diciendo`$this->twigDebugCommand->run(new ArrayInput())` - esto es, más o menos, fingir los argumentos de la línea de comandos - pasarle un `[]` vacío - luego `$output`. Lo que produzca el comando se fijará en ese objeto.

Para ver si funciona, basta con `dd($output)`:

[[[ code('d77a8d195f') ]]]

¡Tiempo de prueba! Refresca... ¡y lo tienes! Qué divertido es esto

Muy bien, ahora que esto funciona, vamos a comentar esta tontería. Mantendré nuestro`$twigDebugCommand` inyectado sólo como referencia.

La conclusión clave es la siguiente: la mayoría de los argumentos a los servicios serán autoconvocables. Pero cuando encuentres un argumento que no sea autoconductible, puedes utilizar el atributo `Autowire`para apuntar al valor o servicio que necesitas.

Siguiente: ¿Recuerdas cuando te dije que `MixRepository` fue el primer servicio que creamos? Pues... Mentí. ¡Resulta que nuestros controladores han sido servicios todo este tiempo!