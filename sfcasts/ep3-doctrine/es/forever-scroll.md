# Desplazamiento por siempre con Turbo Frames

¡Has llegado al último capítulo del tutorial de Doctrine! Este capítulo es... un bonus total. En lugar de hablar de Doctrine, vamos a aprovechar algo de JavaScript para convertir esta página en un "scroll eterno". Pero no te preocupes Hablaremos más de Doctrine en el próximo tutorial, cuando tratemos las Relaciones de Doctrine.

Éste es el objetivo: en lugar de enlaces de paginación, quiero que esta página cargue nueve resultados como los que vemos en la página 1. Luego, cuando nos desplacemos hasta el final, quiero hacer una petición AJAX para mostrar los nueve resultados siguientes, y así sucesivamente. El resultado es un "scroll eterno".

En el primer tutorial de esta serie, instalamos una biblioteca llamada Symfony UX Turbo, que habilitó un paquete de JavaScript llamado Turbo. Turbo convierte todos los clics de los enlaces y los envíos de formularios en llamadas AJAX, lo que nos proporciona una experiencia realmente agradable, similar a la de una aplicación de página única, sin hacer nada especial.

Aunque esto es genial, Turbo tiene otros dos superpoderes opcionales: Turbo Frames y Turbo Streams. Puedes aprender todo sobre ellos en nuestro tutorial de Turbo. Pero vamos a dar una muestra rápida de cómo podríamos aprovechar los Turbo Frames para añadir un desplazamiento eterno sin escribir una sola línea de JavaScript.

## conceptos básicos de los turbo-marcos

Los marcos funcionan dividiendo partes de tu página en elementos separados de `turbo-frame`, que actúa de forma muy parecida a un `iframe`... si eres lo suficientemente viejo como para recordar aquellos. Cuando rodeas algo en un `<turbo-frame>`, cualquier clic dentro de ese marco sólo navegará por ese marco.

Por ejemplo, abre la plantilla de esta página - `templates/vinyl/browse.html.twig` - y desplázate hasta donde tenemos nuestro bucle `for`. Añade un nuevo elemento `turbo-frame` justo aquí. La única regla de un turbo marco es que debe tener un ID único. Así que di`id="mix-browse-list"`, y luego ve hasta el final de esa fila y pega la etiqueta de cierre. Y, sólo por mi propia cordura, voy a aplicar una sangría a esa fila.

[[[ code('cc5da7cb54') ]]]

Bien, entonces... ¿qué hace eso? Si actualizas la página ahora, cualquier navegación dentro de este marco se queda dentro del marco. ¡Fíjate! Si hago clic en "2"... eso ha funcionado. Hizo una petición AJAX para la página 2, nuestra aplicación devolvió esa página HTML completa -incluyendo la cabecera, el pie de página y todo-, pero luego Turbo Frame encontró el `mix-browse-list``<turbo-frame>` correspondiente dentro de eso, cogió su contenido y lo puso aquí.

Y aunque no es fácil de ver en este ejemplo, la única parte de la página que está cambiando es este elemento `<turbo-frame>`. Si yo... digamos... me meto con el título aquí arriba en mi página, y luego hago clic aquí abajo y vuelvo a la página 2... eso no actualiza esa parte de la página. Una vez más, funciona de forma muy parecida a los iframes, pero sin las rarezas. Podrías imaginar que esto se utiliza, por ejemplo, para alimentar un botón "Editar" que añada edición en línea.

Pero en nuestra situación, esto no es muy útil todavía... porque funciona más o menos igual que antes: hacemos clic en el enlace, vemos nuevos resultados. La única diferencia es que al hacer clic dentro de un `<turbo-frame>` no se cambia la URL. Así que, independientemente de la página en la que me encuentre, si actualizo, soy transportado de nuevo a la página 1. Así que esto fue una especie de paso atrás

Pero sigue conmigo. Tengo una solución, pero implica unas cuantas piezas. Para empezar, voy a hacer que el ID sea único para la página actual. Añade un `-`, y entonces podremos decir `pager.currentPage`.

[[[ code('310c7b233c') ]]]

A continuación, en la parte inferior, elimina los enlaces de Pagerfanta y sustitúyelos por otro Marco Turbo. Di `{% if pager.hasNextPage %}`, y dentro de él, añade un`turbo-frame`, igual que arriba, con ese mismo `id="mix-browse-list-{{ }}"`. Pero esta vez, di `pager.nextPage`. Permíteme dividir esto en varias líneas aquí... y también vamos a decirle qué `src` debe utilizar para ello. Oh, déjame arreglar mi error tipográfico... y luego utiliza otro ayudante de Pagerfanta llamado `pagerfanta_page_url` y pásale ese `pager` y luego `pager.nextPage`. Por último, añade `loading="lazy"`.

[[[ code('9af460b3a9') ]]]

¡Woh! Deja que me explique, porque esto es un poco salvaje. En primer lugar, uno de los superpoderes de un `<turbo-frame>` es que puedes darle un atributo `src` y dejarlo vacío. Esto le dice a Turbo:

> ¡Oye! Voy a ser perezoso y empezar este elemento vacío... quizás porque es
> un poco pesado de cargar. Pero en cuanto este elemento sea visible para
> el usuario, haz una petición Ajax a esta URL para obtener su contenido.

Así, este `<turbo-frame>` comenzará vacío... pero en cuanto nos desplacemos hasta él, Turbo hará una petición AJAX para la siguiente página de resultados.

Por ejemplo, si este marco se está cargando para la página 2, la respuesta Ajax contendrá un `<turbo-frame>` con `id="mix-browse-list-2"`. El sistema Turbo Frame lo tomará de la respuesta Ajax y lo pondrá aquí, al final de nuestra lista. Y si hay una página 3, incluirá otro Turbo Frame aquí abajo que apuntará a la página 3.

Todo esto puede parecer un poco loco, así que vamos a probarlo. Voy a desplazarme hasta la parte superior de la página, refresco y... ¡perfecto! Ahora desplázate hacia abajo y observa. Deberías ver que aparece una petición AJAX en la barra de herramientas de depuración de la web. Mientras nos desplazamos... aquí abajo... ¡ah! ¡Ahí está la petición AJAX! Vuelve a desplazarte hacia abajo y... hay una segunda petición AJAX: una para la página 2 y otra para la página 3. Si seguimos desplazándonos, nos quedamos sin resultados y llegamos al final de la página.

Si eres nuevo en Turbo Frames, este concepto puede haber sido un poco confuso, pero puedes aprender más en nuestro tutorial de Turbo. Y un saludo a [una entrada del blog de AppSignal](https://blog.appsignal.com/2022/07/06/get-started-with-hotwire-in-your-ruby-on-rails-app) que introdujo esta genial idea.

¡Muy bien, equipo! ¡Enhorabuena por haber terminado el curso de Doctrine! Espero que te sientas poderoso. ¡Deberías estarlo! La única parte importante que le falta a Doctrine ahora es la de Relaciones de Doctrine: poder asociar una entidad a otra mediante relaciones, como las de muchos a uno y muchos a muchos. Cubriremos todo eso en el próximo tutorial. Hasta entonces, si tienes alguna duda o tienes una gran adivinanza que quieras plantearnos, estamos a tu disposición en la sección de comentarios. ¡Muchas gracias, amigos! ¡Y hasta la próxima vez!
