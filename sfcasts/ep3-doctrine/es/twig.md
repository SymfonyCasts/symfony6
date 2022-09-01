# Métodos de entidad personalizados y magia Twig

Nuestra entidad `VinylMix` tiene una propiedad entera `$votes`... pero no vamos a imprimirla en la página... todavía. Hagámoslo. En`templates/vinyl/browse.html.twig`, después de `createdAt`, añadimos un salto de línea e imprimimos `mix.votes`... (¡que incluso se autocompletó para nosotros!) Si pasamos por encima y refrescamos... ¡qué bien! Vemos los votos, que pueden ser positivos o negativos porque, por desgracia, ¡internet puede ser un lugar poco amigable!

## Los métodos de repositorio incorporados

Ahora mismo, estamos consultando la base de datos y los resultados vuelven en el orden que la base de datos quiera. ¿Podríamos ordenarlos por los votos más altos primero? Una opción es escribir una consulta personalizada dentro de `VinylMixRepository`, de la que aprenderemos pronto. Pero estas clases de repositorio tienen varios métodos que nos permiten, al menos, hacer algunas cosas básicas

Por ejemplo, podemos llamar a `findAll()`... o podríamos llamar a `find()` y pasarle un ID para encontrar un único `VinylMix`. Y hay otros, como`findOneBy()` o `findBy()`, en los que le pasas una matriz de criterios para utilizarlos en una cláusula WHERE. Por ejemplo, podríamos encontrar todas las mezclas DONDE el nombre es igual a algún valor.

Pero para esta situación, deja ese criterio vacío para que devuelva todo. ¿Por qué? Porque quiero aprovechar el segundo argumento: el "orden". Pasa un array con `'votes' => 'DESC'`.

[[[ code('e346ea1578') ]]]

Y ahora... ¡qué bien! ¡Los votos más altos son los primeros!

## Añadir un método de entidad personalizado

Vale, los votos pueden ser positivos o negativos. Para que eso sea súper obvio, quiero imprimir un signo más delante de los votos positivos. Podríamos hacerlo añadiendo algo de lógica en Twig. Pero recuerda que tenemos esta bonita clase de entidad Claro, ahora mismo sólo tiene métodos getter y setter. Pero podemos añadir nuestros propios métodos personalizados. Y esa es una gran manera de organizar tu código.

Compruébalo: crea una nueva función pública llamada, qué tal `getVotesString()`, que devolverá un 🥝. Estoy bromeando, devolverá un `string` por supuesto. Luego calcula el prefijo "+" o "-" con alguna lógica elegante que diga

> Si los votos son iguales a cero, no queremos ningún prefijo. Si los votos son mayores
> que cero, queremos un símbolo de más. Si no, queremos un símbolo de menos.

Y... déjame rodear toda esta segunda afirmación entre paréntesis. Esta es probablemente la línea de código más elegante que he escrito nunca... ¡lo que también significa que es la más confusa! Siéntete libre de dividir esto en varias líneas.

[[[ code('dc5b1303a1') ]]]

En la parte inferior, `return sprintf()` con `%s`, que será el prefijo, y`%d`, que será el recuento de votos. Pasa esto: `$prefix` y luego el valor absoluto de `$this->votes`... ya que estamos añadiendo el signo negativo manualmente.

[[[ code('c25b180e57') ]]]

Ahora podemos utilizar este bonito método en cualquier parte de nuestra aplicación... como desde dentro de una plantilla con `mix.getVotesString()`. O acortarlo con `mix.votesString`.

[[[ code('bb847889bf') ]]]

Twig es lo suficientemente inteligente como para darse cuenta de que `votesString` no es una propiedad real... pero que existe un método `getVotesString()`. Piensa en esto como una propiedad virtual dentro de Twig.

Si volvemos a volar y refrescamos... ¡impresionante! Obtenemos los signos menos y más.

## ¡Un segundo método de entidad personalizado!

