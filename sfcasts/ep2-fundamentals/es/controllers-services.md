# ¡Los controladores también son servicios!

Abre `src/Controller/VinylController.php`. ¡Puede que sea obvio o no, pero nuestras clases de controlador también son servicios en el contenedor! ¡Sí! Se sienten especiales porque son controladores... pero en realidad sólo son viejos y aburridos servicios como todo lo demás. Bueno, excepto que tienen un superpoder que no tiene nada más: la capacidad de autoconectar argumentos en sus métodos de acción. Normalmente, el autocableado sólo funciona con el constructor.

## Vinculación de los argumentos de la acción

Los métodos de acción funcionan realmente como los constructores en lo que respecta a la autoconexión. Por ejemplo, añade un argumento `bool $isDebug` a la acción `browse()`... y a continuación `dump($isDebug)`:

[[[ code('d77a8d195f') ]]]

Y eso... ¡no funciona! Hasta ahora, las únicas dos cosas que sabemos que podemos tener como argumentos de nuestras "acciones" son (A), cualquier comodín en la ruta como`$slug` y (B) los servicios autocableables, como `MixRepository`.

Pero ahora, vuelve a `config/services.yaml` y descomenta el global `bind` de antes:

[[[ code('78858f9ac6') ]]]

Esta vez... ¡funciona!

## Añadir un constructor

Yendo en la otra dirección, como los controladores son servicios, puedes absolutamente tener un constructor si quieres. Movamos `MixRepository` y `$isDebug` a un nuevo constructor. Cópialos, quítalos... añade `public function __construct()`, pégalos... y luego los pondré en sus propias líneas. Para convertirlos en propiedades, añade`private` delante de cada uno:

[[[ code('4c824ee41c') ]]]

De nuevo abajo, sólo tenemos que asegurarnos de cambiar a `dump($this->isDebug)` y añadir `$this->` delante de `mixRepository`:

[[[ code('672b0fa2a8') ]]]

¡Bien! Si probamos esto ahora... ¡funciona bien!

Normalmente no sigo este enfoque... principalmente porque añadir argumentos al método de acción es muy fácil. Pero si necesitas un servicio u otro valor en cada método de acción de tu clase, definitivamente puedes limpiar tu lista de argumentos inyectándola a través del constructor. Voy a eliminar ese `dump()`.

A continuación, vamos a hablar de las variables de entorno y de la finalidad del archivo `.env` que hemos visto antes. Estas cosas serán cada vez más importantes a medida que hagamos nuestra aplicación más y más realista.
