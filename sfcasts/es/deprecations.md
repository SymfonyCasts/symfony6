# Encontrar y eliminar depreciaciones

El sistema de desaprobación de Symfony es un unicornio en Internet: no conozco nada que lo iguale. Es una de las cosas que hacen a Symfony tan especial y en la que se invierte mucho trabajo

## Cómo Symfony cambia / desaprueba funciones

Supongamos que en Symfony queremos cambiar algo: como el nombre de un método. No podemos simplemente cambiar el nombre del método, porque eso rompería tu código. En lugar de eso, añadimos el nuevo nombre del método, mantenemos el antiguo, pero añadimos una pequeña función de código de desaprobación en ese método antiguo. Lanzamos eso en una versión menor, como la 6.3 o la 6.4. Entonces, actualizas a esa versión y, como tu código está llamando al método antiguo, se encuentra con esa desaprobación, lo que desencadena una advertencia de desaprobación. Estas advertencias se recopilan, y podemos verlas de varias formas, como en la Barra de Herramientas de Depuración Web.

Tu trabajo consiste en leerlos y actualizar tu código para llamar al nuevo nombre de método. Una vez que hayan desaparecido todas las desaprobaciones, puedes actualizarte a Symfony 7.0 sin problemas. Porque, recuerda, la única diferencia entre Symfony 6.4 y Symfony 7.0 es que las rutas de código obsoletas han desaparecido. En nuestro ejemplo, significa que el antiguo nombre del método se elimina definitivamente en 7.0.

Me encanta este proceso. Significa que Symfony puede cambiar cosas y seguir modernizándose, y nosotros podemos actualizar nuestras aplicaciones de forma segura y predecible. Es lo mejor.

## A la caza de las desapropiaciones

Así que hoy, somos detectives de desaprobaciones: en una misión para cazarlas y eliminarlas. Para empezar, borraré manualmente mi directorio de caché

```terminal
rm -rf var/cache/*
```

para que cuando vayamos y actualicemos la página de inicio, se cree la caché. Algunas advertencias de desaprobación sólo se producen mientras se construye la caché. Vale, parece que nos quedan tres advertencias después de actualizar nuestras recetas. ¡Qué bien!

## Eliminar symfony/templating

Ábrelos. La primera está relacionada con una clase de ayuda para plantillas que está obsoleta. No es algo que recuerde haber usado en mi código. Mira la traza. No es muy útil. Aquí está la clase de ayuda... llamada por un cargador de clases.

Esto me dice que algo está intentando utilizar esta clase... y la clase está totalmente obsoleta. De hecho, todo este componente `symfony/templating` está obsoleto: ¡no existe en absoluto en Symfony 7.0! De todas formas, es muy probable que nunca lo hayas utilizado... y yo no lo voy a utilizar en mi aplicación. Entonces, ¿quién la utiliza?

Para averiguarlo, ve a la línea de comandos y ejecuta:

```terminal
composer why symfony/templating
```

Esto lo necesita `knplabs/knp-time-bundle`. Haz clic en el enlace para saltar a nuestra versión instalada: 1.20.0. Pero esa no es la última versión: ahora tiene una versión 2.2. ¡Vamos muy retrasados! La versión mayor 2.0 moderniza el código... y es muy probable que eso haya incluido la eliminación de la dependencia de plantillas. De hecho, lo vemos aquí abajo.

Se trata de una nueva versión mayor del paquete, por lo que debemos consultar el registro de cambios o las notas de la versión para comprobar si hay alguna interrupción de la compatibilidad con versiones anteriores que pueda afectarnos.

Lo interesante de esta primera desaprobación es que... no es algo que hayamos llamado directamente. Es una desaprobación indirecta: causada por una biblioteca que estamos utilizando. Y eso es bastante común. Para estar preparados para Symfony 7, tenemos que actualizar este bundle.

En composer.json, busca "time"... y cámbialo por el más reciente `^2.2`. Gira y ejecuta:

```terminal
composer up
```

Actualiza el paquete... ¡y elimina `symfony/templating`!

## DoctrineFixturesBundle Falsa Deprecación

Vale, vuelve a borrar la caché, cierra algunas pestañas y vamos a la página "examinar mezclas", porque eso conecta con la base de datos. Esta vez, vemos dos desaprobaciones. Ábrelas y sumérgete en ellas. La primera tiene algo que ver con los data fixtures. Si miramos la traza, no es superobvio, pero procede de DoctrineFixturesBundle. Esto es complicado: He tenido que bucear en el repositorio de GitHub de DoctrineFixturesBundle para encontrar una conversación. Esta vez, ¡la desaprobación es una falsa advertencia! La capa de desaprobación que se añadió al bundle no se hizo correctamente. El mantenedor confirma que está bien... así que cuando actualicemos a Symfony 7, las cosas no se romperán.

Se trata de una situación extraña, pero demuestra que cazar depreciaciones puede ser complicado

## Depreciaciones de Doctrine

La última deprecación es más larga y sigue un formato diferente. Hasta ahora, cada mensaje incluía el paquete en el que vivía la deprecación y la versión en la que se deprecaba. Pero aquí no vemos eso. Y, al final, hace referencia a una incidencia del repositorio `doctrine/orm`.

¡Ah! Esta deprecación no viene de Symfony: ¡viene de`doctrine/orm`! Esto nos dice que vamos a tener que hacer un cambio en nuestro código -en algún momento- antes de actualizar a la siguiente versión principal de ese paquete. Hoy sólo estamos centrados en actualizar Symfony, así que ésta también es una deprecación que podemos ignorar.

Así que... sí, creo que estamos bien. Nuestra aplicación es bastante pequeña, pero al hacer clic, las únicas desaprobaciones que veo son las que acabamos de ver.

## Registro de desaprobaciones en producción

Pero... ¿cómo podemos estar seguros de que no hay una página que olvidamos comprobar o un envío de formulario que desencadena una depreciación? La respuesta: el registro.

En `config/packages/monolog.yaml`, en la parte inferior, tenemos la configuración del registro de producción. El gestor principal es el gestor `nested`: éste registra los errores en producción. Los registra en stderr, o puedes cambiarlo a un archivo.

[[[ code('26b0347aeb') ]]]

La cuestión es: es de esperar que estés recogiendo los errores de producción en algún sitio. En la parte inferior, hay otro gestor llamado `deprecation`. Éste registra todos los avisos de desaprobación en el mismo lugar. Así que en tus registros de errores de producción, también deberías ver avisos de desaprobación.

[[[ code('479545d651') ]]]

Así que: arregla todas las imprecisiones que encuentres, despliega en producción, espera un día o dos y comprueba los registros para ver si hay imprecisiones. Una vez que no las haya, puedes pasar a Symfony 7.0. ¡Hagamos esa actualización a continuación!