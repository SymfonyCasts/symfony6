# Stimulus: Un JavaScript Sensato y Bonito

Quiero hablar de Stimulus. Stimulus es una pequeña pero encantadora biblioteca de JavaScript que me encanta. Y Symfony tiene un soporte de primera clase para ella. También es muy utilizada por la comunidad de Ruby on Rails.

## SPA vs. Aplicaciones "tradicionales"

Hay dos filosofías en el desarrollo web. La primera es que devuelves el HTML de tu sitio, como hemos hecho en nuestra página de inicio y de navegación, y luego añades el comportamiento de JavaScript a ese HTML. La segunda filosofía es utilizar un marco de trabajo de JavaScript para construir todo tu HTML y JavaScript, lo que supone una aplicación de una sola página.

La solución correcta depende de tu aplicación, pero a mí me gusta mucho el primer enfoque. Y utilizando Stimulus -así como otra herramienta de la que hablaremos en unos minutos llamada Turbo- podemos crear aplicaciones altamente interactivas que se ven y se sienten tan responsivas como una aplicación de una sola página.

Tenemos un tutorial completo sobre Stimulus, pero vamos a probarlo. Ya puedes ver cómo funciona en el ejemplo de su documentación. Creas una pequeña clase JavaScript llamada controlador... y luego adjuntas ese controlador a uno o más elementos de la página. Y ya está Stimulus te permite adjuntar escuchas de eventos -como eventos de clic- y tiene otras cosas buenas.

## Controladores Stimulus en nuestra aplicación

En nuestra aplicación, cuando instalamos Encore, nos dio un directorio `controllers/`. Aquí es donde vivirán nuestros controladores Stimulus. Y en `app.js`, importamos`bootstrap.js`. No es un archivo que tengas que mirar mucho, pero es súper útil. Esto pone en marcha Stimulus -sí, ya está instalado- y registra todo lo que hay en el directorio `controllers/` como un controlador Stimulus. Esto significa que si quieres crear un nuevo controlador Stimulus, ¡sólo tienes que añadir un archivo a este directorio `controllers/`!

Y obtenemos un controlador de Estímulos fuera de la caja llamado `hello_controller.js`. Todos los controladores de Estímulos siguen la práctica de nombrar algo con "guión bajo"`controller.js` o algo con guión `controller.js`. La parte que precede a `_controller` -por tanto, `hello` - se convierte en el nombre del controlador.

## Adjuntar un controlador a un elemento

Adjuntemos esto a un elemento. Abre `templates/vinyl/homepage.html.twig`. Veamos... en la parte principal de la página, voy a añadir un div... y luego para adjuntar el controlador a este elemento, añade `data-controller="hello"`.

[[[ code('a818d762b0') ]]]

¡Vamos a probarlo! Actualiza y... ¡sí! ¡Ha funcionado! El estímulo ha visto este elemento, ha instanciado el controlador... y luego nuestro código ha cambiado el contenido del elemento. El elemento al que está unido este controlador está disponible como `this.element`.

## ¡El estímulo ve dinámicamente nuevos elementos!

Así que... esto ya es muy bonito... porque conseguimos trabajar dentro de un objeto JavaScript ordenado... que está ligado a un elemento específico.

Pero déjame mostrarte la parte más genial de Stimulus: lo que hace que cambie el juego. Inspecciona el elemento en las herramientas de tu navegador cerca del elemento. Voy a modificar el HTML del elemento padre. Justo encima de éste -aunque no importa dónde- añade otro elemento con `data-controller="hello"`.

Y... ¡boom! ¡Vemos el mensaje! Esta es la característica estrella de Stimulus: puedes añadir estos elementos `data-controller` a la página cuando quieras. Por ejemplo, si haces una llamada Ajax... que añade HTML fresco a tu página, Stimulus se dará cuenta de ello y ejecutará los controladores a los que el nuevo HTML deba estar unido. Si alguna vez has tenido problemas en los que has añadido HTML a tu página mediante Ajax... pero el JavaScript de ese nuevo HTML está roto porque le faltan algunos escuchadores de eventos, pues Stimulus acaba de resolverlo.

## La función stimulus_controller ()

Cuando usas Stimulus dentro de Symfony, obtenemos unas cuantas funciones de ayuda para hacernos la vida más fácil. Así, en lugar de escribir `data-controller="hello"` a mano, podemos decir`{{ stimulus_controller('hello') }}`.

[[[ code('108098d4bf') ]]]

Pero eso es sólo un atajo para renderizar ese atributo exactamente igual que antes.

Bien, ahora que tenemos lo básico de Stimulus, vamos a utilizarlo para hacer algo real, como hacer una petición Ajax cuando hagamos clic en este icono de reproducción. Eso es lo siguiente.
