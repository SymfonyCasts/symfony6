# El generador de consultas

La página `/browse` funciona... ¿pero qué pasa si hacemos clic en uno de estos géneros? Bueno... eso funciona más o menos. Muestra el nombre del género... pero obtenemos una lista de todas las mezclas. Lo que realmente queremos es filtrarlas para que sólo se muestren las mezclas de ese género concreto.

Ahora mismo, todas las mezclas de la base de datos están en el género "Pop". Vuelve a`MixController` y encuentra el método falso que crea nuevas mezclas para que podamos hacer algunos datos ficticios más interesantes. Añade una variable `$genres` con "Pop" y "Rock" incluidos... Luego selecciona una al azar con `$genres[array_rand($genres)]`.

[[[ code('54c99ad8bd') ]]]

¡Genial! Ahora ve a `/mix/new` y actualiza unas cuantas veces... hasta que tengamos unas 15 mezclas. Volvemos a `/browse`... ¡yup! Tenemos una mezcla de géneros "Rock" y "Pop"... sólo que aún no se filtran.

Así que nuestra misión está clara: personalizar la consulta de la base de datos para que sólo devuelva los resultados de un género concreto. Bien, en realidad podemos hacerlo de forma súper sencilla en `VinylController`a través del método `findBy()`. El género está en la URL como el comodín `$slug`.

Así que podríamos añadir una sentencia "if" en la que, si hay un género, devolvamos todos los resultados en los que `genre` coincida con `$slug`. Pero esta es una gran oportunidad para aprender a crear una consulta personalizada. Así que vamos a deshacerlo.

## Método del repositorio personalizado

La mejor manera de hacer una consulta personalizada, es crear un nuevo método en el repositorio para la entidad de la que quieras obtener datos. En este caso, eso significa`VinylMixRepository`. Esto contiene algunos métodos de ejemplo. Descomenta el primero... y empieza de forma sencilla 

[[[ code('e4d05ab545') ]]]

Llámalo `findAllOrderedByVotes()`. No nos preocuparemos todavía del género: Sólo quiero hacer una consulta que devuelva todas las mezclas ordenadas por votos. Quitando el argumento, esto devolverá un array y el PHPdoc anterior ayuda a mi editor a saber que será un array de objetos `VinylMix` 

[[[ code('fb27ae3d1b') ]]]

## DQL y el QueryBuilder

Hay varias formas de ejecutar una consulta personalizada en Doctrine. Doctrine, por supuesto, acaba realizando consultas SQL. Pero Doctrine trabaja con MySQL, Postgres y otros motores de bases de datos... y el SQL necesario para cada uno de ellos es ligeramente diferente.

Para manejar esto, internamente, Doctrine tiene su propio lenguaje de consulta llamado Doctrine Query Language o "DQL", Tiene un aspecto similar a

> SELECT v FROM App\Entity\VinylMix v WHERE v.genre = 'pop';

Puedes escribir estas cadenas a mano, pero yo aprovecho el "QueryBuilder" de Doctrine: un bonito objeto que ayuda... ya sabes... ¡a construir esa consulta!

## Crear el QueryBuilder

Para utilizarlo, empieza con `$this->createQueryBuilder()` y pasa un alias que se utilizará para identificar esta clase dentro de la consulta. Hazlo corto, pero único entre tus entidades - algo como `mix`.

[[[ code('d129b30819') ]]]

Como estamos llamando a esto desde dentro de `VinylMixRepository`, el QueryBuilder ya sabe que hay que consultar desde la entidad `VinylMix`... y utilizará `mix` como alias. Si ejecutáramos este query builder ahora mismo, sería básicamente:

> SELECT * FROM vinyl_mix AS mix

El constructor de consultas está cargado de métodos para controlar la consulta. Por ejemplo, llama a `->orderBy()` y pasa a `mix` -ya que es nuestro alias- `.votes` y luego`DESC`.

[[[ code('1cb0df88e1') ]]]

Ya está Ahora que nuestra consulta está construida, para ejecutarla llama a `->getQuery()` (que la convierte en un objeto `Query` ) y luego a `->getResult()`.

[[[ code('ce9df562c0') ]]]

Bueno, en realidad, hay varios métodos a los que puedes llamar para obtener los resultados. Los dos principales son `getResult()` -que devuelve una matriz de los objetos coincidentes- o `getOneOrNullResult()`, que es el que utilizarías si estuvieras consultando un `VinylMix` específico o nulo. Como queremos devolver una matriz de mezclas coincidentes, utiliza `getResult()`.

Ahora podemos utilizar este método. En `VinylController` (déjame cerrar `MixController`...), en lugar de `findBy()`, llama a `findAllOrderedByVotes()`.

[[[ code('b6870c6a31') ]]]

