# Hola Symfony

Bienvenido. Hola. Hola, mi nombre es Ryan y tengo el absoluto placer de presentarte el hermoso y fascinante y productivo mundo de Symfony 6. En serio, me siento como Willie Wonka invitándote a mi fábrica de chocolate, excepto que, con suerte, con menos lesiones relacionadas con el azúcar. De todos modos, si eres nuevo en Symfony, estoy... ¡sinceramente un poco celoso! Te va a encantar el viaje... y espero que te conviertas en un desarrollador aún mejor por el camino: definitivamente vas a construir cosas geniales.

La salsa secreta de Symfony es que empieza siendo diminuto, lo que hace que sea fácil de aprender. Pero luego, amplía sus características automáticamente a través de un sistema de recetas único. En Symfony 6, esas características incluyen nuevas herramientas de JavaScript y un nuevo sistema de seguridad... sólo por nombrar dos de las muchas cosas nuevas.

Symfony también es rápido como un rayo, con un gran enfoque en la creación de una experiencia alegre para el desarrollador, pero sin sacrificar las mejores prácticas de programación. Sí: consigues amar la codificación y amar tu código. Lo sé... ha sonado cursi, pero es cierto.

Así que ven conmigo y estarás en un mundo de pura elucidación.

Es la primera vez que canto en estos tutoriales... y quizá la última. Empecemos.

## Instalar el binario "symfony

Dirígete a https://symfony.com/download. En esta página, encontrarás algunas instrucciones -que variarán en función de tu sistema operativo- sobre cómo descargar algo llamado el binario de Symfony.

Esto... no es realmente Symfony. Es sólo una herramienta de línea de comandos que nos ayudará a iniciar nuevos proyectos Symfony y nos dará algunas buenas herramientas de desarrollo local. Es opcional, pero lo recomiendo encarecidamente

Una vez que hayas instalado esto - yo ya lo he hecho - abre tu aplicación de terminal favorita. Yo estoy usando iTerm para mac, pero no importa. Si lo has configurado todo correctamente, deberías poder ejecutarlo:

```terminal
symfony
```

O incluso mejor

```terminal
symfony list
```

para ver una lista de todas las "cosas" que puede hacer este binario de symfony. Hay muchas cosas aquí: cosas que ayudan al desarrollo "local"... y también algunos servicios opcionales para el despliegue. Vamos a repasar las cosas que necesitas saber a lo largo del camino.

## ¡Iniciemos una aplicación Symfony!

Bien, queremos iniciar una nueva y brillante aplicación Symfony. Para ello, ejecuta:

```terminal
symfony new mixed_vinyl
```

Donde "mixed_vinyl" es el directorio en el que se descargará la nueva app. Se trata de nuestro proyecto secreto para combinar la mejor parte de los años 90 -no, no el Internet de acceso telefónico, hablo de las cintas de mezcla- con el deleite auditivo de los discos. Más adelante hablaremos de ello.

Entre bastidores, este comando utiliza Composer -el gestor de paquetes de PHP- para crear el nuevo proyecto. Más adelante hablaremos de ello.

El resultado final es que podemos pasar a nuestro nuevo directorio `mixed_vinyl`. Abre esta carpeta en tu editor favorito. Yo estoy usando PhpStorm y lo recomiendo encarecidamente.

 Conociendo nuestro nuevo Proyecto

¿Qué ha hecho ese comando `symfony new`? Ha arrancado un nuevo proyecto Symfony! Ooh. Y ya tenemos un repositorio git. Ejecuta:

```terminal
git status
```

Sí: en la rama principal, nada que confirmar. Prueba:

```terminal
git log
```

Genial. Después de descargar el nuevo proyecto, el comando confirmó todos los archivos originales automáticamente... lo cual fue muy agradable. Aunque me gustaría que el primer mensaje de confirmación fuera un poco más rockero.

¡Lo que realmente quiero mostrarte es que nuestro nuevo proyecto es súper pequeño! Prueba este comando:

```terminal
git show --name-only
```

¡Sí! Todo nuestro proyecto es... unos 17 archivos. Y aprenderemos sobre todos ellos a lo largo del camino. Pero quiero que te sientas cómodo: no hay mucho código aquí.

Vamos a añadir funciones poco a poco. Pero si quieres empezar con un proyecto más grande y con más funciones, puedes hacerlo ejecutando el comando `symfony new` con `--webapp`.

***TIP
Si quieres una nueva aplicación Symfony con todas las funciones, echa un vistazo a https://github.com/dunglas/symfony-docker
***

## Comprobación de los requisitos del sistema

Antes de saltar a la codificación, vamos a asegurarnos de que nuestro sistema está listo. Ejecuta otro comando del binario de symfony:

```terminal
symfony check:req
```

¡Parece que está bien! Si a tu instalación de PHP le falta alguna extensión... o hay algún otro problema... como que tu ordenador es en realidad una tetera, esto te lo hará saber.

## Iniciar el servidor web de desarrollo

Entonces: tenemos una nueva aplicación Symfony aquí... ¡y nuestro sistema está listo! Todo lo que necesitamos ahora es un subwoofer. Es decir, ¡un servidor web! Puedes configurar un servidor web real como Nginx o algo moderno como Caddy. Pero para el desarrollo local, el binario de Symfony puede ayudarnos. Corre:

```terminal
symfony serve -d
```

Y... ¡tenemos un servidor web funcionando! ¡Vuelve!

La primera vez que ejecutes esto, es posible que te pida que ejecutes otro comando para configurar un certificado SSL, lo cual está bien porque entonces el servidor soporta https.

¡Momento de la verdad! Copia la URL, gira hacia tu navegador, aguanta la respiración y ¡woo! Hola página de bienvenida de Symfony 6... completa con extravagantes cambios de color cada vez que recargamos.

A continuación: conozcamos -y hagámonos amigos- del código dentro de nuestra aplicación, para poder desmitificar lo que hace cada parte. Luego codificaremos. 