Ya que estamos aquí, las imágenes rotas -causadas por el sitio de marcador de posición que estoy utilizando- son... ¡un poco molestas! ¡Es hora de arreglarlas!

En una aplicación real, probablemente dejaremos que nuestros usuarios suban imágenes reales... aunque por ahora, nos quedaremos con imágenes ficticias. Pero en cualquier caso, probablemente necesitaremos la capacidad de obtener la URL de la imagen de una mezcla de vinilo desde varios lugares de nuestro código. Para facilitarlo y mantener el código centralizado, ¡añadamos otro método de entidad!

¿Qué te parece `public function getImageUrl()`. Dale un argumento `$width` para que podamos pedir diferentes tamaños. Dentro pegaré algo de código que utiliza un servicio diferente para las imágenes ficticias. Esto parece un poco rebuscado, pero sólo intento utilizar el id para obtener una imagen predecible, pero aleatoria... saltándome las 50 primeras, que son todas casi idénticas en este sitio.

[[[ code('e77ea4b80b') ]]]

En cualquier caso, ¡ahora tenemos este bonito método reutilizable!

De vuelta a la plantilla... aquí arriba es donde tengo la URL de la imagen codificada. Sustitúyelo por `mix.imageUrl()`, pero esta vez sí que tenemos que pasar un argumento. Pasa`300`... y actualicemos también el atributo `alt` a `Mix album cover`.

[[[ code('53ce836c2f') ]]]

Si pasamos y refrescamos... encantado. ¡Nuestras mezclas tienen imágenes!

## Limpieza: Borrar el antiguo repositorio

Bien, una última cosa de limpieza. Ya no necesitamos este servicio `MixRepository`, que carga las mezclas desde GitHub. Vamos a borrarlo para no confundirnos... ya que su nombre es tan parecido al nuevo `VinylMixRepository`. Haz clic con el botón derecho del ratón en `MixRepository.php`, ve a "Refactorizar", y haz clic en "Eliminar de forma segura".

¡Es fácil! Pero... puede que todavía lo estemos usando en algún sitio, ¿no? Si vas a tu terminal y ejecutas

```terminal
git grep MixRepository
```

eso te mostrará dónde se sigue mencionando.

Aunque, el contenedor de servicios de Symfony es tan inteligente, que a menudo nos dirá si hemos metido la pata, como por ejemplo si seguimos usando un servicio que no existe. Observa. Prueba a actualizar cualquier página. ¡Sí!

> No se puede autoconectar el servicio `App\Command\TalkToMeCommand`: argumento
> `$mixRepository` del método `__construct()` tiene el tipo `App\Service\MixRepository`.

Aunque esta página ni siquiera utiliza la clase `TalkToMeCommand`, se ha dado cuenta de que hay un problema con ella. Ábrelo: `src/Command/TalkToMeCommand.php`. ¡Sí! Estábamos utilizando `MixRepository`... para poder llamar a su método `findAll()`. Cambia eso por utilizar `VinylMixRepository`... y entonces podremos eliminar la declaración `use` de la parte superior. El `VinylMixRepository` sigue teniendo un método `findAll()`, así que seguirá funcionando. Esta no es una forma muy eficiente de encontrar una mezcla aleatoria, pero es suficiente por ahora.

[[[ code('5c17368962') ]]]

Bien, cierra esa clase y vuelve a actualizarla. ¡El contenedor de servicio ha encontrado otro punto problemático en `VinylController`! Dirígete allí y... arriba en el constructor... ¡sí! Aquí también estamos autocableando. Pero... ya no estamos utilizando la propiedad, así que elimínala. Elimina también su declaración `use` y un par de otras declaraciones `use`que ya no se utilizan.

[[[ code('78a32d8a81') ]]]

Y ahora... ¡el sitio funciona de nuevo!

A continuación, vamos a aprender a crear consultas personalizadas mediante el constructor de consultas
