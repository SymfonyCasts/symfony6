# debug:container y cómo funciona el autowiring

Vale, he mentido. Antes de hablar de los entornos, tengo que confesar algo: no te he enseñado todos los servicios de Symfony. Ni de lejos.

Dirígete a tu terminal y ejecuta nuestro comando favorito:

```terminal
php bin/console debug:autowiring
```

Sabemos que todos estos servicios están flotando en Symfony, esperando que los pidamos. Y sabemos que los bundles nos dan servicios. El servicio Twig de aquí abajo proviene de TwigBundle.

Y como cada servicio es un objeto, algo en algún lugar debe ser responsable de instanciar estos objetos. La pregunta es: "¿Quién?" Y la respuesta es... ¡el contenedor de servicios!

## Hola contenedor de servicios

Resulta que todos los servicios no están realmente... "flotando": todos viven dentro de algo llamado "contenedor". Y hay muchos más servicios en el contenedor de los que nos ha contado `debug:autowiring`. Ooh... ¡secretos! Esta vez, ejecuta:

```terminal
php bin/console debug:container
```

Y... ¡guau! Esto imprime una lista enorme. Es tan grande que es difícil verlo todo. Déjame hacer la fuente más pequeña. ¡Mucho mejor!

Esta es la lista completa de todos los servicios de nuestra aplicación... o del "contenedor". El contenedor es básicamente un "array" gigante donde cada servicio tiene un nombre único que apunta a su objeto de servicio. Por ejemplo, aquí abajo... ahí vamos... podemos ver que hay un servicio cuyo nombre único - o "id" es `twig`.

Saber que el id del servicio Twig es `twig` no suele ser importante, pero es útil entender que cada servicio tiene un id único... y que puedes verlos todos dentro del comando `debug:container`.

## El contenedor crea objetos

En realidad, el contenedor podría describirse mejor como un gran conjunto de instrucciones sobre cómo instanciar servicios, siempre y cuando algo los pida. Por ejemplo, el contenedor sabe exactamente cómo instanciar este servicio Twig. Sabe que su clase es `Twig\Environment`. Y aunque no lo veas en esta lista, conoce los argumentos exactos que debe pasar a su constructor. En el momento en que alguien necesite el servicio Twig, el contenedor lo instanciará y lo devolverá.

Sí, cuando autocontratamos un servicio, básicamente estamos diciendo

> Oye, contenedor, ¿puedes darme el servicio Cliente HTTP?

Si nada en nuestro código ha pedido aún ese servicio durante esta petición, el contenedor lo creará. Pero si algo ya lo ha pedido, entonces el contenedor simplemente devolverá el que ya ha creado. Esto significa que si pedimos el servicio Cliente HTTP en diez lugares diferentes, el contenedor sólo creará y devolverá la misma instancia. ¡Muy bonito!

## Cómo funciona el autocableado

De todos modos, `debug:container` nos muestra todos los servicios que el contenedor sabe instanciar. Pero `debug:autowiring` sólo nos muestra una parte de esos servicios. ¿Por qué?

Bueno, resulta que no todos los servicios son autocableables. Muchos de los elementos de esta lista son servicios de bajo nivel que sólo existen para ayudar a otros servicios a hacer su trabajo. Probablemente nunca necesitarás utilizar estos servicios de bajo nivel directamente... y en realidad no puedes obtenerlos mediante autoconexión.

Pero, retrocedamos un momento. Ahora que sabemos un poco más, podemos saber exactamente cómo funciona el sistema de autoconexión de Symfony. Es maravillosamente sencillo.

Como hemos visto, el contenedor es realmente un array donde cada servicio tiene un id que apunta a ese objeto servicio. Cuando Symfony ve este tipo `HttpClientInterface`-este es el tipo completo que ve, gracias a nuestra declaración `use` - para averiguar qué servicio del contenedor debe pasarnos, simplemente busca un servicio cuyo ID coincida exactamente con esta cadena. ¡Deja que te lo enseñe!

Desplázate hacia la parte superior de la lista para encontrar... ¡un servicio cuyo ID es`Symfony\Contracts\HttpClient\HttpClientInterface`! La gran mayoría de los servicios del contenedor utilizan la estrategia de nomenclatura "caso serpiente". Pero si un servicio está pensado para que lo utilicemos en nuestro código, Symfony añadirá dentro un servicio adicional que coincida con su nombre de clase o interfaz.

Gracias a ello, cuando escribimos `HttpClientInterface`, Symfony busca en el contenedor un servicio cuyo id es`Symfony\Contracts\HttpClient\HttpClientInterface`, lo encuentra y nos lo pasa.

## Alias de servicio

Pero fíjate en el lado derecho: dice que se trata de un alias para un ID de servicio diferente. Un "alias" es como un enlace simbólico. Significa que cuando alguien pregunte por el servicio `HttpClientInterface`, Symfony nos pasará en realidad este otro servicio.

Podemos utilizar la misma lógica aquí abajo para el tipo `CacheInterface`. Si comprobamos la lista, aquí está el servicio cuyo id coincide con ese tipo. Pero, en realidad, es sólo un alias de un servicio llamado `cache.app`. Así que cuando autoconectamos `CacheInterface`, el servicio `cache.app` es lo que realmente se nos pasa.

Si te sientes inseguro, aquí tienes las tres grandes conclusiones. Uno: hay un montón de objetos de servicio flotando por ahí y todos viven dentro de algo llamado "contenedor". Cada servicio tiene un identificador único.

Dos: sólo un pequeño porcentaje de ellos nos resulta útil... y esos están configurados para que podamos autocablearlos. El autocableado funciona buscando en el contenedor un servicio cuyo id coincida exactamente con el tipo. Cuando ejecutamos `debug:autowiring`, básicamente nos muestra los servicios de esta lista cuyo id es un nombre de clase o interfaz. Ésos son los "servicios autoconducibles".

La tercera y última conclusión es que los servicios también tienen un sistema de alias... lo que significa que cuando pedimos el servicio `CacheInterface`, lo que realmente nos dará es el servicio cuyo id es `cache.app`.

Si te preguntas cómo podríamos utilizar en nuestro código un servicio no autoconvocable, ¡es una gran pregunta! Es algo raro, pero aprenderemos a hacerlo más adelante.

A continuación, vamos a hablar de la utilización de una configuración diferente a nivel local y a nivel de producción. Vamos a hablar de los entornos.