Me encanta lo claro que es este método: hace que sea súper obvio lo que estamos consultando exactamente. Y cuando lo probamos... ¡sigue funcionando! Todavía no está filtrando, pero el orden es correcto.

## Añadir la sentencia WHERE

Bien, volvamos a nuestro nuevo método. Añade un argumento opcional `string $genre = null`. Si se pasa un género, tenemos que añadir una sentencia "where". Para hacer espacio para ello, divide esto en varias líneas... y sustituye `return` por `$queryBuilder =`. A continuación, `return $queryBuilder` por `->getQuery()`, y `->getResult()`.

[[[ code('4d59046ba3') ]]]

Ahora podemos decir `if ($genre)`, y añadir la declaración "where". ¿Cómo? Apuesto a que puedes adivinar: `$queryBuilder->andWhere()`.

Pero una advertencia. También hay un método `where()`... pero yo nunca lo uso. Cuando llames a `where()`, borrará cualquier sentencia "where" existente que pueda tener el constructor de consultas... por lo que podrías eliminar accidentalmente algo que hayas añadido antes. Por tanto, utiliza siempre `andWhere()`. Doctrine es lo suficientemente inteligente como para darse cuenta de que, al ser el primer WHERE, no necesita añadir el`AND`.

Dentro de `andWhere()`, pasa `mix.genre =`... pero no pongas el género dinámico justo en la cadena. Eso es un gran no-no: nunca lo hagas. Eso te abre a los ataques de inyección SQL. En su lugar, siempre que necesites poner un valor dinámico en una consulta, utiliza una "sentencia preparada"... que es una forma elegante de decir que pones un marcador de posición aquí, como `:genre`. El nombre de esto puede ser cualquier cosa... como "dinosaurio" si quieres. Pero lo llames como lo llames, luego rellenarás el marcador de posición diciendo `->setParameter()` con el nombre del parámetro -así que`genre` - y luego el valor: `$genre`.

[[[ code('d847ca105b') ]]]

¡Qué bonito! De nuevo en `VinylController`, pasa `$slug` como género.

¡Vamos a probar esto! Vuelve a la página de navegación primero. ¡Genial! Obtenemos todos los resultados. Ahora haz clic en "Rock" y... ¡bien! Menos resultados y todos los géneros muestran "Rock"! Si filtro por "Pop"... ¡lo tengo! Incluso podemos ver la consulta para esto... aquí está. Tiene la sentencia "where" para el género igual a "Pop". ¡Guau!

## Reutilización de la lógica del generador de consultas

A medida que tu proyecto se hace más y más grande, vas a crear más y más métodos en tu repositorio para las consultas personalizadas. Y puede que empieces a repetir la misma lógica de consulta una y otra vez. Por ejemplo, podríamos ordenar por los votos en un montón de métodos diferentes de esta clase.

Para evitar la duplicación, podemos aislar esa lógica en un método privado. ¡Compruébalo! Añade `private function addOrderByVotesQueryBuilder()`. Esto aceptará un argumento`QueryBuilder` (queremos el de `Doctrine\ORM`), pero hagámoslo opcional. Y también devolveremos un `QueryBuilder`.

[[[ code('0b62ed3f29') ]]]

El trabajo de este método es añadir esta línea `->orderBy()`. Y por comodidad, si no pasamos un `$queryBuilder`, crearemos uno nuevo.

Para permitirlo, empieza con`$queryBuilder = $queryBuilder ?? $this->createQueryBuilder('mix')`. Vuelvo a utilizar a propósito `mix` para el alias. Para simplificar la vida, elige un alias para una entidad y utilízalo sistemáticamente en todas partes.

[[[ code('64c4b20a01') ]]]

En cualquier caso, esta línea puede parecer extraña, pero básicamente dice

> Si existe un QueryBuilder, utilízalo. Si no, crea uno nuevo.

Debajo de `return $queryBuilder`... ve a robar la lógica de `->orderBy()` de aquí arriba y... pega. ¡Impresionante!

[[[ code('642635ef7b') ]]]

PhpStorm está un poco enfadado conmigo... pero eso es sólo porque está teniendo una mañana dura y necesita reiniciarse: nuestro código está, esperemos, bien.

Vuelve al método original, simplifica a`$queryBuilder = $this->addOrderByVotesQueryBuilder()` y no le pases nada.

[[[ code('7e289b4487') ]]]

¿No es bonito? Cuando actualizamos... ¡no está roto! ¡Toma ese PhpStorm!

A continuación, vamos a añadir una página de "espectáculo de mezclas" en la que podamos ver una única mezcla de vinilos. Por primera vez, consultaremos un único objeto de la base de datos y nos ocuparemos de lo que ocurre si no se encuentra ninguna mezcla que coincida.
