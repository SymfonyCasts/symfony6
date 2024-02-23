# MapQueryParameter y carga útil de la petición

Las siguientes novedades de las que quiero hablar están relacionadas con la obtención de datos de la petición. Normalmente es un trabajo un poco aburrido. Pero las nuevas funciones son bastante chulas.
# El atributo MapQueryParameter

Por ejemplo, añade un `?query=banana` a la URL. Para obtenerlo en nuestro controlador, históricamente escribíamos un argumento con `Request` y lo cogíamos de ahí. Y aunque eso sigue funcionando, ahora podemos añadir un argumento `?string $query`. Para decirle a Symfony que esto es algo que debe obtener de un parámetro de consulta, añade un atributo delante: `#[MapQueryParameter]`.

Ya está Vuelca `$query` para probar que funciona.

[[[ code('c2c8e330d4') ]]]

De vuelta al mundo del navegador web, actualiza. En la barra de herramientas de depuración web... ¡ya está!

## Validación a partir de la sugerencia de tipo

El atributo también tiene algunas opciones. Por ejemplo, si tu parámetro de consulta se llama de forma diferente a tu argumento, podrías ponerlo aquí.

Y más allá de coger el valor de la petición, este sistema también realiza la validación. Observa: duplica esto y añade un argumento `int $page = 1`. Ah, y quería que el argumento `$query` fuera opcional para que no tenga que estar en la URL. A continuación, vuelca `$page`.

[[[ code('c12ac0cbc0') ]]]

Vale, si añadimos `?page=3` a la URL... no hay sorpresa: vuelca `3`. Pero está bien que obtengamos un entero 3: no una cadena. Ahora prueba con `page=banana`. Un 404! El sistema ve que tenemos un tipo `int` y realiza la validación.

## La función filter_var()

Todo este sistema es manejado por algo llamado `QueryParameterValueResolver`. Así que si realmente quieres profundizar, comprueba esa clase. Internamente, utiliza una función PHP llamada `filter_var()` para realizar la validación. No es una función con la que esté muy familiarizado, pero es bastante potente. Le pasas un valor, uno o varios filtros... y te dice si ese valor satisface esos filtros. También puedes pasarle opciones para controlar los filtros.

Si no haces nada extra, el sistema lee nuestra sugerencia de tipo `int`, y pasa un filtro a `filter_var()` que exige que sea un `int`. Por eso falla.

## Validar que un int está en un rango

Pero podemos ir más allá. Añade un argumento llamado `$limit` que por defecto sea 10. Vuelca esto abajo. Pero quiero que el límite esté entre 1 y 10. Para forzarlo, pasa dos opciones especiales a `filter_var`: `min_range` puesto a 1 y `max_range` puesto a 10.

[[[ code('d9e7d8e079') ]]]

¡Vamos a probarlo! Digamos `?limit=3`. Funciona como esperamos. Pero cuando probamos `limit=13`.`filter_var()` falla y ¡obtenemos un 404! ¡Me encanta!

## Obtener parámetros de consulta de matrices

Esto se puede utilizar incluso para manejar matrices. Copia y crea un argumento más: una matriz de `$filters` que por defecto sea una matriz vacía. Vuelca eso.

[[[ code('1a83b6b427') ]]]

En el navegador, añade `?filters[]` igual a plátano, `&filters[]` igual a manzana. ¡Comprueba ese array en la barra de herramientas de depuración web! También funciona para matrices asociativas: añade`foo` y `bar` entre `[]`. ¡Sí! Una matriz asociativa.

Es una función muy bien diseñada para obtener parámetros de consulta.

## Cuerpo de la petición

Además, si necesitas obtener el cuerpo de una petición, en Symfony 6.3 hay un nuevo método llamado `$request->getPayload()`. ¿Construyendo una API? Cuando tu cliente envíe JSON en el cuerpo, utiliza `$request->getPayload()` para descodificarlo en una matriz asociativa. ¡Eso está muy bien! Pero además, si tu usuario envía un formulario HTML normal, `$request->getPayload()` también funciona en ese caso. Detecta que se está enviando un formulario HTML y descodifica los datos de `$_POST` en una matriz. Así que no importa si estás utilizando una API o un formulario normal, tenemos un método uniforme para obtener la carga útil de la petición. Pequeño, pero bonito.

## MapRequestPayload

Hablando de JSON, también es habitual utilizar el serializador para deserializar la carga útil en un objeto. Esto está relacionado con otra nueva función llamada`#[MapRequestPayload]`.

En este caso, `__invoke` es la acción del controlador. Esto dice: toma el JSON de la petición y deserialízalo en un `ProductReviewDto`, que es la clase de ejemplo anterior. Después de enviar el JSON a través del serializador, incluso realiza la validación. Así que otra característica bien pensada.

Vale, ¡ya está bien de cosas de peticiones! A continuación, vamos a probar una nueva función de la 6.4: la posibilidad de perfilar comandos de consola.
