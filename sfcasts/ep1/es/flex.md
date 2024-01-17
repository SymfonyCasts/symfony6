# Symfony Flex: Aliases, Paquetes y Recetas

Symfony es un conjunto de librerías que nos proporciona toneladas de herramientas: herramientas para registrar, hacer consultas a la base de datos, enviar correos electrónicos, renderizar plantillas y hacer llamadas a la API, por nombrar algunas. Si las cuentas, como hice yo, Symfony consta de unas 100 bibliotecas distintas. ¡Vaya!

Ahora quiero empezar a convertir nuestras páginas en verdaderas páginas HTML... en lugar de devolver sólo texto. Pero no vamos a meter un montón de HTML en nuestras clases de PHP, qué asco. En su lugar, vamos a renderizar una plantilla.

## La filosofía de Symfony de empezar poco a poco e instalar funciones

Pero, ¿adivina qué? ¡No hay ninguna biblioteca de plantillas en nuestro proyecto! ¿Qué? Pero yo creía que acababas de decir que Symfony tiene una herramienta para renderizar plantillas!? ¡Mentira!

Bueno... Symfony sí tiene una herramienta para eso. Pero nuestra aplicación utiliza actualmente muy pocas de las bibliotecas de Symfony. Las herramientas que tenemos hasta ahora no suponen mucho más que un sistema de ruta-controlador-respuesta. Si necesitas renderizar una plantilla o hacer una consulta a la base de datos, no tenemos esas herramientas instaladas en nuestra app... todavía.

De hecho, me encanta esto de Symfony. En lugar de empezar con un proyecto gigantesco, con todo lo que necesitamos, más toneladas de cosas que no necesitamos, Symfony empieza de forma diminuta. Luego, si necesitas algo, lo instalas

Pero antes de instalar una biblioteca de plantillas, en tu terminal, ejecuta

```terminal
git status
```

Vamos a confirmar todo:

```terminal
git add .
```

Puedo ejecutar con seguridad `git add .` -que añade todo lo que hay en mi directorio a git- porque uno de los archivos con los que venía nuestro proyecto originalmente era un archivo `.gitignore`, que ya ignora cosas como el directorio `vendor/`, el directorio `var/` y varias otras rutas. Si te preguntas qué son estas cosas raras de los marcadores, está relacionado con el sistema de recetas, del que vamos a hablar.

En cualquier caso, ejecuta `git commit` y añade un mensaje:

```terminal-silent
git commit -m "route -> controller -> response -> profit"
```

¡Perfecto! Y ahora, estamos limpios.

## Instalar una biblioteca de plantillas (Twig)

Bien, ¿cómo podemos instalar una biblioteca de plantillas? ¿Y qué bibliotecas de plantillas están disponibles para Symfony? ¿Y cuál es la recomendada? Bueno, por supuesto, una buena manera de responder a estas preguntas sería consultar la documentación de Symfony.

Pero también podemos simplemente... ¡adivinar! En cualquier proyecto PHP, puedes añadir nuevas bibliotecas de terceros a tu aplicación diciendo "composer require" y luego el nombre del paquete. Todavía no sabemos el nombre del paquete que necesitamos, así que simplemente lo adivinaremos:

```terminal
composer require templates
```

Ahora bien, si has utilizado Composer antes, puede que ahora mismo estés gritando a tu pantalla ¿Por qué? Porque en Composer, los nombres de los paquetes son siempre `something/something`. No es posible, literalmente, tener un paquete llamado simplemente `templates`.

Pero mira: cuando ejecutamos esto, ¡funciona! Y arriba dice que está usando la versión 1 para `symfony/twig-pack`. Twig es el nombre del motor de plantillas de Symfony.

## Alias de Flex

Para entender esto, vamos a dar un pequeño paso atrás. Nuestro proyecto comenzó con un archivo`composer.json` que contiene varias bibliotecas de Symfony. Una de ellas se llama`symfony/flex`. Flex es un plugin de Composer. En realidad, añade tres superpoderes a Composer.

***TIP
El servidor flex.symfony.com se cerró a favor de un nuevo sistema. ¡Pero aún puede ver una lista de todas las recetas 
disponibles en ¡ https://bit.ly/flex-recipes!
***

