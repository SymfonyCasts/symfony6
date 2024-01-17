# Conoce nuestra Diminuta App

Vamos a conocer nuestro nuevo proyecto porque mi objetivo final es que entiendas realmente cómo funcionan las cosas. Como he mencionado, no hay mucho aquí todavía... unos 15 archivos. Y realmente sólo hay tres directorios en los que tengamos que pensar o preocuparnos.

## El directorio public/

El primero es `public/`... y esto es sencillo: es la raíz del documento. En otras palabras, si necesitas que un archivo sea accesible públicamente -como un archivo de imagen o un archivo CSS- tiene que vivir dentro de `public/`.

Ahora mismo, esto contiene exactamente un archivo: `index.php`, que se llama "controlador frontal" 

[[[ code('f3303a0b1f') ]]]

Ooo. Es una palabra elegante que significa que, independientemente de la URL a la que vaya el usuario, éste es el script que siempre se ejecuta primero. Su trabajo es arrancar Symfony y ejecutar nuestra aplicación. Y ahora que lo hemos visto, probablemente no tengamos que pensar ni abrirlo nunca más.

## config/ & src/

Y, realmente, aparte de poner archivos CSS o de imagen en `public/`, este no es un directorio con el que vayas a tratar en el día a día. Lo que significa... Que en realidad sólo hay dos directorios en los que tenemos que pensar: `config/`
y `src/`.

El directorio `config/` contiene... ¡gatos! Ya me gustaría. No, contiene archivos de configuración. Y `src/` contiene el 100% de tus clases PHP. Pasaremos el 95% de nuestro tiempo dentro del directorio`src/`.

## composer.json & vendor/

Bien... ¿dónde está "Symfony"? Nuestro proyecto comenzó con un archivo `composer.json`. En él se enumeran todas las librerías de terceros que necesita nuestra aplicación. El comando "symfony new" que ejecutamos en secreto utilizó "composer" -es decir, el gestor de paquetes de PHP- para instalar estas librerías... que en realidad es sólo una forma de decir que Composer descargó estas librerías en el directorio `vendor/`.

El propio Symfony es en realidad una colección de un montón de pequeñas bibliotecas que resuelven cada una un problema específico. En el directorio `vendor/symfony/`, parece que ya tenemos unas 25 de ellas. Técnicamente, nuestra aplicación sólo requiere estos seis paquetes, pero algunos de ellos requieren otros paquetes... y Composer es lo suficientemente inteligente como para descargar todo lo que necesitamos.

De todos modos, "Symfony", o en realidad, un conjunto de bibliotecas de Symfony, vive en el directorio `vendor/`y nuestra nueva aplicación aprovecha ese código para hacer su trabajo. Más adelante hablaremos de Composer y de la instalación de paquetes de terceros. Pero en su mayor parte, `vendor/` es otro directorio del que... ¡no tenemos que preocuparnos!
# bin/ y var/

Entonces, ¿qué queda? Bueno, `bin/` contiene exactamente un archivo... y siempre contendrá sólo este archivo. Hablaremos de lo que hace `bin/console` un poco más tarde. Y el directorio `var/`contiene archivos de caché y de registro. Esos archivos son importantes... pero nunca necesitaremos mirar o pensar en esas cosas.

Sí, vamos a vivir casi exclusivamente dentro de los directorios `config/` y `src/`.

## Configuración de PhpStorm

Bien, una última tarea antes de empezar a codificar. Siéntete libre de utilizar el editor de código que quieras: PhpStorm, VS Code, code carrier pigeon, lo que sea. Pero recomiendo encarecidamente PhpStorm. Hace que desarrollar con Symfony sea un sueño... ¡y ni siquiera me pagan por decir eso! Aunque, si quieren empezar a pagarme, acepto el pago en stroopwafels.

Parte de lo que hace que PhpStorm sea tan bueno es un plugin diseñado específicamente para Symfony. Voy a mis preferencias de PhpStorm y, dentro, busco Plugins, Marketplace y luego busco Symfony. Aquí está. ¡Este plugin es increíble.... lo que puedes ver porque ha sido descargado 5,4 millones de veces! Añade toneladas de locas funciones de autocompletado que son específicas de Symfony.

Si aún no lo tienes, instálalo. Una vez instalado, vuelve a Configuración y busca aquí arriba "Symfony" para encontrar una nueva área de Symfony. El único truco de este plugin es que tienes que activarlo para cada proyecto. Así que haz clic en esa casilla. Además, no es demasiado importante, pero cambia el directorio web a `public/`.

Pulsa Ok y... ¡estamos listos! Vamos a dar vida a nuestra aplicación creando nuestra primera página a continuación.