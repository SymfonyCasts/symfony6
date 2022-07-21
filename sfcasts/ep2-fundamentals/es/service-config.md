# Configuración manual del servicio en services.yaml

En tu terminal, ejecuta:

```terminal
bin/console debug:container --parameters
```

Uno de los parámetros de `kernel` se llama `kernel.debug`. Además de los entornos, Symfony tiene este concepto de "modo de depuración". Es verdadero para el entorno `dev` y falso para `prod`. Y, de vez en cuando, ¡es muy útil!

He aquí nuestro nuevo reto (sobre todo para ver si podemos hacerlo). Dentro de`MixRepository`, quiero averiguar si estamos en modo de depuración. Si el modo de depuración es verdadero, guardaremos el caché durante 5 segundos. Si es falso, quiero almacenar en caché durante 60 segundos

## ¡Inyección de dependencia!

Retrocedamos un momento. Supón que estás trabajando dentro de un servicio como`MixRepository`. De repente te das cuenta de que necesitas algún otro servicio como el logger. ¿Qué haces para conseguir el registrador? La respuesta: haces el baile de la inyección de dependencia. Añades un argumento y una propiedad de `private LoggerInterface $logger`... y luego lo utilizas en tu código. Harás esto muchas veces en Symfony.

Permíteme deshacer eso... porque en realidad no necesitamos el registrador ahora mismo. Pero lo que sí necesitamos es algo parecido. Ahora mismo estamos dentro de un servicio y de repente nos hemos dado cuenta de que necesitamos alguna configuración (la bandera `kernel.debug` ) para hacer nuestro trabajo. ¿Qué hacemos para conseguir esa configuración? Lo mismo Añadirla como argumento a nuestro constructor. Digamos `private bool $isDebug`, y aquí abajo, úsalo: si `$this->isDebug`, caché durante 5 segundos, si no, caché durante 60 segundos.

## Argumentos no autoinstalables

Pero... hay una pequeña complicación... y seguro que ya sabes cuál es. Cuando refrescamos la página... ¡vaya! Nos sale un error en `Cannot resolve argument`. Si saltas un poco, dice

> No se puede autoconectar el servicio `App\Service\MixRepository`: el argumento `$isDebug` del
> método `__construct()` es de tipo `bool`, debes configurar su
> valor explícitamente.

Eso tiene sentido. El autocableado sólo funciona para los servicios. No puedes tener un argumento bool`$isDebug` y esperar que Symfony se dé cuenta de alguna manera de que queremos el parámetro`kernel.debug`. Puede que sea un mago, pero no tengo un hechizo para eso. Sin embargo, puedo hacer desaparecer un trozo de pastel entero. Con magia. Sin duda.

## Configurar MixRepository en services.yaml

¿Cómo solucionamos esto? Abre un archivo que aún no hayamos mirado:`config/services.yaml`. Hasta ahora, no hemos necesitado añadir ninguna configuración para nuestro servicio`MixRepository`. El contenedor vio la clase `MixRepository` en cuanto la creamos... y el autocableado ayudó al contenedor a saber qué argumentos pasar al constructor. Pero ahora que tenemos un argumento no autocable, tenemos que dar una pista al contenedor. Y eso lo hacemos en este archivo.

Dirígete a la parte inferior y añade el espacio de nombres completo de esta clase:`App\Service\MixRepository`. Debajo de eso, utiliza la palabra `bind`. Y debajo de eso, dale al contenedor una pista para indicarle qué debe pasar al argumento diciendo`$isDebug` set to `%kernel.debug%`

Estoy utilizando `$isDebug` a propósito. Eso tiene que coincidir exactamente con el nombre del argumento en la clase. Gracias a esto, el contenedor pasará el valor del parámetro`kernel.debug`.

Y cuando lo probamos... ¡funciona! Los dos argumentos del servicio siguen estando autocableados, pero hemos rellenado el único argumento que faltaba para que el contenedor pueda instanciar nuestro servicio. ¡Muy bien!

Quiero hablar más del propósito de este archivo y de toda la configuración aquí arriba. Resulta que mucha de la magia que hemos estado viendo relacionada con los servicios y el autocableado se puede explicar con este código. Eso a continuación.
