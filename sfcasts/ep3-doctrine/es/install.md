# Instalación de Doctrine

¡Bienvenido de nuevo, equipo, al tercer episodio de nuestra serie Symfony 6! Los dos primeros cursos han sido súper importantes: nos han llevado desde los fundamentos hasta el núcleo de cómo funciona todo en Symfony: todo lo bueno de los "servicios" y la configuración. Ahora estás preparado para utilizar cualquier otra parte de Symfony y empezar a construir realmente un sitio.

Y... ¿qué mejor manera de hacerlo que añadir una base de datos? Porque... hasta ahora, a pesar de todas las cosas geniales que hemos hecho, el sitio que hemos estado construyendo es 100% estático ¡Aburrido! Es hora de cambiar eso.

## Hola Doctrine

Sabemos que Symfony es una colección de un montón de bibliotecas para resolver un montón de problemas diferentes. Entonces... ¿tiene Symfony algunas herramientas para ayudarnos a hablar con la base de datos? La respuesta es... ¡no! Porque... ¡no tiene que hacerlo!

¿Por qué? Entra Doctrine: la biblioteca más potente del mundo PHP para trabajar con bases de datos. Y Symfony y Doctrine funcionan muy bien juntos: son los Frodo y Sam Gamgee de la Tierra Media de PHP: los Han Solo y Chewbacca de la Alianza Rebelde de PHP. Symfony y Doctrine son como dos personajes de Disney que se acaban los bocadillos el uno al otro

## Configuración del proyecto

Para ver este dúo dinámico en acción, vamos a configurar nuestro proyecto. Jugar con las bases de datos es divertido, ¡así que codifica conmigo! Hazlo descargando el código del curso desde esta página. Tras descomprimirlo, encontrarás un directorio `start/` con el mismo código que ves aquí. Abre este archivo `README.MD` para obtener todas las instrucciones de configuración.

El último paso será abrir un terminal, entrar en tu proyecto y ejecutar:

```terminal
symfony serve -d
```

Esto utiliza el binario de Symfony para iniciar un servidor web local que vive en https://127.0.0.1:8000. Tomaré el camino más perezoso y haré clic en eso para ver... ¡Vinilo mezclado! Nuestra última idea de startup -y te juro que va a ser enorme- combina la nostalgia de las "cintas de mezcla" de los años 80 y 90 con la experiencia de audio de los discos de vinilo. Tú creas tus dulces cintas de mezcla, y nosotros las prensamos en un disco de vinilo para obtener una experiencia de audio totalmente hipster.

Hasta ahora, nuestro sitio tiene una página de inicio y una página para navegar por las mezclas que han creado otras personas. Sin embargo, esa página no es realmente dinámica: se extrae de un repositorio de GitHub... y a menos que hayas configurado una clave API como hicimos en el último episodio, ¡esta página está rota! Eso es lo primero que vamos a arreglar: consultar una base de datos para las mezclas.

## Instalar Doctrine

¡Así que vamos a instalar Doctrine! Busca tu terminal y ejecútalo:

```terminal
composer require "doctrine:^2.2"
```

Esto es, por supuesto, un alias de Flex para una biblioteca llamada `symfony/orm-pack`. Y recuerda: un "paquete" es una especie de "biblioteca falsa" que sirve como atajo para instalar varios paquetes a la vez. En este caso, estamos instalando el propio Doctrine, pero también algunas otras bibliotecas relacionadas, como el excelente sistema Doctrine Migrations.

## Configuración de Docker

Ah, y ¡mira esto! El comando pregunta:

> ¿Quieres incluir la configuración Docker de las recetas?

Así, ocasionalmente, cuando instales un paquete, la receta de ese paquete contendrá la configuración de Docker que puede, por ejemplo, iniciar un contenedor de base de datos. Esto es totalmente opcional, pero voy a decir `p` por sí mismo permanentemente. Hablaremos más sobre la configuración Docker en unos minutos.

## Las recetas de Doctrine

Pero ahora mismo, vamos a comprobar lo que hizo la receta. Ejecuta:

```terminal
git status
```

Muy bien: esto modificó los archivos normales como `composer.json`, `composer.lock` y`symfony.lock`... y también modificó `config/bundles.php`. Si lo compruebas... no es ninguna sorpresa: nuestra aplicación tiene ahora dos nuevos bundles: DoctrineBundle y DoctrineMigrationsBundle.

Pero probablemente la parte más importante de la receta es el cambio que se ha realizado en nuestro archivo`.env`. Recuerda: aquí es donde podemos configurar las variables de entorno... y la receta nos dio una nueva llamada `DATABASE_URL`. Ésta, como puedes ver, contiene todos los detalles de la conexión, como el nombre de usuario y la contraseña.

¿Qué utiliza esta variable de entorno? ¡Excelente pregunta! Mira el nuevo archivo que nos dio la receta: `config/packages/doctrine.yaml`. La mayor parte de esta configuración no tendrás que pensarla ni cambiarla. Pero fíjate en esta clave `url`: ¡lee la variable de entorno `DATABASE_URL`!

La cuestión es: la variable de entorno `DATABASE_URL` es la clave para configurar tu aplicación para que hable con una base de datos... y jugaremos con ella en unos minutos.

La receta también ha añadido unos cuantos directorios nuevos: `migrations/` `src/Entity/` y`src/Repository/`. Ahora mismo, aparte de un archivo `.gitignore` sin sentido, están todos vacíos. Pronto empezaremos a llenarlos.

Bien: Doctrine ya está instalado. Pero para hablar de una base de datos... tenemos que asegurarnos de que tenemos una base de datos en funcionamiento y que la variable de entorno `DATABASE_URL` apunta a ella. Hagamos eso a continuación, pero con un giro opcional y delicioso: vamos a utilizar Docker para iniciar la base de datos.
