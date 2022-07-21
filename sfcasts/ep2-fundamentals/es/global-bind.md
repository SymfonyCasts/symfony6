# Vincular argumentos globalmente

En la práctica, rara vez tienes que hacer algo dentro de `services.yaml`. La mayoría de las veces, cuando añades un argumento al constructor de un servicio, es autoconductible. Así que añades el argumento, le das una pista de tipo... ¡y sigues codificando!

Pero el argumento de `$isDebug` no es autoconductible... ya que no es un servicio. Y eso nos obligó a anular por completo el servicio para poder especificar ese único argumento con `bind`. Funciona, pero... ¡eso era... mucho teclear para hacer una cosa tan pequeña!
# Mover el bind a `_defaults`

Así que aquí tienes una solución diferente. Copia esa tecla `bind`, borra el servicio por completo, y arriba, bajo `_defaults`, pega.

Cuando nos movemos y probamos esto... ¡la página sigue funcionando! ¿No es genial? Y tiene sentido. Esta sección registrará automáticamente `MixRepository` como servicio... y entonces todo lo que esté bajo `_defaults` se aplicará a ese servicio. Así que el resultado final es exactamente el que teníamos antes.

¡Me encanta hacer esto! Me permite establecer convenciones para todo el proyecto. Ahora que tenemos esto, podemos añadir un argumento `$isDebug` al constructor de cualquier servicio y funcionará al instante.

## Vinculación con Type_hints

Por cierto, si quieres, también puedes incluir el tipo con el bind.

Así, ahora sólo funcionaría si utilizamos la sugerencia de tipo `bool` con el argumento. Si utilizáramos `string`, por ejemplo, Symfony no intentaría pasar ese valor.

## El atributo Autowire

Así que el bind global es genial. Pero a partir de Symfony 6.1, hay otra forma de especificar un argumento no autoconductible. Comenta el global `bind`. Todavía me gusta hacer esto... pero probemos la nueva forma.

Si actualizamos ahora, obtendremos un error porque Symfony no sabe qué pasar al argumento `$isDebug`. Para solucionarlo, entra en `MixRepository` y, por encima del argumento (o antes del argumento si no estás usando varias líneas), añade un atributo de PHP 8 llamado `Autowire`. Normalmente, los atributos de PHP 8 se autocompletan, pero este no se autocompleta para mí. Esto se debe a un error en PhpStorm. Para evitarlo, voy a escribir `Autowire`... luego iré a la parte superior y empezaré a añadir la declaración `use`para esto manualmente, lo que sí nos da una opción de autocompletar. Pulsa "tab" y... ¡tah dah! Si quieres hacerlos por orden alfabético, puedes moverlo.

También puedes observar que está subrayado con un mensaje

> No se puede aplicar el atributo a una propiedad [...]

De nuevo, PhpStorm está un poco confundido porque esto es tanto una propiedad como un argumento.

De todos modos, sigue adelante y pasa esto como un argumento `%kernel.debug%`.

Actualiza ahora y... ¡lo tienes! Bastante bien, ¿verdad?

Siguiente: la mayoría de las veces, cuando se autoconduce un argumento como `HttpClientInterface`, sólo hay un servicio en el contenedor que implementa esa interfaz. Pero, ¿y si hubiera varios clientes HTTP en nuestro contenedor? ¿Cómo podríamos elegir el que queremos? Es hora de hablar del autocableado con nombre.
