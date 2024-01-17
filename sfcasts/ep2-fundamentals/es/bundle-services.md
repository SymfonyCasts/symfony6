# Encontrar y Utilizar los Servicios de un Bundle

Acabamos de instalar KnpTimeBundle. ¡Hurra! Pero... eh... ¿qué significa eso? ¿Qué nos da eso?

Lo primero que nos da un bundle son... ¡servicios! ¿Qué servicios nos da este bundle? Bueno, podríamos, por supuesto, leer la documentación, bla, bla. Bueno, vale, deberías hacerlo... pero, ¡vamos! ¡Aventurémonos con temeridad y aprendamos explorando!

En el último tutorial, conocimos un comando que nos muestra todos los servicios de nuestra aplicación: `debug:autowiring`:

```terminal-silent
php bin/console debug:autowiring
```

Por ejemplo, si buscamos "logger", parece que hay un servicio llamado`LoggerInterface`. También aprendimos que podemos autoconectar cualquier servicio de esta lista en nuestro controlador utilizando su tipo. Usando este tipo `LoggerInterface` -que en realidad es `Psr\Log\LoggerInterface` - Symfony sabe que debe pasarnos este servicio. Luego, aquí abajo, llamamos a métodos sobre él como `$logger->info()`.

Hemos instalado `KnpTimeBundle` hace un momento, así que busquemos "tiempo":

```terminal-silent
php bin/console debug:autowiring time
```

Y... ¡eh! ¡Mira esto! ¡Tenemos un nuevo servicio `DateTimeFormatter`! Es del nuevo bundle y seguro que es lo que buscamos. Vamos a utilizarlo en nuestro controlador.

## Utilizar el nuevo servicio DateTimeFormatter

La pista de tipo que necesitamos es `Knp\Bundle\TimeBundle\DateTimeFormatter`. De acuerdo En`VinylController`, busca `browse()`, y añade el nuevo argumento.

Por cierto, el orden de los argumentos no importa... excepto cuando se trata de argumentos opcionales. He hecho que el argumento `$slug` sea opcional y normalmente necesitas tus argumentos opcionales al final de la lista. Así que añadiré `DateTimeFormatter`justo aquí y pulsaré "tab" para añadir la declaración `use` en la parte superior.

Podemos nombrar el argumento como queramos, como `$sherlockHolmes` o`$timeFormatter`:

[[[ code('616397799e') ]]]

Para usar esto, haz un bucle sobre las mezclas - `foreach ($mixes as $key => $mix)`

[[[ code('7da483ab67') ]]]

luego, en cada una, añade una nueva clave `ago`: `$mixes[$key]['ago'] =`... y aquí es donde necesitamos el nuevo servicio. ¿Cómo utilizamos el `DateTimeFormatter`? ¡No tengo ni idea! Pero hemos utilizado su tipo, así que PhpStorm debería decirnos qué métodos tiene. Escribe`$timeFormatter->`... y ¡bien! Tiene 4 métodos públicos.

El que queremos es `formatDiff()`. Pásale el tiempo "desde"... que es`$mix['createdAt']`:

[[[ code('85dac5f608') ]]]

¡Eso es todo lo que necesitamos! Estamos haciendo un bucle sobre estos `$mixes`, tomando la clave `createdAt`, que es un objeto `DateTime`, pasándolo al método `formatDiff()`, que debería devolver una cadena con el formato "ago". Para ver si esto funciona, a continuación,`dd($mixes)`:

[[[ code('ec67d50981') ]]]

¡Vamos a probarlo! Gira, refresca... y abramos. ¡Sí! Mira esto: `"ago"
=> "7 months ago"`... `"ago" => "18 days ago"`... Funciona. Así que elimina ese volcado:

[[[ code('07e24f0499') ]]]

Y ahora que cada mezcla tiene un nuevo campo `ago`, en `browse.html.twig`, sustituye el código`mix.createdAt|date` por `mix.ago`:

[[[ code('47f88f8537') ]]]

Y ahora... mucho mejor.

Así que: teníamos un problema... y sabíamos que había que resolverlo con un servicio... porque los servicios sí funcionan. Todavía no teníamos un servicio que hiciera lo que necesitábamos, así que salimos, encontramos uno y lo instalamos. ¡Problema resuelto! El propio Symfony tiene un montón de paquetes diferentes, y cada uno de ellos nos proporciona varios servicios. Pero a veces necesitarás un bundle de terceros como éste para hacer el trabajo. Normalmente, puedes buscar en Internet el problema que intentas resolver, más "Symfony bundle", para encontrarlo.

## Utilizar el filtro Twig de hace años

Además del bonito servicio `DateTimeFormatter` que acabamos de utilizar, este bundle también nos proporcionó otro servicio. Pero no es un servicio que debamos utilizar directamente, como en el controlador. No Este servicio está destinado a ser utilizado por el propio Twig... ¡para alimentar un nuevo filtro Twig! ¡Así es! Puedes añadir funciones personalizadas, filtros... o cualquier cosa a Twig.

Para ver el nuevo filtro, vamos a probar otro útil comando de depuración:

```terminal
php bin/console debug:twig
```

Esto imprime una lista de todas las funciones, filtros y pruebas de Twig, junto con la única variable global de Twig que tenemos. Si subes a Filtros, ¡hay uno nuevo llamado "hace"! Eso no estaba allí antes de que instaláramos `KnpTimeBundle`.

Así que todo el trabajo que hicimos en nuestro controlador está perfectamente bien ... pero resulta que hay una manera más fácil de hacer todo esto. Elimina el `foreach`... elimina el servicio `DateTimeFormatter`... y, aunque es opcional, limpia la declaración extra `use` de la parte superior:

[[[ code('c1f3df9471') ]]]

En `browse.html.twig`, ya no tenemos un campo `ago`... pero seguimos teniendo un campo`createdAt`. En lugar de canalizarlo en el filtro `date`, canalízalo en `ago`:

[[[ code('754b969c0e') ]]]

¡Eso es todo lo que necesitamos! Volvemos a la actualización del sitio y... obtenemos exactamente el mismo resultado.

Por cierto, no lo haremos en este tutorial, pero al final, podrás seguir fácilmente la documentación para crear tus propias funciones y filtros Twig personalizados.

Vale, nuestra aplicación aún no tiene una base de datos... y no la tendrá hasta el próximo episodio. Pero para hacer las cosas más interesantes, vamos a obtener los datos de nuestras mezclas haciendo una llamada HTTP a un repositorio especial de GitHub. Eso a continuación.