El primero, que acabamos de ver, se llama aliases de Flex. Dirígete a https://flex.symfony.com para ver una página gigante llena de paquetes. Busca "plantillas". Aquí está. En `symfony/twig-pack`, dice Aliases: template, templates, twig y twig-pack.

La idea que hay detrás de los alias de Flex es muy sencilla. Escribimos`composer require templates`. Y luego, internamente, Flex lo cambia por`symfony/twig-pack`. En última instancia, ése es el paquete que Composer instala.

Esto significa que, la mayoría de las veces, puedes simplemente "composer require" lo que quieras, como `composer require logger`, `composer require orm`, `composer require icecream`, lo que sea. Es sólo un sistema de acceso directo. Lo importante es que, lo que realmente se instaló fue `symfony/twig-pack`.

## Paquetes Flex

Y eso significa que, en nuestro archivo `composer.json`, deberíamos ver ahora`symfony/twig-pack` bajo la clave `require`. Pero si te das la vuelta, ¡no está ahí! ¡Gracias! En su lugar, ha añadido `symfony/twig-bundle`, `twig/extra-bundle`, y `twig/twig`.

Estamos asistiendo al segundo superpoder de Symfony Flex: desempaquetar paquetes. Copiamos el nombre del paquete original y... podemos encontrar ese repositorio en GitHub entrando en https://github.com/symfony/twig-pack.

Y... sólo contiene un archivo: `composer.json`. Y esto requiere otros tres paquetes: los tres que acabamos de ver añadidos a nuestro proyecto.

Esto se llama paquete Symfony. Es... realmente un paquete falso que nos ayuda a instalar otros paquetes. Resulta que, si quieres añadir un motor de plantillas rico a tu aplicación, es recomendable instalar estos tres paquetes. Pero en lugar de hacer que los añadas manualmente, puedes hacer que Composer requiera `symfony/twig-pack` y los obtenga automáticamente. Cuando instalas un "paquete", como éste, Flex lo "desempaqueta" automáticamente: encuentra los tres paquetes de los que depende el paquete y los añade a tu archivo `composer.json`.

Así pues, los paquetes son un atajo para que puedas ejecutar un comando de `composer require` y conseguir que se añadan varias bibliotecas a tu proyecto.

Bien, ¿cuál es el tercer y último superpoder de Flex? Me alegro de que lo preguntes Para averiguarlo, en tu terminal, ejecuta

```terminal
git status
```

## Recetas de Flex

Vaya. Normalmente, cuando ejecutas `composer require`, los únicos archivos que debería modificar -además de descargar paquetes en `vendor/` - son `composer.json` y`composer.lock`. El tercer superpoder de Flex es su sistema de recetas.

Siempre que instales un paquete, ese paquete puede tener una receta. Si la tiene, además de descargar el paquete en el directorio `vendor/`, Flex también ejecutará su receta. Las recetas pueden hacer cosas como añadir nuevos archivos o incluso modificar algunos archivos existentes.

Observa: si nos desplazamos un poco hacia arriba, ah sí: dice "configurando 2 recetas". Así que aparentemente había una receta para `symfony/twig-bundle` y también una receta para`twig/extra-bundle`. Y estas recetas aparentemente actualizaron el archivo `config/bundles.php`y añadieron un nuevo directorio y archivo.

El sistema de recetas es genial. Todo lo que tenemos que hacer es que Composer requiera una nueva biblioteca y su receta añadirá todos los archivos de configuración u otra configuración necesaria para que podamos empezar a usar esa biblioteca inmediatamente Se acabó el seguir 5 pasos de "instalación" manual en un README. Cuando añades una biblioteca, funciona de forma inmediata.

A continuación: Quiero profundizar un poco más en las recetas. Por ejemplo, ¿dónde viven? ¿Cuál es su color favorito? ¿Y qué ha añadido esta receta específicamente a nuestra aplicación y por qué? También voy a contarte un pequeño secreto: todos los archivos de nuestro proyecto -todos los archivos de `config/`, el directorio `public/`... todas estas cosas- se añadieron mediante una receta. Y lo demostraré.
